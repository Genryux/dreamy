<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Sheet</title>
    <style>
        @page {
            margin: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #1f2937;
            font-size: 10px;
            line-height: 1.4;
            padding: 30px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
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
            width: 50%;
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
            padding-top: 8px
        }

        .logo {
            width: 50px;
            height: 50px;
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

        .sis-title {
            font-size: 22px;
            font-weight: bold;
            color: #C8A165;
        }

        /* Section Headers */
        .section-header {
            color: #1A3165;
            padding: 8px 0px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 0;
        }

        /* Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 0.5px solid black;
            border-top: none;
        }

        .info-table td {
            padding: 5px 8px;
            border: 0.5px solid black;
            vertical-align: middle;
        }

        .label-cell {
            background-color: #1A3165;
            color: white;
            font-weight: 500;
            width: 25%;
            font-size: 9px;
        }

        .value-cell {
            background-color: #ffffff;
            text-align: center;
            font-size: 10px;
        }

        /* Student Info Section */
        .student-info-container {
            display: table;
            width: 100%;
            border: 0.5px solid black;
        }

        .photo-cell {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            text-align: center;
            padding: 10px;
            border-right: 0.5px solid black;
        }

        .photo-box {
            width: 110px;
            height: 90px;
            border: 0.5px solid black;
            background-color: #f3f4f6;
            margin: 0 auto;
            text-align: center;
            line-height: 85px;
        }

        .photo-box img {
            width: 110px;
            height: 90px;
            object-fit: cover;
        }

        .student-details-cell {
            display: table-cell;
            vertical-align: top;
        }

        .student-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-details-table td {
            padding: 4px 8px;
            border: 0.5px solid black;
            border-top: none;
            border-left: none;
            vertical-align: middle;
        }

        .student-details-table tr:first-child td {
            border-top: none;
        }

        .student-details-table .label-cell {
            width: 20%;
        }

        .name-value {
            text-transform: uppercase;
            font-weight: 600;
        }

        .name-sublabel {
            font-size: 7px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* Highlighted cells */
        .highlight-label {
            background-color: #1A3165;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }

        .highlight-value {
            color: #1A3165;
            font-weight: bold;
            text-align: center;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(290deg);
            font-size: 120px;
            color: rgba(0,0,0,0.09);
            font-weight: 800;
            letter-spacing: 8px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            text-transform: uppercase;
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* Make table backgrounds transparent so watermark shows through
           but preserve dark-blue label backgrounds */
        .info-table,
        .student-details-table {
            background-color: transparent;
        }

        .info-table td,
        .student-details-table td,
        .value-cell {
            background-color: transparent;
        }

        /* Photo box shouldn't obscure watermark */
        .photo-box {
            background-color: transparent;
        }

        /* Ensure dark-blue label cells remain opaque so they are not made transparent */
        .info-table td.label-cell,
        .student-details-table td.label-cell,
        .label-cell,
        .highlight-label {
            background-color: #1A3165 !important;
            color: #ffffff !important;
        }

        /* Generated timestamp box */
        .generated-info {
            position: fixed;
            top: 12px;
            left: 12px;
            font-size: 9px;
            color: #374151;
            background: rgba(255,255,255,0.85);
            padding: 4px 8px;
            border-radius: 4px;
            z-index: 3;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
    </style>
    </head>

<body>
    @php
        $schoolName = $school->name ?? 'Dreamy School Philippines';
        $addressParts = array_filter([$school->address_line1 ?? null, $school->address_line2 ?? null]);
        $cityParts = array_filter([$school->city ?? null, $school->province ?? null, $school->country ?? null]);
        $fullAddress = implode(' ', $addressParts) . (count($cityParts) ? ', ' . implode(', ', $cityParts) : '');
        $contactInfo = implode(' | ', array_filter([$school->phone ?? null, $school->email ?? null]));
        $logoSrc = $school->logo_path ?? public_path('images/Dreamy_logo.png');
        
        // Get student profile picture or fallback to default
        $studentPhotoPath = null;
        if ($profilePicture && $profilePicture->file_path) {
            $studentPhotoPath = public_path('storage/' . $profilePicture->file_path);
        } else {
            $studentPhotoPath = public_path('images/business-man.png');
        }

        // Student data
        $user = $student->user;
        $record = $studentRecord;
        $program = $student->program;

        // Calculate age from birthdate
        $age = $record && $record->birthdate ? \Carbon\Carbon::parse($record->birthdate)->age : 'N/A';
        $birthdate =
            $record && $record->birthdate ? \Carbon\Carbon::parse($record->birthdate)->format('M. d, Y') : 'N/A';

        // Format current address
        $currentAddress = '';
        if ($record) {
            $addrParts = array_filter([
                $record->house_no,
                $record->street,
                $record->barangay ? 'Barangay ' . $record->barangay : null,
                $record->city,
            ]);
            $currentAddress = implode(', ', $addrParts) ?: $record->current_address ?? 'N/A';
        }

        $permanentAddress = $record->permanent_address ?? $currentAddress;

        // Semester display
        $semesterDisplay = 'N/A';
        if ($acadTerm) {
            $semesterDisplay = $acadTerm->semester ?? 'N/A';
        } elseif ($record && $record->semester_applied) {
            $semesterDisplay = $record->semester_applied;
        }
    @endphp

    @php
        $status = $student->status ?? '';
        $wmColor = 'rgba(31,41,55,0.20)';
        if (strtolower($status) === 'dropped') {
            $wmColor = 'rgba(220,38,38,0.20)';
        } elseif (strtolower($status) === 'graduated') {
            $wmColor = 'rgba(16,185,129,0.20)';
        } elseif (in_array(strtolower($status), ['officially enrolled', 'enrolled'])) {
            $wmColor = 'rgba(67,176,241,0.20)';
            $status = 'Enrolled';
        }
    @endphp

    @if(!empty($status))
        <div class="watermark" style="color: {{ $wmColor }}">{{ strtoupper($status) }}</div>
    @endif

    @php
        $generatedAt = now();
        $generatedBy = optional(auth()->user())->first_name ? optional(auth()->user())->first_name . ' ' . optional(auth()->user())->last_name : (optional(auth()->user())->email ?? 'System');
    @endphp

    <div class="generated-info">Generated on: {{ $generatedAt->format('M. d, Y H:i') }} by: {{ $generatedBy }}</div>

    <div class="page-content">

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="header-content">
                <div class="logo-cell">
                    <img src="{{ $logoSrc }}" alt="Logo" class="logo">
                </div>
                <div class="school-info-cell">
                    <div class="school-name">{{ $schoolName }}</div>
                    <div class="school-address">
                        {{ $fullAddress }}<br>
                        {{ $contactInfo }}
                    </div>
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="sis-title">Student Information Sheet</div>
        </div>
    </div>

    <!-- STUDENT INFORMATION -->
    <div class="section-header">STUDENT INFORMATION</div>
    <div class="student-info-container">
        <div class="photo-cell">
            <div class="photo-box">
                @if ($studentPhotoPath && file_exists($studentPhotoPath))
                    <img src="{{ $studentPhotoPath }}" alt="Student Photo">
                @else
                    <span style="color: #9ca3af; font-size: 8px;">No Photo</span>
                @endif
            </div>
        </div>
        <div class="student-details-cell">
            <table class="student-details-table">
                <tr>
                    <td class="label-cell">Complete Name:</td>
                    <td class="value-cell name-value" style="border-right: none;" colspan="5">{{ strtoupper($user->last_name ?? '') }},
                        {{ strtoupper($user->first_name ?? '') }}{{ $record && $record->middle_name ? ' ' . strtoupper($record->middle_name) : '' }}{{ $record && $record->extension_name ? ' ' . strtoupper($record->extension_name) : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="value-cell" colspan="2" style="width: 25%;">
                        <div class="name-value">{{ strtoupper($user->last_name ?? 'N/A') }}</div>
                        <div class="name-sublabel">SURNAME</div>
                    </td>
                    <td class="value-cell" colspan="2" style="width: 25%;">
                        <div class="name-value">{{ strtoupper($user->first_name ?? 'N/A') }}</div>
                        <div class="name-sublabel">FIRST NAME</div>
                    </td>
                    <td class="value-cell" colspan="2" style="width: 30%; border-right: none;">
                        <div class="name-value">
                            {{ $record && $record->middle_name ? strtoupper($record->middle_name) : '' }}
                        </div>
                        <div class="name-sublabel">MIDDLE NAME</div>
                    </td>
                </tr>
                <tr>
                    <td class="highlight-label">BIRTHDATE:</td>
                    <td class="highlight-value" style="font-size: 9px;">{{ strtoupper($birthdate) }}</td>
                    <td class="highlight-label">AGE:</td>
                    <td class="highlight-value">{{ $age }}</td>
                    <td class="highlight-label">GENDER:</td>
                    <td class="highlight-value" style="border-right: none;">{{ strtoupper($record->gender ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="highlight-label">PLACE OF BIRTH:</td>
                    <td class="value-cell" style="border-bottom: none; border-right: none;" colspan="2">{{ $record->place_of_birth ?? 'N/A' }}</td>
                    <td class="highlight-label">MOTHER TONGUE:</td>
                    <td class="value-cell" style="border-bottom: none; border-right: none;" colspan="2">{{ $record->mother_tongue ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- CONTACT INFORMATION -->
    <div class="section-header">CONTACT INFORMATION</div>
    <table class="info-table">
        <tr>
            <td class="label-cell">Email:</td>
            <td class="value-cell">{{ $user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Contact Number:</td>
            <td class="value-cell">{{ $record->contact_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Current Address:</td>
            <td class="value-cell">{{ $currentAddress }}</td>
        </tr>
        <tr>
            <td class="label-cell">Permanent Address:</td>
            <td class="value-cell">{{ $permanentAddress }}</td>
        </tr>
    </table>

    <!-- PARENT/GUARDIAN INFORMATION -->
    <div class="section-header">PARENT/GUARDIAN INFORMATION</div>
    <table class="info-table">
        <tr>
            <td class="label-cell">Father's Name:</td>
            <td class="value-cell">{{ $record->father_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Father's Contact Number:</td>
            <td class="value-cell">{{ $record->father_contact_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Mother's Name:</td>
            <td class="value-cell">{{ $record->mother_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Mother's Contact Number:</td>
            <td class="value-cell">{{ $record->mother_contact_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Guardian's Name:</td>
            <td class="value-cell">{{ $record->guardian_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Guardian's Contact Number:</td>
            <td class="value-cell">{{ $record->guardian_contact_number ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- ACADEMIC INFORMATION -->
    <div class="section-header">ACADEMIC INFORMATION</div>
    <table class="info-table">
        <tr>
            <td class="label-cell">LRN:</td>
            <td class="value-cell">{{ $student->lrn ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Grade/Year Level:</td>
            <td class="value-cell">{{ $student->grade_level ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Program/Track:</td>
            <td class="value-cell">{{ $program ? $program->name : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Semester:</td>
            <td class="value-cell">{{ $semesterDisplay }}</td>
        </tr>
        <tr>
            <td class="label-cell">Section:</td>
            <td class="value-cell">{{ $student->section ? $student->section->name : 'N/A' }}</td>
        </tr>
        <tr>
        <tr>
            <td class="label-cell">Previous School Attended:</td>
            <td class="value-cell">{{ $record->last_school_attended ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Last Grade Level Completed:</td>
            <td class="value-cell">{{ $record->last_grade_level_completed ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- ADDITIONAL INFORMATION -->
    <div class="section-header">ADDITIONAL INFORMATION</div>
    <table class="info-table">
        <tr>
            <td class="label-cell">Special Needs or Disability:</td>
            <td class="value-cell">
                @if ($record && $record->has_special_needs && $record->special_needs)
                    {{ is_array($record->special_needs) ? implode(', ', $record->special_needs) : $record->special_needs }}
                @else
                    None
                @endif
            </td>
        </tr>
        <tr>
            <td class="label-cell">Belong to any IP community?:</td>
            <td class="value-cell">{{ $record && $record->belongs_to_ip ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Beneficiary of 4Ps?:</td>
            <td class="value-cell">{{ $record && $record->is_4ps_beneficiary ? 'Yes' : 'No' }}</td>
        </tr>
    </table>
</body>

</div>
</body>

</html>
