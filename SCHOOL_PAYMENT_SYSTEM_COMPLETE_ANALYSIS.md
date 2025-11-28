# School Payment System

## Overview
The school payment system is a comprehensive financial management solution that handles invoices, payment plans, installments, discounts, and various payment methods. It supports both flexible payments and structured installment plans with early enrollment discounts.

## Core Entities and Relationships

### 1. **Student** (Central Entity)
- **Purpose**: Represents enrolled students who receive invoices
- **Key Fields**: `user_id`, `program_id`, `grade_level`, `enrollment_period_id`
- **Relationships**:
  - `belongsTo(User)` - User account information
  - `belongsTo(Program)` - Academic program
  - `belongsTo(EnrollmentPeriod)` - Enrollment period for early discounts
  - `hasMany(Invoice)` - All invoices for this student

### 2. **SchoolFee** (Fee Definition)
- **Purpose**: Defines different types of fees (tuition, library, etc.)
- **Key Fields**: `name`, `amount`, `program_id`, `grade_level`, `academic_term_id`
- **Relationships**:
  - `belongsTo(Program)` - Optional program-specific fees
  - `belongsTo(AcademicTerms)` - Term-specific fees
  - `hasMany(InvoiceItem)` - Used in invoice items

### 3. **Invoice** (Payment Container)
- **Purpose**: Main container for student's financial obligations
- **Key Fields**: 
  - `student_id`, `academic_term_id`, `status` (unpaid/paid/partially_paid)
  - `invoice_number` (auto-generated: INV-YYYYMMDD-XXXX)
  - `has_payment_plan` (boolean), `payment_mode` (flexible/installment/full)
- **Relationships**:
  - `belongsTo(Student)` - Student who owes money
  - `belongsTo(AcademicTerms)` - Academic term
  - `hasMany(InvoiceItem)` - Individual fee items
  - `hasMany(InvoicePayment)` - Payment records
  - `hasOne(PaymentPlan)` - Optional installment plan
  - `hasMany(PaymentSchedule)` - Payment schedule items

### 4. **InvoiceItem** (Fee Line Items)
- **Purpose**: Individual fee components within an invoice
- **Key Fields**: `invoice_id`, `school_fee_id`, `amount`, `academic_term_id`
- **Relationships**:
  - `belongsTo(Invoice)` - Parent invoice
  - `belongsTo(SchoolFee)` - Fee definition
  - `belongsTo(AcademicTerms)` - Academic term

### 5. **PaymentPlan** (Installment Structure)
- **Purpose**: Defines installment payment structure
- **Key Fields**:
  - `invoice_id`, `total_amount`, `discounted_total`, `total_discount`
  - `down_payment_amount`, `remaining_amount`
  - `installment_months` (default 9), `monthly_amount`, `first_month_amount`
  - `payment_type` (installment/full)
- **Relationships**:
  - `belongsTo(Invoice)` - Associated invoice
  - `hasMany(PaymentSchedule)` - Payment schedule items

### 6. **PaymentSchedule** (Payment Timeline)
- **Purpose**: Individual payment due dates and amounts
- **Key Fields**:
  - `payment_plan_id`, `invoice_id`, `installment_number` (0=down payment, 1-9=monthly)
  - `amount_due`, `amount_paid`, `due_date`, `status` (pending/partial/paid/overdue)
  - `description` (e.g., "Down Payment", "December 2025")
- **Relationships**:
  - `belongsTo(PaymentPlan)` - Parent payment plan
  - `belongsTo(Invoice)` - Associated invoice
  - `hasMany(InvoicePayment)` - Payments made against this schedule

### 7. **InvoicePayment** (Payment Records)
- **Purpose**: Records actual payments made
- **Key Fields**:
  - `invoice_id`, `payment_schedule_id` (optional)
  - `amount` (final amount paid), `original_amount` (before discounts)
  - `early_discount`, `custom_discounts`, `total_discount`
  - `payment_date`, `method`, `type`, `reference_no`
  - `academic_term_id`
- **Relationships**:
  - `belongsTo(Invoice)` - Associated invoice
  - `belongsTo(PaymentSchedule)` - Optional schedule payment
  - `belongsTo(AcademicTerms)` - Academic term

### 8. **EnrollmentPeriod** (Early Discount System)
- **Purpose**: Manages enrollment periods with early discount benefits
- **Key Fields**: `academic_terms_id`, `name`, `application_start_date`, `application_end_date`
- **Special Fields**: `early_discount_percentage` (decimal)
- **Relationships**:
  - `belongsTo(AcademicTerms)` - Academic term
  - `hasMany(ApplicationForm)` - Applications during this period
  - `hasMany(Applicants)` - Applicants in this period

### 9. **Discount** (Custom Discounts)
- **Purpose**: Manages custom discount types
- **Key Fields**: `name`, `description`, `discount_type` (percentage/fixed), `discount_value`, `is_active`
- **Methods**: `calculateDiscount($amount)` - Calculates discount amount

### 10. **AcademicTerms** (Term Management)
- **Purpose**: Manages academic terms/semesters
- **Key Fields**: `year`, `semester`, `start_date`, `end_date`, `is_active`
- **Relationships**:
  - `hasMany(EnrollmentPeriod)` - Enrollment periods
  - `hasMany(Invoice)` - Invoices for this term

