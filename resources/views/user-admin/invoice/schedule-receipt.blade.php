<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $schedule->description }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            margin: 0;
            padding: 40px;
            line-height: 1.4;
            background-color: #ffffff;
        }
        
        .receipt-container {
            width: 100%;
            max-width: 750px;
        }
        
        /* Header */
        .header-table {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #199BCF;
            padding-bottom: 25px;
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
        
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            color: #1A3165;
            text-align: right;
            margin-bottom: 15px;
        }
        
        .receipt-details {
            font-size: 10px;
            color: #666666;
            text-align: right;
        }
        
        .receipt-details div {
            margin-bottom: 2px;
        }
        
        .badge {
            padding: 4px 8px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 8px;
            display: inline-block;
            border-radius: 4px;
        }
        
        .badge-paid {
            background-color: #199BCF;
            color: white;
        }
        
        .badge-installment {
            background-color: #C8A165;
            color: white;
        }
        
        /* Payment Confirmation Section */
        .payment-confirmation {
            background-color: #199BCF;
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 35px;
            border-radius: 6px;
        }
        
        .confirmation-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .confirmation-amount {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .confirmation-message {
            font-size: 11px;
            opacity: 0.9;
        }
        
        /* Details Section */
        .details-table {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .details-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 30px;
        }
        
        .details-table td:last-child {
            padding-right: 0;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 6px;
            text-transform: uppercase;
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
        
        /* Payment Breakdown Table */
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        
        .breakdown-table th {
            background-color: #199BCF;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .breakdown-table th:nth-child(2),
        .breakdown-table th:nth-child(3),
        .breakdown-table th:nth-child(4) {
            text-align: center;
            width: 15%;
        }
        
        .breakdown-table th:nth-child(1) {
            width: 55%;
        }
        
        .breakdown-table td {
            padding: 8px;
            border-bottom: 1px solid #cccccc;
        }
        
        .breakdown-table td:nth-child(2),
        .breakdown-table td:nth-child(3),
        .breakdown-table td:nth-child(4) {
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
        
        /* Summary Section */
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .summary-table td {
            padding: 3px 0;
            font-size: 10px;
        }
        
        .summary-label {
            text-align: right;
            padding-right: 10px;
            color: #666666;
        }
        
        .summary-value {
            text-align: right;
            color: #1A3165;
            font-weight: bold;
        }
        
        .summary-final {
            border-top: 2px solid #199BCF;
            padding-top: 6px;
            font-size: 11px;
        }
        
        /* Payment Schedule */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
            padding: 18px;
            margin-bottom: 30px;
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
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #cccccc;
        }
        
        .footer p {
            margin-bottom: 2px;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(25, 155, 207, 0.1);
            z-index: -1;
            pointer-events: none;
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
    <div class="receipt-container">
        <!-- Watermark -->
        <div class="watermark">PAID</div>
        
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
                    <div class="receipt-title">RECEIPT</div>
                    <div class="receipt-details">
                        <div><strong>Receipt #:</strong> RCP-{{ $invoice->invoice_number }}</div>
                        <div><strong>Payment Date:</strong> {{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
                        <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                    </div>
                    <div class="badge badge-paid">PAID</div>
                    <div class="badge badge-installment">
                        @if($schedule->installment_number === 0)
                            Down Payment
                        @else
                            Installment {{ $schedule->installment_number }} of 9
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payment Confirmation -->
        <div class="payment-confirmation">
            <div class="confirmation-title">Payment Received Successfully</div>
            <div class="confirmation-amount">PHP {{ number_format($schedule->amount_paid, 2) }}</div>
            <div class="confirmation-message">
                Thank you for your payment. This receipt confirms your 
                @if($schedule->installment_number === 0)
                    down payment.
                @else
                    installment {{ $schedule->installment_number }} payment.
                @endif
            </div>
        </div>

        <!-- Details Section -->
        <table class="details-table">
            <tr>
                <td>
                    <div class="section-title">Received From</div>
                    <div class="section-content">
                        <div class="student-name">{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</div>
                        <div>Student ID: {{ $student->lrn ?? 'N/A' }}</div>
                        <div>{{ $student->program->code ?? 'N/A' }}</div>
                        <div>Academic Term: {{ $academicTerm?->getFullNameAttribute() ?? 'N/A' }}</div>
                    </div>
                </td>
                <td>
                    <div class="section-title">Payment Information</div>
                    <div class="section-content">
                        <div><strong>Payment Method:</strong> {{ $payments->first()->method ?? 'Cash' }}</div>
                        <div><strong>Transaction ID:</strong> {{ $payments->first()->reference_no ?? 'N/A' }}</div>
                        <div><strong>Account:</strong> .... {{ substr($payments->first()->reference_no ?? '0000', -4) }}</div>
                        <div><strong>Contract #:</strong> {{ $invoice->invoice_number }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payment Breakdown Table -->
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total Value</th>
                    <th>Installment</th>
                    <th>Amount Paid</th>
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
                        <td>PHP {{ number_format($invoice->paymentPlan->down_payment_amount, 2) }}</td>
                        <td>1/1</td>
                        <td>PHP {{ number_format($schedule->amount_paid, 2) }}</td>
                    </tr>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            <div class="item-title">{{ $item->fee->name }}</div>
                            <div class="item-subtitle">School fee component</div>
                        </td>
                        <td>PHP {{ number_format($item->amount, 2) }}</td>
                        <td>1/1</td>
                        <td>PHP 0.00</td>
                    </tr>
                    @endforeach
                @else
                    {{-- Monthly Installment --}}
                    <tr>
                        <td>
                            <div class="item-title">{{ $schedule->description }} - Monthly Installment</div>
                            <div class="item-subtitle">Monthly payment for school fees</div>
                        </td>
                        <td>PHP {{ number_format($invoice->total_amount, 2) }}</td>
                        <td>{{ $schedule->installment_number }}/9</td>
                        <td>PHP {{ number_format($schedule->amount_paid, 2) }}</td>
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

        <!-- Summary Section -->
        <table class="summary-table">
            <tr>
                <td class="summary-label" style="width: 70%;">Total Contract Value:</td>
                <td class="summary-value" style="width: 30%;">PHP {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Paid to Date:</td>
                <td class="summary-value">PHP {{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Remaining Balance:</td>
                <td class="summary-value">PHP {{ number_format($invoice->balance, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label summary-final">PAYMENT RECEIVED:</td>
                <td class="summary-value summary-final">PHP {{ number_format($schedule->amount_paid, 2) }}</td>
            </tr>
        </table>

        <!-- Payment Schedule Overview -->
        @if($invoice->has_payment_plan)
        <div style="margin-bottom: 20px;">
            <div class="section-title">Payment Schedule Status</div>
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
                        <td class="{{ $schedule->installment_number === 0 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentSchedules()->where('installment_number', 0)->first()->amount_due ?? 0, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 1 ? 'schedule-current' : '' }}">Month 1</td>
                        <td class="{{ $schedule->installment_number === 1 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->first_month_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 2 ? 'schedule-current' : '' }}">Month 2</td>
                        <td class="{{ $schedule->installment_number === 2 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 3 ? 'schedule-current' : '' }}">Month 3</td>
                        <td class="{{ $schedule->installment_number === 3 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 4 ? 'schedule-current' : '' }}">Month 4</td>
                        <td class="{{ $schedule->installment_number === 4 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 5 ? 'schedule-current' : '' }}">Month 5</td>
                        <td class="{{ $schedule->installment_number === 5 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 6 ? 'schedule-current' : '' }}">Month 6</td>
                        <td class="{{ $schedule->installment_number === 6 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 7 ? 'schedule-current' : '' }}">Month 7</td>
                        <td class="{{ $schedule->installment_number === 7 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                        <td class="{{ $schedule->installment_number === 8 ? 'schedule-current' : '' }}">Month 8</td>
                        <td class="{{ $schedule->installment_number === 8 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $schedule->installment_number === 9 ? 'schedule-current' : '' }}">Month 9</td>
                        <td class="{{ $schedule->installment_number === 9 ? 'schedule-current' : '' }}">PHP {{ number_format($invoice->paymentPlan->monthly_amount, 0) }}</td>
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
                    This receipt confirms your down payment for the academic term. 
                    The remaining balance will be divided into 9 monthly installments.
                @else
                    This receipt confirms installment {{ $schedule->installment_number }} of a 9-month payment plan. 
                    Your next installment is due on {{ $schedule->due_date ? \Carbon\Carbon::parse($schedule->due_date)->addMonth()->format('F d, Y') : 'N/A' }}.
                @endif
                Thank you for your timely payment.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an official receipt from {{ $schoolSettings?->name ?? 'Dreamy School' }}. Please keep this for your records.</p>
            <p>For inquiries, please contact the school administration.</p>
            <p>Receipt generated on {{ \Carbon\Carbon::now()->format('F d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>