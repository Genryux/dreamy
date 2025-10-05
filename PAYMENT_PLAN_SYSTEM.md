# Payment Plan System Documentation

## Overview

The payment plan system has been updated to support structured installment payments with flexible down payments, designed specifically for the community partner's business requirements.

## Business Logic

### Payment Structure
- **Total Invoice Amount**: The complete amount of school fees for a student
- **Down Payment**: Variable amount paid during enrollment (non-fixed)
- **Installment Period**: Fixed 9 months (configurable up to 12 months)
- **Monthly Payments**: Calculated by dividing remaining amount by 9 months
- **First Month Adjustment**: Any rounding differences or down payment shortfalls are added to the first month

### Calculation Examples

#### Scenario 1: Full Expected Down Payment
```
Total Invoice: ₱15,000
Down Payment: ₱4,500
Remaining: ₱15,000 - ₱4,500 = ₱10,500
Monthly: ₱10,500 ÷ 9 = ₱1,166.67
All 9 months: ₱1,166.67 each
```

#### Scenario 2: Partial Down Payment
```
Total Invoice: ₱15,000
Down Payment: ₱3,500
Remaining: ₱15,000 - ₱3,500 = ₱11,500
Monthly: ₱11,500 ÷ 9 = ₱1,277.78
Months 2-9: ₱1,277.78 each
Month 1: ₱1,277.78 (same as others due to equal division)
```

## Database Structure

### New Tables

#### `payment_plans`
Stores the overall payment plan configuration for an invoice.

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| invoice_id | bigint | Foreign key to invoices |
| total_amount | decimal(10,2) | Total invoice amount |
| down_payment_amount | decimal(10,2) | Actual down payment received |
| remaining_amount | decimal(10,2) | Amount to be paid in installments |
| installment_months | integer | Number of months (default 9) |
| monthly_amount | decimal(10,2) | Regular monthly payment |
| first_month_amount | decimal(10,2) | First month (with adjustments) |
| payment_type | string | 'installment' or 'full' |

#### `payment_schedules`
Stores individual payment schedules/due dates.

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| payment_plan_id | bigint | Foreign key to payment_plans |
| invoice_id | bigint | Foreign key to invoices |
| installment_number | integer | 0 = down payment, 1-9 = monthly |
| amount_due | decimal(10,2) | Expected payment amount |
| amount_paid | decimal(10,2) | Amount actually paid |
| due_date | date | Payment due date |
| status | string | pending, partial, paid, overdue |
| description | string | e.g., "Down Payment", "Month 1" |

### Updated Tables

#### `invoices`
Added payment plan fields:
- `has_payment_plan` (boolean) - Whether invoice has a payment plan
- `payment_mode` (string) - 'flexible', 'installment', or 'full'

#### `invoice_payments`
Added schedule tracking:
- `payment_schedule_id` (bigint, nullable) - Links payment to schedule

## API Endpoints

### Create Payment Plan
```
POST /invoice/{invoice}/payment-plan

Body:
{
  "down_payment": 4500.00,
  "installment_months": 9,
  "start_date": "2025-11-01" (optional)
}
```

### Calculate Payment Plan (Preview)
```
POST /payment-plan/calculate

Body:
{
  "total_amount": 15000.00,
  "down_payment": 4500.00,
  "installment_months": 9
}

Response:
{
  "plan": {
    "total_amount": 15000.00,
    "down_payment_amount": 4500.00,
    "remaining_amount": 10500.00,
    "monthly_amount": 1166.67,
    "first_month_amount": 1166.67,
    "installment_months": 9
  },
  "schedule": [...]
}
```

### Get Payment Plan Details
```
GET /invoice/{invoice}/payment-plan

Response:
{
  "total_amount": 15000.00,
  "down_payment": 4500.00,
  "remaining_amount": 10500.00,
  "monthly_amount": 1166.67,
  "first_month_amount": 1166.67,
  "installment_months": 9,
  "schedules": [...],
  "paid_schedules": 2,
  "pending_schedules": 7,
  "next_due": {...}
}
```

