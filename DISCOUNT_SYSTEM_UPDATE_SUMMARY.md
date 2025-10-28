# Discount System Update Summary

## Overview
Updated the discount calculation system to apply custom discounts to the **total invoice amount** instead of the payment/down payment amount, making it consistent with how early enrollment discounts work.

---

## Changes Made

### 1. **Frontend - Invoice Show View** (`resources/views/user-admin/invoice/show.blade.php`)

#### A. Custom Discount Calculation (Lines 1087-1097)
**Changed:** Custom discounts now calculate based on total invoice amount
```javascript
// Calculate custom discounts (applied to total invoice amount)
if (isDownPayment || !hasPaymentPlan) {
    const totalInvoiceAmount = {{ $invoice->total_amount }};
    discountCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const discountAmount = getDiscountAmount(checkbox.value, totalInvoiceAmount);
            customDiscounts += discountAmount;
            totalDiscount += discountAmount;
        }
    });
}
```

#### B. Down Payment Live Calculation (Lines 1102-1115)
**Formula:** `(Invoice Total - Early Discount - Down Payment - Custom Discounts) / 9 months`
```javascript
const totalInvoiceAmount = {{ $invoice->total_amount }};
const discountedTotal = totalInvoiceAmount - earlyDiscount;
const remainingBalance = discountedTotal - baseAmount - customDiscounts;
const monthlyAmount = remainingBalance / 9;
```

**Display:** `Remaining: ₱4,000.00 / 9 months = ₱444.44`

#### C. One-Time Payment Display (Lines 1119-1124)
**Formula:** `Invoice Total - Early Discount - Custom Discounts`
```javascript
const totalInvoiceAmount = {{ $invoice->total_amount }};
const discountedTotal = totalInvoiceAmount - earlyDiscount - customDiscounts;
totalDisplay.textContent = `Total to Pay: ₱${Math.max(0, discountedTotal).toFixed(2)}`;
```

**Display:** `Total to Pay: ₱9,000.00`

#### D. Helper Function Updated (Lines 1137-1156)
```javascript
function getDiscountAmount(discountId, baseAmount = null) {
    // ...
    if (amountText.includes('%')) {
        const percentage = parseFloat(amountText.replace('%', ''));
        // Use baseAmount (invoice total) if provided, otherwise use payment amount
        const calculationBase = baseAmount !== null ? baseAmount : (parseFloat(amountInput.value) || 0);
        return calculationBase * (percentage / 100);
    }
    // ...
}
```

---

### 2. **Backend - Invoice Payment Controller** (`app/Http/Controllers/InvoicePaymentController.php`)

#### Custom Discount Calculation (Lines 110-120)
**Changed:** Custom discounts now apply to invoice total, not payment amount
```php
// Calculate custom discounts for record keeping (applied to total invoice amount)
$customDiscountsTotal = 0;
if (isset($validated['custom_discount_enabled']) && $validated['custom_discount_enabled'] && isset($validated['selected_discounts'])) {
    foreach ($validated['selected_discounts'] as $discountId) {
        $discount = \App\Models\Discount::find($discountId);
        if ($discount && $discount->is_active) {
            // Apply custom discount to total invoice amount, not payment amount
            $customDiscountsTotal += $discount->calculateDiscount($invoice->total_amount);
        }
    }
}
```

---

### 3. **Backend - Invoice Model** (`app/Models/Invoice.php`)

#### A. Updated Balance Calculation (Lines 84-98)
**Added:** Discount handling for flexible/one-time payments
```php
public function getBalanceAttribute()
{
    // For installment plans, use the discounted total if payment plan exists
    if ($this->has_payment_plan && $this->paymentPlan) {
        $discountedTotal = $this->paymentPlan->discounted_total ?? $this->total_amount;
        $balance = $discountedTotal - $this->paid_amount;
    } else {
        // For flexible/one-time payments, subtract total discounts from invoice total
        $totalDiscounts = $this->payments->sum('total_discount');
        $discountedTotal = $this->total_amount - $totalDiscounts;
        $balance = $discountedTotal - $this->paid_amount;
    }
    return round($balance, 2);
}
```

