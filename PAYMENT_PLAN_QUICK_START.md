# Payment Plan System - Quick Start Guide

## 🚀 Getting Started (5 Minutes)

### Step 1: Run Migrations
```bash
cd "D:\Project folder\Herd\dreamy"
php artisan migrate
```

### Step 2: Test the System
1. Navigate to any invoice with an outstanding balance
2. Look for the "Create Payment Plan" button
3. Click it and enter:
   - **Down Payment**: ₱4,500
   - **Installment Months**: 9
4. Click "Calculate Preview" to see the breakdown
5. Submit to create the plan

## 💡 How It Works

### Example Calculation
```
Invoice Total:    ₱15,000
Down Payment:     ₱4,500
---------------------------
Remaining:        ₱10,500
Monthly Payment:  ₱1,166.67
Duration:         9 months
```

### Payment Recording
When you record a payment:
1. System finds the next unpaid installment
2. Applies payment to that installment
3. Updates status (pending → partial → paid)
4. Moves to next installment if payment exceeds amount due

## 📊 Features at a Glance

| Feature | Description |
|---------|-------------|
| **Flexible Down Payment** | Accept any amount as down payment |
| **Fixed Installments** | 9 months (configurable 1-12) |
| **Auto Calculation** | Monthly amounts calculated automatically |
| **Smart Application** | Payments applied to schedules in order |
| **Status Tracking** | pending, partial, paid, overdue |
| **Visual Indicators** | Color-coded badges for each status |

## 🎯 Common Use Cases

### Case 1: Standard Payment Plan
```php
Student: John Doe
Total Invoice: ₱15,000
Down Payment: ₱4,500 (paid during enrollment)
Monthly: ₱1,166.67 for 9 months
```

### Case 2: Lower Down Payment
```php
Student: Jane Smith
Total Invoice: ₱15,000
Down Payment: ₱3,500 (paid during enrollment)
Monthly: ₱1,277.78 for 9 months
```

### Case 3: Higher Down Payment
```php
Student: Bob Johnson
Total Invoice: ₱15,000
Down Payment: ₱6,000 (paid during enrollment)
Monthly: ₱1,000 for 9 months
```

## 🔍 UI Components

### Invoice Page WITH Payment Plan
```
┌─────────────────────────────────────┐
│ Payment Plan Summary                │
├─────────────────────────────────────┤
│ Down Payment    Monthly    First   │
│ ₱4,500         ₱1,166.67   ₱1,166  │
├─────────────────────────────────────┤
│ Payment Schedule Table              │
│ [#] [Description] [Due] [Status]    │
│ [0] Down Payment  Paid              │
│ [1] Month 1       Pending           │
│ [2] Month 2       Pending           │
│ ...                                 │
└─────────────────────────────────────┘
```

### Invoice Page WITHOUT Payment Plan
```
┌─────────────────────────────────────┐
│ 📅 Set Up Payment Plan              │
├─────────────────────────────────────┤
│ Create structured installment plan  │
│ [Create Payment Plan] Button        │
└─────────────────────────────────────┘
```

## 🎨 Status Colors

| Status | Color | Meaning |
|--------|-------|---------|
| ✅ Paid | Green | Fully paid |
| ⏳ Pending | Gray | Not yet due or paid |
| 🟡 Partial | Yellow | Partially paid |
| 🔴 Overdue | Red | Past due date |

## 📝 API Quick Reference

### Calculate Preview (Before Creating)
```javascript
POST /payment-plan/calculate
{
  "total_amount": 15000,
  "down_payment": 4500,
  "installment_months": 9
}
```

### Create Payment Plan
```javascript
POST /invoice/{invoiceId}/payment-plan
{
  "down_payment": 4500,
  "installment_months": 9
}
```

### Get Payment Plan Details
```javascript
GET /invoice/{invoiceId}/payment-plan
```

## 🔧 Troubleshooting

### Issue: Migration Error
**Solution**: Make sure you're in the correct directory
```bash
cd "D:\Project folder\Herd\dreamy"
php artisan migrate
```

### Issue: Payment Plan Button Not Showing
**Check**:
- Invoice has outstanding balance
- Invoice doesn't already have a payment plan
- User has proper permissions

### Issue: Calculation Doesn't Match
**Verify**:
- Down payment is less than total amount
- Installment months is between 1-12
- Total amount is correct

## 📖 Where to Find More Info

- **Full Documentation**: `PAYMENT_PLAN_SYSTEM.md`
- **Implementation Details**: `PAYMENT_PLAN_IMPLEMENTATION_SUMMARY.md`
- **Service Layer**: `app/Services/PaymentPlanService.php`
- **Controller**: `app/Http/Controllers/PaymentPlanController.php`

## 🎓 Training Scenarios

### Scenario 1: Creating First Payment Plan
1. Open an unpaid invoice
2. Scroll to "Set Up Payment Plan" section
3. Click "Create Payment Plan"
4. Enter down payment: ₱4,500
5. Click "Calculate Preview"
6. Review the breakdown
7. Click "Create Plan"
8. See the payment schedule table appear

### Scenario 2: Recording Monthly Payment
1. Open an invoice with payment plan
2. Click "Record Payment"
3. Enter amount: ₱1,166.67
4. Select payment method: Cash
5. Enter type: Monthly Installment
6. Submit
7. See schedule update (Month 1 → Paid)

### Scenario 3: Partial Payment
1. Open an invoice with payment plan
2. Click "Record Payment"
3. Enter amount: ₱500 (less than due)
4. Submit
5. See schedule update (Month 1 → Partial)
6. Next payment will complete Month 1 first

## ⚡ Pro Tips

1. **Preview First**: Always click "Calculate Preview" to verify calculations
2. **Track Status**: Use the payment schedule table to track all installments
3. **Flexible Down Payment**: Can be any amount (even ₱0 if needed)
4. **Smart Payments**: Payments automatically apply to oldest unpaid schedule
5. **Backward Compatible**: Existing invoices continue as "flexible" mode

## 🎯 Success Metrics

After implementation, you should be able to:
- ✅ Create payment plans in under 30 seconds
- ✅ Track 9-month payment schedules easily
- ✅ See clear status indicators for each payment
- ✅ Record payments that auto-apply to schedules
- ✅ Handle flexible down payment amounts

## 🆘 Need Help?

1. Check the documentation files
2. Review the code comments in service files
3. Test with sample data first
4. Verify migrations ran successfully

---

**Remember**: The system is backward compatible. All existing invoices continue to work in "flexible" payment mode!