### Update Payment Plan
```
PUT /payment-plan/{paymentPlan}

Body:
{
  "down_payment": 3500.00
}
```

### Delete Payment Plan
```
DELETE /payment-plan/{paymentPlan}
```

## Service Layer

### PaymentPlanService

#### `createInstallmentPlan($invoice, $downPaymentAmount, $installmentMonths, $startDate)`
Creates a complete payment plan with schedules.

#### `recordPayment($invoice, $amount, $paymentData)`
Records a payment and automatically applies it to schedules in order.

#### `adjustDownPayment($paymentPlan, $actualDownPayment)`
Updates payment plan when down payment differs from expected.

#### `getPaymentPlanSummary($invoice)`
Returns complete payment plan information including schedules.

## Usage Examples

### Creating a Payment Plan
```php
use App\Services\PaymentPlanService;

$paymentPlanService = app(PaymentPlanService::class);

$paymentPlan = $paymentPlanService->createInstallmentPlan(
    $invoice,
    $downPaymentAmount = 4500.00,
    $installmentMonths = 9,
    $startDate = Carbon::now()->addMonth()
);
```

### Recording a Payment
```php
$payment = $paymentPlanService->recordPayment(
    $invoice,
    $amount = 1166.67,
    [
        'payment_date' => now(),
        'method' => 'Cash',
        'type' => 'Monthly Installment',
        'reference_no' => 'OR-12345'
    ]
);
```

### Getting Payment Plan Summary
```php
$summary = $paymentPlanService->getPaymentPlanSummary($invoice);

// Returns:
// - total_amount
// - down_payment
// - monthly_amount
// - first_month_amount
// - schedules (collection)
// - paid_schedules (count)
// - pending_schedules (count)
// - next_due (schedule object)
```

## Payment Processing Logic

### With Payment Plan (Installment Mode)
1. Payment is recorded
2. System finds unpaid/partial schedules in order
3. Payment is applied to schedules sequentially
4. Each schedule status is updated
5. Invoice status updates when fully paid

### Without Payment Plan (Flexible Mode)
1. Payment is recorded directly
2. No schedule tracking
3. Invoice status updates based on balance

## Migration Instructions

1. Run migrations:
```bash
php artisan migrate
```

2. The system is backward compatible:
   - Existing invoices continue as "flexible" payment mode
   - New invoices can choose payment mode
   - Payment plans are optional

## UI Features

### Invoice Show Page

#### For Invoices with Payment Plan:
- Payment plan summary cards showing:
  - Down payment amount
  - Monthly payment amount
  - First month amount
  - Total months
- Detailed payment schedule table
- Status indicators (paid, partial, overdue, pending)
- Next due payment highlight

#### For Invoices without Payment Plan:
- "Create Payment Plan" section
- Preview calculator
- Modal for creating payment plan

### Payment Recording
- Automatically applies to schedules if payment plan exists
- Updates schedule statuses
- Shows which installment was paid

## Status Management

### Invoice Status
- `unpaid` - No payments made
- `partially_paid` - Some payment made but not complete
- `paid` - Fully paid

### Schedule Status
- `pending` - Not yet paid, not overdue
- `partial` - Partially paid
- `paid` - Fully paid
- `overdue` - Past due date and not paid

## Key Features

1. **Flexible Down Payments**: Any amount can be set as down payment
2. **Automatic Calculation**: Monthly amounts calculated automatically
3. **Rounding Handling**: Ensures total payments match invoice amount exactly
4. **Schedule Tracking**: Detailed tracking of each payment
5. **Status Indicators**: Clear visual indicators for payment status
6. **Backward Compatible**: Works with existing flexible payment system
7. **Due Date Management**: Automatic due date calculation
8. **Payment Application**: Smart payment application to schedules in order

## Future Enhancements

Potential improvements:
- SMS/Email reminders for upcoming due dates
- Late payment penalties
- Early payment discounts
- Multiple payment plan templates
- Student self-service payment plan selection
- Payment history export
- Analytics dashboard for payment trends

