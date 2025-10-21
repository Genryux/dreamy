<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $invoice->invoice_number }}</title>
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
        
        .badge-onetime {
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
        
        /* Payment History Table */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        
        .payments-table th {
            background-color: #1A3165;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .payments-table th:nth-child(2),
        .payments-table th:nth-child(3),
        .payments-table th:nth-child(4) {
            text-align: center;
            width: 20%;
        }
        
        .payments-table th:nth-child(1) {
            width: 40%;
        }
        
        .payments-table td {
            padding: 8px;
            border-bottom: 1px solid #cccccc;
        }
        
        .payments-table td:nth-child(2),
        .payments-table td:nth-child(3),
        .payments-table td:nth-child(4) {
            text-align: center;
        }
        
        /* Totals */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        
        .totals-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #cccccc;
        }
        
        .totals-table td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #1A3165;
            width: 70%;
        }
        
        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #333333;
            width: 30%;
        }
        
        .grand-total td:first-child {
            background-color: #1A3165;
            color: white;
            font-size: 11px;
        }
        
        .grand-total td:last-child {
            background-color: #1A3165;
            color: white;
            font-size: 11px;
        }
        
        /* Status Section */
        .status-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
            border-radius: 6px;
        }
        
        .status-title {
            font-size: 14px;
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 8px;
        }
        
        .status-message {
            font-size: 11px;
            color: #666666;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 9px;
            color: #666666;
            text-align: center;
        }
        
        .footer div {
            margin-bottom: 4px;
        }
        
        .footer-contact {
            color: #199BCF;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="vertical-align: top;">
                    <div class="school-name">{{ $schoolSettings?->name ?? 'N/A' }}</div>
                    <div class="school-info">
                        {{ $schoolSettings?->address_line1 ?? 'N/A' }}<br>
                        {{ $schoolSettings?->address_line2 ?? 'N/A' }}<br>
                        {{ $schoolSettings?->city ?? 'Philippines' }}<br>
                        Phone: {{ $schoolSettings?->phone ?? 'N/A' }} | 
                        Email: {{ $schoolSettings?->email ?? 'N/A' }}
                    </div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div class="receipt-title">RECEIPT</div>
                    <div class="receipt-details">
                        <div><strong>Receipt No:</strong> {{ $invoice->invoice_number }}</div>
                        <div><strong>Date:</strong> {{ now()->format('M d, Y') }}</div>
                        <div><strong>Academic Term:</strong> {{ $academicTerm?->getFullNameAttribute() ?? 'N/A' }}</div>
                    </div>
                    <div class="badge badge-onetime">ONE-TIME PAYMENT</div>
                </td>
            </tr>
        </table>

        <!-- Payment Confirmation -->
        <div class="payment-confirmation">
            <div class="confirmation-title">PAYMENT RECEIVED</div>
            <div class="confirmation-amount">PHP {{ number_format($invoice->paid_amount, 2) }}</div>
            <div class="confirmation-message">
                @if($invoice->balance == 0)
                    Complete payment for invoice #{{ $invoice->invoice_number }}
                @elseif($invoice->paid_amount > 0)
                    Partial payment for invoice #{{ $invoice->invoice_number }}
                @else
                    No payments received yet
                @endif
            </div>
        </div>

        <!-- Student Information -->
        <table class="details-table">
            <tr>
                <td>
                    <div class="section-title">Student Information</div>
                    <div class="section-content">
                        <div><strong>Student ID:</strong> {{ $student->lrn ?? 'N/A' }}</div>
                        <div><strong>Name:</strong> <span class="student-name">{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</span></div>
                        <div><strong>Program:</strong> {{ $student->program->code ?? 'N/A' }}</div>
                    </div>
                </td>
                <td>
                    <div class="section-title">Payment Details</div>
                    <div class="section-content">
                        <div><strong>Year Level:</strong> {{ $student->grade_level ?? 'N/A' }}</div>
                        <div><strong>Payment Mode:</strong> One-Time Payment</div>
                        <div><strong>Invoice Total:</strong> PHP {{ number_format($invoice->total_amount, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payment Breakdown -->
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="item-title">{{ $item->fee?->name ?? 'School Fee' }}</div>
                        <div class="item-subtitle">Complete payment for {{ $item->fee?->name ?? 'school fee' }}</div>
                    </td>
                    <td>1</td>
                    <td>PHP {{ number_format($item->amount, 2) }}</td>
                    <td>PHP {{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Reference</th>
                    <th>Method</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                    <td>{{ $payment->reference_no ?? '-' }}</td>
                    <td>{{ ucfirst($payment->method ?? 'Cash') }}</td>
                    <td>PHP {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td>Total Amount:</td>
                <td>PHP {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @if($invoice->payments->sum('total_discount') > 0)
            <tr>
                <td>Discount Applied:</td>
                <td style="color: #dc2626;">-PHP {{ number_format($invoice->payments->sum('total_discount'), 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Amount Paid:</td>
                <td>PHP {{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Remaining Balance:</td>
                <td>PHP {{ number_format($invoice->balance, 2) }}</td>
            </tr>
        </table>

        <!-- Status Section -->
        <div class="status-section">
            @if($invoice->balance == 0)
                <div class="status-title">PAYMENT COMPLETE</div>
                <div class="status-message">This invoice has been paid in full. Thank you for your payment!</div>
            @elseif($invoice->paid_amount > 0)
                <div class="status-title">PARTIAL PAYMENT</div>
                <div class="status-message">Partial payment received. Remaining balance: PHP {{ number_format($invoice->balance, 2) }}</div>
            @else
                <div class="status-title">PENDING PAYMENT</div>
                <div class="status-message">No payments received yet. Full amount due: PHP {{ number_format($invoice->total_amount, 2) }}</div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>This receipt confirms payment for the complete invoice amount.</div>
            <div>Please keep this receipt for your records.</div>
            <div class="footer-contact">
                For inquiries: {{ $schoolSettings?->phone ?? 'N/A' }} | {{ $schoolSettings?->email ?? 'N/A' }}
            </div>
        </div>
    </div>
</body>
</html>