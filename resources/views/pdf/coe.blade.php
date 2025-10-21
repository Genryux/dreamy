<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Enrollment</title>
    <style>
        @page { margin: 48px; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #0f111c; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; }
        /* Reliable header for PDF rendering */
        .header-table { display: table; width: 100%; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; }
        .header-cell { display: table-cell; vertical-align: middle; }
        .logo-cell { width: 72px; }
        .header-right { text-align: right; vertical-align: middle; }
        .logo { width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #9ca3af; }
        .school h1 { margin: 0; font-size: 18px; font-weight: 800; }
        .school .school-info { display: block; margin-top: 4px; }
        .muted { color: #6b7280; font-size: 12px; margin: 0; }
        .title { text-align: center; margin: 24px 0 8px; font-size: 22px; font-weight: 800; }
        .subtitle { text-align: center; color: #6b7280; font-size: 10px; margin: 0 0 16px; }
        .p { font-size: 13px; line-height: 1.6; margin: 10px 0; text-align: center; }
        .grid { display: table; width: 100%; margin-top: 16px; }
        .col { display: table-cell; width: 50%; vertical-align: top; }
        .label { color: #6b7280; font-size: 11px; margin: 0 0 2px; }
        .value { font-size: 12px; margin: 0; }
        .footer { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 48px; font-size: 10px; color: #6b7280; }
        .signature { text-align: center; }
        .signature .line { height: 48px; }
        .signature .name { font-size: 12px; font-weight: 600; color: #0f111c; }
        .notice { text-align: center; font-size: 10px; color: #6b7280; margin-top: 8px; }
    </style>
    </head>
<body>
    @php
        $schoolName = $school->name ?? 'Dreamy School Philippines';
        $addressLines = array_filter([
            $school->address_line1 ?? null,
            $school->address_line2 ?? null,
        ]);
        $cityLine = array_filter([
            $school->city ?? null,
            $school->province ?? null,
            $school->country ?? null,
        ]);
        $schoolInfoLine = trim(implode(', ', array_merge($addressLines, $cityLine)));
        $logoSrc = isset($school) && $school->logo_path ? $school->logo_path : asset('images/Dreamy_logo.png');
        $registrarName = $school->registrar_name ?? '[Registrar’s Name]';
        $registrarTitle = $school->registrar_title ?? 'Registrar';
        $contactLine = trim(implode(' • ', array_filter([
            $school->email ?? null,
            $school->phone ?? null,
        ])));
    @endphp
    <div class="header-table">
        <div class="header-cell logo-cell">
            <div class="logo"><img src="{{ $logoSrc }}" alt="Logo" style="width: 64px; height: 64px;"></div>
        </div>
        <div class="header-cell">
            <div class="school">
                <h1>{{ $schoolName }}</h1>
                @if ($schoolInfoLine !== '')
                    <div class="muted school-info">{{ $schoolInfoLine }}</div>
                @endif
            </div>
        </div>
        <div class="header-cell header-right">
            <p class="muted">Date: {{ now()->format('F j, Y') }}</p>
            <p class="muted">Ref: COE-{{ $studentRecord->id ?? '0000' }}</p>
        </div>
    </div>

    <div>
        <div class="title">Certificate of Enrollment</div>
        <div class="subtitle">Academic Records Office</div>

        <p class="p">
            This is to certify that
            <strong>{{ $studentRecord->getFullName() ?? 'Student Name' }}</strong>, with Learner Reference Number (LRN)
            <strong>{{ $studentRecord->student->lrn ?? 'N/A' }}</strong>, is officially enrolled at
            <strong>{{ $schoolName }}</strong> for the
            <strong>{{ $studentRecord->acad_term_applied ?? 'Academic Year' }}</strong>
            {{ $studentRecord->semester_applied ? '(' . $studentRecord->semester_applied . ')' : '' }}.
        </p>
        <p class="p">
            The student is admitted in the <strong>{{ $studentRecord->student->program->code ?? 'Program' }}</strong> program, Grade Level
            <strong>{{ $studentRecord->student->grade_level ?? 'N/A' }}</strong>, Section
            <strong>{{ $studentRecord->student->sections->name ?? 'N/A' }}</strong>.
        </p>
        <p class="p">
            This certification is issued upon request of the student for whatever legal purpose it may serve.
        </p>

        <div class="grid">
            <div class="col">
                <p class="label">Student</p>
                <p class="value"><strong>{{ $studentRecord->getFullName() ?? '-' }}</strong></p>
                <p class="value">LRN: {{ $studentRecord->student->lrn ?? '-' }}</p>
            </div>
            <div class="col" style="text-align:right;">
                <p class="label">Program Details</p>
                <p class="value">Program: {{ $studentRecord->student->program->code ?? '-' }}</p>
                <p class="value">Grade Level: {{ $studentRecord->student->grade_level ?? '-' }}</p>
                <p class="value">Section: {{ $studentRecord->student->section->name ?? '-' }}</p>
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                <div class="line"></div>
                <div class="name">{{ auth()->user()->first_name .' '. auth()->user()->last_name }}</div>
                <div>{{ $registrarTitle }}</div>
            </div>
            <div>
                <div>Issued by: Academic Records Office</div>
                @if ($contactLine !== '')
                    <div>Contact: {{ $contactLine }}</div>
                @endif
            </div>
        </div>
        <p class="notice">Note: This certificate is not valid without the official stamp and signature of the Registrar.</p>
    </div>
</body>
</html>


