<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$studentSubjects = DB::table('student_subjects')
    ->select('id', 'student_id', 'section_subject_id', 'teacher_id', 'subject_id', 'status', 'academic_terms_id')
    ->get();

echo "Total student_subjects records: " . $studentSubjects->count() . "\n\n";

foreach ($studentSubjects as $ss) {
    echo "ID: {$ss->id}\n";
    echo "  section_subject_id: " . ($ss->section_subject_id ?? 'NULL') . "\n";
    echo "  teacher_id: " . ($ss->teacher_id ?? 'NULL') . "\n";
    echo "  subject_id: " . ($ss->subject_id ?? 'NULL') . "\n";
    echo "  status: {$ss->status}\n";
    echo "  academic_terms_id: " . ($ss->academic_terms_id ?? 'NULL') . "\n";
    echo "---\n";
}

// Also check section_subjects
$sectionSubjects = DB::table('section_subjects')->count();
echo "\nTotal section_subjects records: {$sectionSubjects}\n";
