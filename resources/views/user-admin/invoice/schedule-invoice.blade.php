<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $schedule->description }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            margin: 0;
            padding: 20px;
            line-height: 1.3;
        }
        
        .invoice-container {
            width: 100%;
            max-width: 750px;
        }
        
        /* Header */
        .header-table {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #199BCF;
            padding-bottom: 15px;
        }
        
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 8px;
        }
        
        .school-info {
            font-size: 10px;
            color: #666666;
            line-height: 1.2;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #1A3165;
            text-align: right;
            margin-bottom: 15px;
        }
        
        .invoice-details {
            font-size: 10px;
            color: #666666;
            text-align: right;
        }
        
        .invoice-details div {
            margin-bottom: 2px;
        }
        
        .badge {
            background-color: #199BCF;
            color: white;
            padding: 4px 8px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 8px;
            display: inline-block;
        }
        
        /* Main Content */
        .content-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 6px;
        }
        
        .section-content {
            font-size: 10px;
            color: #666666;
        }
        
        .section-content div {
            margin-bottom: 2px;
        }
        
        .student-name {
            font-weight: 600;
            color: #1A3165;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .items-table th {
            background-color: #1A3165;
            color: white;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1A3165;
        }
        
        .items-table th:nth-child(2),
        .items-table th:nth-child(3),
        .items-table th:nth-child(4) {
            text-align: center;
            width: 12%;
        }
        
        .items-table th:nth-child(1) {
            width: 64%;
        }
        
        .items-table td {
            padding: 6px;
            border: 1px solid #cccccc;
            vertical-align: top;
        }
        
        .items-table td:nth-child(2),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: center;
        }
        
        .item-title {
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 2px;
        }
        
        .item-subtitle {
            color: #666666;
            font-size: 9px;
        }
        
        /* Totals */
        .totals-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .totals-table td {
            padding: 3px 0;
            font-size: 10px;
        }
        
        .total-label {
            text-align: right;
            padding-right: 10px;
            color: #666666;
        }
        
        .total-value {
            text-align: right;
            color: #1A3165;
            font-weight: bold;
        }
        
        .total-final {
            border-top: 2px solid #199BCF;
            padding-top: 6px;
            font-size: 11px;
        }
        
        /* Payment Schedule */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        
        .schedule-table th {
            background-color: #f8f9fa;
            color: #1A3165;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #cccccc;
        }
        
        .schedule-table td {
            padding: 4px;
            text-align: center;
            border: 1px solid #cccccc;
        }
        
        .schedule-current {
            background-color: #199BCF;
            color: white;
            font-weight: bold;
        }
        
        /* Payment Terms */
        .terms-box {
            background-color: #fff8dc;
            border-left: 4px solid #C8A165;
            padding: 12px;
            margin-bottom: 20px;
        }
        
        .terms-title {
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 4px;
            font-size: 10px;
        }
        
        .terms-content {
            font-size: 9px;
            color: #666666;
            line-height: 1.2;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 9px;
            color: #999999;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #cccccc;
        }
        
        .footer p {
            margin-bottom: 2px;
        }
        
        /* Status Colors */
        .status-paid {
            color: #059669;
            font-weight: 600;
        }
        
        .status-partial {
            color: #D97706;
            font-weight: 600;
        }
        
        .status-pending {
            color: #666666;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div class="school-name">{{ $schoolSettings?->name ?? 'N/A' }}</div>
                    <div class="school-info">
                        {{ $schoolSettings?->address_line1 ?? 'N/A' }}<br>
                        @if($schoolSettings?->address_line2)
                            {{ $schoolSettings->address_line2 }}<br>
                        @endif
                        {{ $schoolSettings?->city ?? 'N/A' }}, {{ $schoolSettings?->province ?? 'Philippines' }}<br>
                        @if($schoolSettings?->zip)
                            {{ $schoolSettings->zip }}<br>
                        @endif
                        Phone: {{ $schoolSettings?->phone ?? 'N/A' }}<br>
                        Email: {{ $schoolSettings?->email ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-details">
                        <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                        <div><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('F d, Y') }}</div>
                        <div><strong>Due Date:</strong> {{ $schedule->due_date ? \Carbon\Carbon::parse($schedule->due_date)->format('F d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="badge">
                        @if($schedule->installment_number === 0)
                            Down Payment
                        @else
                            Installment {{ $schedule->installment_number }} of 9
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Main Content -->
        <table class="content-table">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <div class="section-title">BILL TO</div>
                    <div class="section-content">
                        <div class="student-name">{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</div>
                        <div>Student ID: {{ $student->lrn ?? 'N/A' }}</div>
                        <div>{{ $student->program->code ?? 'N/A' }}</div>
                        <div>Academic Term: {{ $academicTerm?->getFullNameAttribute() ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div class="section-title">PAYMENT DETAILS</div>
                    <div class="section-content">
                        <div><strong>Schedule:</strong> {{ $schedule->description }}</div>
                        <div><strong>Payment Type:</strong> 
                            @if($schedule->installment_number === 0)
                                Down Payment
                            @else
                                Monthly Installment
                            @endif
                        </div>
                        <div><strong>Status:</strong> 
                            <span class="status-{{ $schedule->status }}">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @if($schedule->installment_number === 0)
                    {{-- Down Payment --}}
                    <tr>
                        <td>
                            <div class="item-title">Down Payment - Academic Term {{ $academicTerm?->getFullNameAttribute() ?? 'N/A' }}</div>
                            <div class="item-subtitle">Initial payment for school fees</div>
                        </td>
                        <td>1</td>
                        <td>PHP {{ number_format($invoice->paymentPlan->down_payment_amount, 2) }}</td>
                        <td>PHP {{ number_format($schedule->amount_due, 2) }}</td>
                    </tr>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            <div class="item-title">{{ $item->fee->name }}</div>
                            <div class="item-subtitle">School fee component</div>
                        </td>
                        <td>1</td>
                        <td>PHP{{ number_format($item->amount, 2) }}</td>
                        <td>PHP{{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    {{-- Monthly Installment --}}
                    <tr>
                        <td>
                            <div class="item-title">{{ $schedule->description }} - Monthly Installment</div>
                            <div class="item-subtitle">Monthly payment for school fees</div>
                        </td>
                        <td>1</td>
                        <td>PHP {{ number_format($schedule->amount_due, 2) }}</td>
                        <td>PHP {{ number_format($schedule->amount_due, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Invoice Items Breakdown (Simple) -->
        @if($schedule->installment_number > 0)
        <div style="margin-bottom: 20px; font-size: 9px; color: #666666;">
            <div style="margin-bottom: 5px;"><strong>Payment covers:</strong></div>
            @foreach($invoice->items as $item)
            <div style="margin-left: 10px; margin-bottom: 2px;">
                • {{ $item->fee->name }} (PHP {{ number_format($invoice->paymentPlan->monthly_amount, 2) }} monthly)
            </div>
            @endforeach
        </div>
        @endif

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td class="total-label" style="width: 85%;">Subtotal:</td>
                <td class="total-value" style="width: 15%;">PHP {{ number_format($schedule->amount_due, 2) }}</td>
            </tr>
            @if($schedule->amount_paid > 0)
            <tr>
                <td class="total-label">Amount Paid:</td>
                <td class="total-value">PHP{{ number_format($schedule->amount_paid, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="total-label total-final">AMOUNT DUE:</td>
                <td class="total-value total-final">PHP{{ number_format($schedule->amount_due - $schedule->amount_paid, 2) }}</td>
            </tr>
        </table>

        <!-- Payment Schedule Overview -->
        @if($invoice->has_payment_plan)
        <div style="margin-bottom: 20px;">
            <div class="section-title">Payment Schedule Overview</div>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Schedule</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 20%;">Schedule</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 20%;">Schedule</th>
                        <th style="width: 20%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="{{ $schedule->installment_number === 0 ? 'schedule-current' : '' }}">Down Payment</td>
                        <td class="{{ $schedule->installment_number === 0 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentSchedules()->where('installment_number', 0)->first()->amount_due ?? 0, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 1 ? 'schedule-current' : '' }}">Month 1</td>
                        <td class="{{ $schedule->installment_number === 1 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->first_month_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 2 ? 'schedule-current' : '' }}">Month 2</td>
                        <td class="{{ $schedule->installment_number === 2 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 3 ? 'schedule-current' : '' }}">Month 3</td>
                        <td class="{{ $schedule->installment_number === 3 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 4 ? 'schedule-current' : '' }}">Month 4</td>
                        <td class="{{ $schedule->installment_number === 4 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 5 ? 'schedule-current' : '' }}">Month 5</td>
                        <td class="{{ $schedule->installment_number === 5 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 6 ? 'schedule-current' : '' }}">Month 6</td>
                        <td class="{{ $schedule->installment_number === 6 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 7 ? 'schedule-current' : '' }}">Month 7</td>
                        <td class="{{ $schedule->installment_number === 7 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 8 ? 'schedule-current' : '' }}">Month 8</td>
                        <td class="{{ $schedule->installment_number === 8 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 9 ? 'schedule-current' : '' }}">Month 9</td>
                        <td class="{{ $schedule->installment_number === 9 ? 'schedule-current' : '' }}">PHP{{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <!-- Payment Terms -->
        <div class="terms-box">
            <div class="terms-title">Payment Terms:</div>
            <div class="terms-content">
                @if($schedule->installment_number === 0)
                    This is the down payment for the academic term. Payment is due upon enrollment. 
                    The remaining balance will be divided into 9 monthly installments.
                @else
                    This is installment {{ $schedule->installment_number }} of a 9-month payment plan. 
                    Payment is due on the 10th day of each month Late payments may incur additional charges.
                @endif
                Failure to make payments as scheduled may result in a temporary hold on enrollment.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an official invoice from Dreamy School. Please keep this for your records.</p>
            <p>For inquiries, please contact the school administration.</p>
            <p>Generated on {{ \Carbon\Carbon::now()->format('F d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>