#### B. New: Discounted Total Accessor (Lines 100-110)
```php
public function getDiscountedTotalAttribute()
{
    if ($this->has_payment_plan && $this->paymentPlan) {
        return $this->paymentPlan->discounted_total ?? $this->total_amount;
    } else {
        // For flexible/one-time payments, subtract total discounts from invoice total
        $totalDiscounts = $this->payments->sum('total_discount');
        return round($this->total_amount - $totalDiscounts, 2);
    }
}
```

#### C. New: Total Discount Accessor (Lines 112-120)
```php
public function getTotalDiscountAttribute()
{
    if ($this->has_payment_plan && $this->paymentPlan) {
        return $this->paymentPlan->total_discount ?? 0;
    } else {
        return $this->payments->sum('total_discount');
    }
}
```

---

## Example Calculations

### Scenario:
- **Invoice Total:** ₱15,000
- **Early Enrollment Discount:** 20% = ₱3,000
- **Custom Discount:** 20% = ₱3,000
- **Down Payment:** ₱5,000

### Installment Plan (Down Payment):

**Step-by-step:**
1. Early Discount: ₱15,000 × 20% = ₱3,000
2. Custom Discount: ₱15,000 × 20% = ₱3,000
3. Discounted Total: ₱15,000 - ₱3,000 = ₱12,000
4. Remaining Balance: ₱12,000 - ₱5,000 - ₱3,000 = ₱4,000
5. Monthly Payment: ₱4,000 / 9 = ₱444.44

**Display:** `Remaining: ₱4,000.00 / 9 months = ₱444.44`

### One-Time Payment:

**Step-by-step:**
1. Early Discount: ₱15,000 × 20% = ₱3,000
2. Custom Discount: ₱15,000 × 20% = ₱3,000
3. Total to Pay: ₱15,000 - ₱3,000 - ₱3,000 = ₱9,000

**Display:** `Total to Pay: ₱9,000.00`

---

## Before vs After Comparison

### Before (WRONG):
- **Custom Discount Base:** Payment/Down payment amount
- **Example:** ₱5,000 × 20% = ₱1,000 discount
- **Monthly:** ₱6,000 / 9 = ₱666.67

### After (CORRECT):
- **Custom Discount Base:** Total invoice amount
- **Example:** ₱15,000 × 20% = ₱3,000 discount
- **Monthly:** ₱4,000 / 9 = ₱444.44

---

## What Was NOT Changed

### ✅ Kept Intact:
1. **PaymentPlanService::createInstallmentPlan()** - Already correct
2. **PaymentPlanService::recalculatePaymentPlan()** - Already correct
3. **PaymentPlan::calculate()** - Already correct
4. **Early enrollment discount calculation** - Already correct
5. **Payment recording flow** - Only discount calculation changed
6. **Database structure** - No migration needed

---

## Benefits

1. **Consistency:** Both early and custom discounts now apply to invoice total
2. **Fairness:** Larger discounts for students (calculated on full amount)
3. **Clarity:** Clear distinction between discount types
4. **Accuracy:** Correct monthly payment calculations
5. **Flexibility:** Works for both installment and one-time payments

---

## Testing Checklist

### Installment Plan (Down Payment):
- [ ] Enter down payment amount
- [ ] Check early enrollment discount applies to invoice total
- [ ] Check custom discount applies to invoice total
- [ ] Verify remaining balance calculation
- [ ] Verify monthly payment calculation
- [ ] Submit payment and verify backend records discounts correctly

### One-Time Payment:
- [ ] Enter payment amount
- [ ] Check early enrollment discount applies to invoice total
- [ ] Check custom discount applies to invoice total
- [ ] Verify total to pay calculation
- [ ] Submit payment and verify balance updates correctly

### Edge Cases:
- [ ] Multiple custom discounts checked
- [ ] Fixed amount discount (not percentage)
- [ ] No discounts selected
- [ ] Early enrollee without custom discount
- [ ] Non-early enrollee with custom discount

---

## Notes

- All discount calculations are for **real-time preview only** in the frontend
- Backend recalculates everything based on selected discounts
- No data integrity issues - discounts are properly tracked in database
- Invoice balance now correctly reflects all applied discounts
