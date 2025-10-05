# Student-Driven Payment Plan Selection

## Overview
Implemented a student-driven payment plan selection system where students choose their payment preference through the mobile app, and the payment plan is automatically created on the admin side.

## Implementation Summary

### ✅ **Mobile App Changes (React Native)**

#### 1. Enhanced Invoice Card
- **Two stacked buttons** appear for unpaid invoices without a payment plan:
  - **One-Time Payment** (blue background with white text)
  - **Monthly Installment** (neutral background)
- Both buttons include icons for visual clarity

#### 2. Payment Plan Breakdown Modal
Shows when student clicks "Monthly Installment":
- **Payment Breakdown Summary**:
  - Total Amount
  - Down Payment (₱4,500 - admin configurable later)
  - Remaining Balance
- **9-Month Schedule**:
  - Down Payment (Month 0)
  - Month 1-9 with amounts and due dates
- **Two Action Buttons**:
  - **Cancel** - Close modal without creating plan
  - **Confirm Plan** - Create payment plan

#### 3. Confirmed Payment Plan View
Shows after student confirms plan:
- **Plan Header** with icon
- **Payment Summary Cards**:
  - Down Payment amount
  - Monthly Amount
- **Payment Schedule List**:
  - Shows first 3 schedules
  - Status badges (paid, partial, pending, overdue)
  - "+X more payments" indicator
- **NO Cancel Button** - Plan is locked once confirmed

### ✅ **Backend Changes (Laravel)**

#### 1. New API Endpoints

**Calculate Payment Plan Preview** (No Auth Required for Preview)
```
POST /api/financial/payment-plan/calculate
Body: {
  "total_amount": 17000,
  "down_payment": 4500,
  "installment_months": 9
}
Response: {
  "success": true,
  "plan": {
    "total_amount": 17000,
    "down_payment_amount": 4500,
    "remaining_amount": 12500,
    "monthly_amount": 1388.89,
    "first_month_amount": 1389.89,
    "installment_months": 9
  },
  "schedule": [...]
}
```

**Select Payment Mode** (Student Auth Required)
```
POST /api/financial/invoice/{invoiceId}/payment-plan/select
Body: {
  "payment_mode": "installment" // or "full"
}
Response: {
  "success": true,
  "message": "Monthly installment plan created successfully",
  "payment_plan": {...}
}
```

#### 2. Updated API Endpoints

**Get Invoices** - Now includes payment plan data:
```
GET /api/financial/invoices
Response: {
  "data": {
    "invoices": [
      {
        "id": 1,
        "has_payment_plan": true,
        "payment_mode": "installment",
        "payment_plan": {
          "down_payment_amount": 4500,
          "monthly_amount": 1388.89,
          "schedules": [...]
        }
      }
    ]
  }
}
```

#### 3. Service Layer Integration
- Uses existing `PaymentPlanService::createInstallmentPlan()`
- Automatically creates down payment record
- Creates 9-month payment schedule
- Updates invoice status

### 📱 **User Flow**

```
Student opens Invoice Tab
    ↓
Sees unpaid invoice
    ↓
Two stacked buttons appear:
[One-Time Payment (Blue)]
[Monthly Installment (Neutral)]
    ↓
Student clicks "Monthly Installment"
    ↓
Modal shows breakdown:
• Down Payment: ₱4,500
• Month 1: ₱1,388.89
• Month 2-9: ₱1,388.89 each
    ↓
Two buttons shown:
[Cancel] [Confirm Plan]
    ↓
Student clicks "Confirm Plan"
    ↓
API creates payment plan automatically
    ↓
Invoice card updates to show:
• Payment plan summary
• First 3 schedules
• Status badges
• NO cancel button (locked)
    ↓
Admin sees 9-month table automatically
```

## Key Features

### Student Side
✅ **Simple Choice** - Just two buttons
✅ **Clear Preview** - See full breakdown before confirming
✅ **Cancel Option** - Can back out before confirming
✅ **Locked After Confirmation** - No changes after plan is created
✅ **Visual Feedback** - Status badges, icons, color coding

### Admin Side
✅ **Zero Manual Work** - Payment plans appear automatically
✅ **Existing Views Work** - 9-month table shows up as expected
✅ **All Logic Preserved** - Payment recording, status tracking unchanged
✅ **Down Payment Recorded** - Automatically creates payment record

## Technical Details

### Mobile App Files Modified
1. `d:/dreamy_app/components/financial/InvoicesTab.tsx` - Main component with UI and logic

### Backend Files Modified
1. `app/Http/Controllers/Api/FinancialController.php` - Added payment plan endpoints
2. `routes/api.php` - Added new API routes

### Backend Files Used (Already Existing)
1. `app/Services/PaymentPlanService.php` - Payment plan creation logic
2. `app/Models/PaymentPlan.php` - Payment plan model
3. `app/Models/PaymentSchedule.php` - Schedule model
4. `app/Models/Invoice.php` - Updated with payment plan relationships

