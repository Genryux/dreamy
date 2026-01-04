<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officially Enrolled Students</title>
    <style>
        @page {
            margin: 40px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #1f2937;
            font-size: 9px;
            line-height: 1.4;
            padding: 20px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #1A3165;
            padding-bottom: 10px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 50px;
            padding-right: 10px;
        }

        .logo {
            width: 45px;
            height: 45px;
        }

        .school-info-cell {
            display: table-cell;
            vertical-align: middle;
        }

        .school-name {
            font-size: 14px;
            font-weight: bold;
            color: #1A3165;
            margin-bottom: 2px;
        }

        .school-address {
            font-size: 8px;
            color: #6b7280;
            line-height: 1.3;
        }

        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #1A3165;
        }

        .doc-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Meta Info */
        .meta-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }

        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-label {
            display: table-cell;
            font-weight: bold;
            width: 120px;
            color: #374151;
        }

        .meta-value {
            display: table-cell;
            color: #1f2937;
        }

        /* Table */
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .students-table thead th {
            background-color: #1A3165;
            color: white;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #1A3165;
        }

        .students-table thead th:first-child {
            text-align: center;
            width: 30px;
        }

        .students-table tbody td {
            padding: 6px;
            border: 1px solid #d1d5db;
            font-size: 8px;
            vertical-align: middle;
        }

        .students-table tbody td:first-child {
            text-align: center;
            font-weight: bold;
            color: #6b7280;
        }

        .students-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .students-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-enrolled {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-withdrawn {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 8px;
            color: #6b7280;
        }

        .footer-row {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
        }

        /* Summary */
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 5px;
        }

        .summary-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .summary-text {
            color: #1e40af;
        }

        /* No data */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="header-content">
                @if($schoolSetting && $schoolSetting->logo_path)
                <div class="logo-cell">
                    <img src="{{ public_path('storage/' . $schoolSetting->logo_path) }}" alt="School Logo" class="logo">
                </div>
                @endif
                <div class="school-info-cell">
                    <div class="school-name">{{ $schoolSetting->name ?? 'School Name' }}</div>
                    <div class="school-address">
                        @if($schoolSetting)
                            {{ $schoolSetting->address_line1 ?? '' }}
                            @if($schoolSetting->city || $schoolSetting->province)
                                {{ $schoolSetting->city ?? '' }}{{ $schoolSetting->city && $schoolSetting->province ? ', ' : '' }}{{ $schoolSetting->province ?? '' }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Officially Enrolled Students</div>
            <div class="doc-subtitle">Academic Year {{ $academicTerm->year ?? 'N/A' }} - {{ $academicTerm->semester ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <div class="meta-row">
            <span class="meta-label">Academic Term:</span>
            <span class="meta-value">{{ $academicTerm->year ?? 'N/A' }} - {{ $academicTerm->semester ?? 'N/A' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Filters Applied:</span>
            <span class="meta-value">{{ $filterDescription }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Export Date:</span>
            <span class="meta-value">{{ $exportDate }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Total Students:</span>
            <span class="meta-value">{{ $students->count() }}</span>
        </div>
    </div>

    <!-- Students Table -->
    @if($students->count() > 0)
    <table class="students-table">
        <thead>
            <tr>
                <th>#</th>
                <th>LRN</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Grade Level</th>
                <th>Program</th>
                <th>Section</th>
                <th>Contact Number</th>
                <th>Email Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $enrollment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $enrollment->student->lrn ?? '-' }}</td>
                <td>{{ $enrollment->student->user->last_name ?? '-' }}</td>
                <td>{{ $enrollment->student->user->first_name ?? '-' }}</td>
                <td>{{ $enrollment->student->grade_level ?? '-' }}</td>
                <td>{{ $enrollment->program->code ?? $enrollment->student->program->code ?? '-' }}</td>
                <td>{{ $enrollment->section->name ?? '-' }}</td>
                <td>{{ $enrollment->student->record->contact_number ?? '-' }}</td>
                <td>{{ $enrollment->student->user->email ?? '-' }}</td>
                <td>
                    @php
                        $statusClass = match($enrollment->status) {
                            'enrolled' => 'status-enrolled',
                            'pending_confirmation' => 'status-pending',
                            'withdrawn' => 'status-withdrawn',
                            default => 'status-pending'
                        };
                        $statusText = match($enrollment->status) {
                            'enrolled' => 'Enrolled',
                            'pending_confirmation' => 'Pending',
                            'withdrawn' => 'Withdrawn',
                            default => ucfirst($enrollment->status ?? 'Unknown')
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-title">Export Summary</div>
        <div class="summary-text">
            This document contains {{ $students->count() }} student record(s) for the {{ $academicTerm->year ?? 'N/A' }} - {{ $academicTerm->semester ?? 'N/A' }} academic term.
            @if($filterDescription !== 'All Enrolled Students')
                Filtered by: {{ $filterDescription }}.
            @endif
        </div>
    </div>
    @else
    <div class="no-data">
        <p>No students found matching the specified criteria.</p>
        <p style="margin-top: 10px;">Filters: {{ $filterDescription }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <span class="footer-left">Generated on {{ $exportDate }} by {{ auth()->user()->first_name ?? 'System' }} {{ auth()->user()->last_name ?? '' }}</span>
            <span class="footer-right">{{ $schoolSetting->name ?? 'School Management System' }} | Page 1</span>
        </div>
    </div>
</body>

</html>
