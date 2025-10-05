# Fix for Existing Payment Plans

## Issue
If you created payment plans before this fix, the down payment might not have been recorded as an actual `InvoicePayment` record. This causes a balance discrepancy where all 9 monthly payments are made, but the invoice still shows a balance equal to the down payment.

## Example of the Issue
```
Invoice Total: ₱17,000
Down Payment: ₱4,500
9 Monthly Payments: ₱12,500
Balance Remaining: ₱4,500 (INCORRECT - should be ₱0)
```

## What Was Fixed
The `PaymentPlanService::createInstallmentPlan()` method now creates an actual `InvoicePayment` record for the down payment when the payment plan is created. This ensures:
1. The down payment is counted in the invoice's `paid_amount`
2. The invoice balance is calculated correctly
3. All payments (down payment + monthly installments) equal the invoice total

## For Existing Payment Plans

If you already created payment plans before this fix, you can manually fix them:

### Option 1: Delete and Recreate (Recommended)
1. Go to the invoice
2. Delete the existing payment plan
3. Create a new payment plan with the same down payment amount
4. The down payment will now be properly recorded

### Option 2: Manual Fix via Database (Advanced)

Run this SQL to create missing down payment records:

```sql
-- Find payment plans missing down payment InvoicePayment records
SELECT 
    pp.id as plan_id,
    pp.invoice_id,
    pp.down_payment_amount,
    ps.id as schedule_id,
    COUNT(ip.id) as payment_count
FROM payment_plans pp
INNER JOIN payment_schedules ps ON ps.payment_plan_id = pp.id AND ps.installment_number = 0
LEFT JOIN invoice_payments ip ON ip.payment_schedule_id = ps.id
WHERE pp.down_payment_amount > 0
GROUP BY pp.id, pp.invoice_id, pp.down_payment_amount, ps.id
HAVING payment_count = 0;

-- For each row from above, insert the missing payment record:
INSERT INTO invoice_payments (
    invoice_id,
    payment_schedule_id,
    amount,
    payment_date,
    method,
    type,
    reference_no,
    academic_term_id,
    created_at,
    updated_at
)
SELECT 
    pp.invoice_id,
    ps.id,
    pp.down_payment_amount,
    ps.due_date,
    'Cash',
    'Down Payment',
    CONCAT('PAY-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 9999), 4, '0')),
    i.academic_term_id,
    NOW(),
    NOW()
FROM payment_plans pp
INNER JOIN payment_schedules ps ON ps.payment_plan_id = pp.id AND ps.installment_number = 0
INNER JOIN invoices i ON i.id = pp.invoice_id
LEFT JOIN invoice_payments ip ON ip.payment_schedule_id = ps.id
WHERE pp.down_payment_amount > 0
AND ip.id IS NULL;
```

### Option 3: Artisan Command (Best for Multiple Fixes)

Create a one-time fix command:

```php
// app/Console/Commands/FixPaymentPlanDownPayments.php
<?php

namespace App\Console\Commands;

use App\Models\PaymentPlan;
use App\Models\InvoicePayment;
use Illuminate\Console\Command;

class FixPaymentPlanDownPayments extends Command
{
    protected $signature = 'payment-plan:fix-down-payments';
    protected $description = 'Add missing down payment records to existing payment plans';

    public function handle()
    {
        $this->info('Finding payment plans with missing down payment records...');
        
        $fixed = 0;
        
        PaymentPlan::with(['schedules', 'invoice'])->chunk(100, function ($plans) use (&$fixed) {
            foreach ($plans as $plan) {
                if ($plan->down_payment_amount <= 0) {
                    continue;
                }
                
                // Find down payment schedule
                $downPaymentSchedule = $plan->schedules()
                    ->where('installment_number', 0)
                    ->first();
                
                if (!$downPaymentSchedule) {
                    $this->warn("Plan {$plan->id}: No down payment schedule found");
                    continue;
                }
                
                // Check if payment record exists
                $existingPayment = InvoicePayment::where('payment_schedule_id', $downPaymentSchedule->id)
                    ->first();
                
                if ($existingPayment) {
                    $this->line("Plan {$plan->id}: Down payment already recorded");
                    continue;
                }
                
                // Create missing payment record
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                
                InvoicePayment::create([
                    'invoice_id' => $plan->invoice_id,
                    'payment_schedule_id' => $downPaymentSchedule->id,
                    'academic_term_id' => $activeTerm ? $activeTerm->id : $plan->invoice->academic_term_id,
                    'amount' => $plan->down_payment_amount,
                    'payment_date' => $downPaymentSchedule->due_date ?? now(),
                    'method' => 'Cash',
                    'type' => 'Down Payment',
                    'reference_no' => null, // Will be auto-generated
                ]);
                
                // Update invoice status
                $invoice = $plan->invoice;
                $invoice->refresh();
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();
                
                $fixed++;
                $this->info("Plan {$plan->id}: Fixed! Added down payment of ₱{$plan->down_payment_amount}");
            }
        });
        
        $this->info("✅ Fixed {$fixed} payment plan(s)");
        
        return 0;
    }
}
```

Then run:
```bash
php artisan payment-plan:fix-down-payments
```

## Verification

After fixing, verify the invoice balance:

```php
$invoice = Invoice::find($invoiceId);
$invoice->refresh();

echo "Total Amount: ₱" . number_format($invoice->total_amount, 2) . "\n";
echo "Paid Amount: ₱" . number_format($invoice->paid_amount, 2) . "\n";
echo "Balance: ₱" . number_format($invoice->balance, 2) . "\n";

// Should show:
// Total Amount: ₱17,000.00
// Paid Amount: ₱17,000.00 (if all payments made)
// Balance: ₱0.00
```

## Prevention
This issue is now fixed in the code. All new payment plans will automatically create the down payment record.