## Configuration

### Current Settings (Fixed)
- **Down Payment**: ₱4,500 (hardcoded)
- **Installment Months**: 9 (fixed)
- **Payment Mode Options**: "installment" or "full"

### Future Enhancements (TODO)
- [ ] Admin UI to configure down payment amount
- [ ] Variable installment months (6, 9, 12)
- [ ] Minimum down payment rules
- [ ] Student payment history integration

## Business Logic

### Payment Calculation
```php
Total: ₱17,000
Down Payment: ₱4,500
Remaining: ₱12,500
Monthly: ₱12,500 ÷ 9 = ₱1,388.89
First Month: ₱1,388.89 + rounding difference
```

### Down Payment Handling
- Down payment is automatically recorded as a payment
- Creates payment schedule entry (installment_number = 0)
- Marks as "paid" immediately
- Included in invoice `paid_amount`

### Payment Plan States
1. **No Plan** - Show selection buttons
2. **Viewing Breakdown** - Show cancel + confirm
3. **Plan Confirmed** - Show plan details, no cancel

## API Response Examples

### Invoice without Plan
```json
{
  "id": 1,
  "invoice_number": "INV-20251004-0001",
  "status": "unpaid",
  "total_amount": 17000,
  "balance": 17000,
  "has_payment_plan": false,
  "payment_mode": "flexible"
}
```

### Invoice with Installment Plan
```json
{
  "id": 1,
  "has_payment_plan": true,
  "payment_mode": "installment",
  "payment_plan": {
    "id": 1,
    "down_payment_amount": 4500,
    "monthly_amount": 1388.89,
    "first_month_amount": 1389.89,
    "installment_months": 9,
    "schedules": [
      {
        "installment_number": 0,
        "description": "Down Payment",
        "amount_due": 4500,
        "amount_paid": 4500,
        "status": "paid"
      },
      {
        "installment_number": 1,
        "description": "Month 1",
        "amount_due": 1389.89,
        "amount_paid": 0,
        "status": "pending"
      }
    ]
  }
}
```

## Testing

### Manual Testing Steps
1. **Create Test Invoice**
   - Create invoice for a student
   - Verify total amount

2. **Mobile App - View Invoice**
   - Open mobile app as student
   - Navigate to Financial > Invoices tab
   - Verify two buttons appear

3. **Test Monthly Installment Flow**
   - Click "Monthly Installment"
   - Verify breakdown modal shows
   - Check calculations (down payment, monthly amounts)
   - Click "Cancel" - modal should close
   - Click "Monthly Installment" again
   - Click "Confirm Plan"
   - Verify modal closes
   - Verify invoice card updates with plan details

4. **Admin Side Verification**
   - Log into admin panel
   - Navigate to invoice
   - Verify 9-month payment schedule table appears
   - Verify down payment is recorded
   - Check invoice paid_amount includes down payment

5. **Test One-Time Payment**
   - Create another invoice
   - Click "One-Time Payment"
   - Verify payment_mode is set to "full"
   - Verify no payment plan is created

### Edge Cases to Test
- [ ] Invoice amount very small (₱500)
- [ ] Down payment equals total amount
- [ ] Student tries to create plan twice (should fail)
- [ ] Network error during plan creation
- [ ] Refresh after plan creation

## Known Limitations

1. **Fixed Down Payment** - Currently hardcoded to ₱4,500
2. **No Plan Modification** - Once confirmed, student cannot change
3. **Single Invoice** - Assumes one invoice per student per term
4. **Mobile View Only** - Payment plan selection not available on web

## Future Roadmap

### Phase 1 (Current) ✅
- Student payment plan selection
- Automatic plan creation
- Basic UI

### Phase 2 (Next)
- [ ] Admin configurable down payment
- [ ] Variable installment months
- [ ] Student payment reminders
- [ ] Payment plan analytics

### Phase 3 (Future)
- [ ] Multiple payment plan templates
- [ ] Early payment discounts
- [ ] Late payment penalties
- [ ] Payment plan modification (admin only)

## Support

### Common Issues

**Q: Buttons not showing?**
A: Verify invoice is unpaid and has no existing payment plan

**Q: Modal not opening?**
A: Check API endpoint is accessible and returning data

**Q: Plan not creating?**
A: Check backend logs, verify student owns the invoice

**Q: Admin table not showing?**
A: Refresh page, verify payment_plan relationship is loaded

### Debugging

**Mobile App Console:**
```javascript
console.log('Invoice:', invoice);
console.log('Payment Plan Preview:', paymentPlanPreview);
```

**Backend Logs:**
```bash
tail -f storage/logs/laravel.log
```

## Conclusion

The student-driven payment plan selection system is now fully implemented and integrated with both the mobile app and admin panel. Students can easily choose their payment preference, and the system automatically creates the necessary records without any manual admin intervention.

