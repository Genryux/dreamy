<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
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
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .totals-table td {
            padding: 4px 6px;
            border: 1px solid #cccccc;
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
        
        /* Payment Info */
        .payment-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .payment-title {
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 6px;
            font-size: 11px;
        }
        
        .payment-details {
            color: #666666;
        }
        
        .payment-details div {
            margin-bottom: 2px;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            font-size: 9px;
            color: #666666;
            text-align: center;
        }
        
        .footer div {
            margin-bottom: 3px;
        }
        
        .footer-contact {
            color: #199BCF;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
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
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-details">
                        <div><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</div>
                        <div><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</div>
                        <div><strong>Academic Term:</strong> {{ $academicTerm?->getFullNameAttribute() ?? 'N/A' }}</div>
                    </div>
                    <div class="badge">ONE-TIME PAYMENT</div>
                </td>
            </tr>
        </table>

        <!-- Student Information -->
        <table class="content-table">
            <tr>
                <td style="vertical-align: top; width: 50%;">
                    <div class="section-title">STUDENT INFORMATION</div>
                    <div class="section-content">
                        <div><strong>Student ID:</strong> {{ $student->lrn ?? 'N/A' }}</div>
                        <div><strong>Name:</strong> <span class="student-name">{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</span></div>
                        <div><strong>Program:</strong> {{ $student->program ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="vertical-align: top; width: 50%;">
                    <div class="section-title">PAYMENT INFORMATION</div>
                    <div class="section-content">
                        <div><strong>Year Level:</strong> {{ $student->grade_level ?? 'N/A' }}</div>
                        <div><strong>Payment Mode:</strong> One-Time Payment</div>
                        <div><strong>Status:</strong> 
                            @if($invoice->balance == 0)
                                Paid in Full
                            @elseif($invoice->paid_amount > 0)
                                Partial Payment
                            @else
                                Pending Payment
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="payment-title">PAYMENT FOR: COMPLETE INVOICE PAYMENT</div>
            <div class="payment-details">
                <div>This invoice covers the complete payment for all school fees and charges.</div>
                <div>Payment is due in full as selected by the student.</div>
            </div>
        </div>

        <!-- Invoice Items -->
        <table class="items-table">
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

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td>PHP {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td>PHP {{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Remaining Balance:</td>
                <td>PHP {{ number_format($invoice->balance, 2) }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div>This invoice is for one-time payment of the complete amount.</div>
            <div>Please ensure payment is made in full as selected by the student.</div>
            <div class="footer-contact">
                For inquiries: {{ $schoolSettings?->phone ?? 'N/A' }} | {{ $schoolSettings?->email ?? 'N/A' }}
            </div>
        </div>
    </div>
</body>
</html>