## Database Schema Structure

### Core Tables:
1. **students** - Student records
2. **school_fees** - Fee definitions
3. **invoices** - Main invoice records
4. **invoice_items** - Invoice line items
5. **invoice_payments** - Payment records
6. **payment_plans** - Installment plans
7. **payment_schedules** - Payment timeline
8. **enrollment_periods** - Enrollment periods with discounts
9. **discounts** - Custom discount types
10. **academic_terms** - Academic term management

### Key Relationships:
- Student → Invoice (1:many)
- Invoice → InvoiceItem (1:many)
- Invoice → PaymentPlan (1:1)
- PaymentPlan → PaymentSchedule (1:many)
- Invoice → InvoicePayment (1:many)
- PaymentSchedule → InvoicePayment (1:many)
- Student → EnrollmentPeriod (many:1)
- SchoolFee → InvoiceItem (1:many)

## Payment Flow and Business Logic

### 1. **Invoice Creation Process**
```
Student Promotion → InvoiceService.assignInvoiceAfterPromotion()
├── Get active academic term
├── Find student's program and grade level
├── Filter school fees by program/grade
├── Create invoice with 'unpaid' status
└── Create invoice items for each school fee
```

### 2. **Payment Plan Creation**
```
Student Confirms Payment Plan → PaymentPlanController.store()
├── Validate down payment and installment months
├── PaymentPlanService.createInstallmentPlan()
│   ├── Calculate payment plan using PaymentPlan::calculate()
│   ├── Create PaymentPlan record
│   ├── Create down payment schedule (installment_number = 0)
│   ├── Create monthly schedules (installment_number = 1-9)
│   ├── Send invoice email for down payment
│   └── Update invoice (has_payment_plan = true, payment_mode = 'installment')
└── Return success response
```

### 3. **Payment Recording Process**
```
Payment Recording → InvoicePaymentController.store()
├── PIN verification (security)
├── Calculate discounts:
│   ├── Early enrollment discount (from EnrollmentPeriod)
│   └── Custom discounts (from Discount model)
├── PaymentPlanService.recordPayment() or recordPaymentToSchedule()
│   ├── Create InvoicePayment record
│   ├── Update PaymentSchedule (if applicable)
│   ├── Recalculate payment plan (if down payment)
│   ├── Update invoice status
│   └── Send receipt email
└── Log activity and return response
```

### 4. **Discount System**
- **Early Enrollment Discount**: Applied based on `EnrollmentPeriod.early_discount_percentage`
- **Custom Discounts**: Applied from `Discount` model (percentage or fixed amount)
- **Discount Tracking**: Stored in `InvoicePayment` (original_amount, early_discount, custom_discounts, total_discount)

### 5. **Payment Plan Calculation Logic**
```php
// PaymentPlan::calculate() method
$discountedTotal = $totalAmount - $totalDiscount;
$remaining = $discountedTotal - $downPayment;
$monthlyAmount = round($remaining / $installmentMonths, 2);
$totalMonthly = $monthlyAmount * $installmentMonths;
$difference = $remaining - $totalMonthly;
```

## Controllers and Services

### **Controllers:**
1. **InvoiceController** - Invoice CRUD, PDF generation, item management
2. **PaymentPlanController** - Payment plan creation, calculation, updates
3. **InvoicePaymentController** - Payment recording with PIN security
4. **SchoolFeeController** - School fee management
5. **InvoiceItemController** - Invoice item management

### **Services:**
1. **PaymentPlanService** - Core payment plan logic and calculations
2. **InvoiceService** - Invoice assignment after student promotion
3. **AcademicTermService** - Academic term management

## Key Features

### **Payment Modes:**
1. **Installment** - Structured payment plan with down payment + monthly installments
2. **Full** - One-time payment of full amount

### **Security Features:**
- PIN verification for payment recording
- Rate limiting for failed PIN attempts
- Activity logging for all financial operations
- Soft delete for paid invoices

### **Notification System:**
- Invoice email notifications
- Receipt email notifications
- Real-time notifications via broadcast channels
- Private queued notifications

### **PDF Generation:**
- Invoice PDFs (one-time and schedule-based)
- Receipt PDFs (one-time and schedule-based)

## Business Rules

1. **Invoice Creation**: One invoice per student per academic term
2. **Payment Plan**: Can only be created if no payments exist
3. **Down Payment**: Must be paid before monthly installments
4. **Schedule Payments**: Must match exact remaining amount for monthly installments
5. **Discounts**: Applied at payment time, tracked separately
6. **Status Updates**: Automatic based on payment amounts
7. **Soft Delete**: Paid invoices are soft-deleted for record keeping

## Integration Points

- **Student Mobile Application**: Links to student mobile application for payment method selection
- **Academic Terms**: All financial records tied to academic terms
- **User System**: Notifications sent to student's user account
- **Activity Logging**: All operations logged for audit trail
- **Email System**: Automated invoice and receipt emails
- **PDF Generation**: Professional invoice and receipt documents

This payment system provides a comprehensive solution for managing school finances with flexibility for different payment preferences while maintaining detailed records and audit trails.
