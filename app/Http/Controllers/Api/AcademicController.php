<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerms;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentSubject;
use App\Services\AcademicTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicController extends Controller
{
    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}

    /**
     * Get student's current section information with classmates and adviser
     */
    public function getCurrentSection(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $student = $user->student;
        
        // Get current enrollment for active term using service
        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        if (!$activeTerm) {
            return response()->json(['error' => 'No active academic term found'], 404);
        }

        $enrollment = $this->academicTermService->getStudentCurrentEnrollment($student->id);

        if (!$enrollment || !$enrollment->section_id) {
            // Return empty data instead of 404 for students not yet assigned to sections
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => null,
                    'name' => 'Not Assigned',
                    'grade_level' => '-',
                    'academic_term' => $activeTerm->getFullNameAttribute(),
                    'total_students' => 0,
                    'adviser' => [
                        'id' => null,
                        'name' => '-',
                        'email' => '-',
                        'phone' => '-',
                        'office' => '-',
                    ],
                    'classmates' => [],
                ]
            ]);
        }

        // Get section with adviser
        $section = Section::with([
            'program',
            'teacher', // This is the adviser
        ])->find($enrollment->section_id);

        // Get classmates separately to avoid the ambiguous column issue
        $classmates = Student::join('student_enrollments', 'students.id', '=', 'student_enrollments.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('student_enrollments.section_id', $enrollment->section_id)
            ->where('student_enrollments.academic_term_id', $activeTerm->id)
            ->select('students.id', 'users.first_name', 'users.last_name', 'students.lrn')
            ->get();

        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }

        // Format section data
        $sectionData = [
            'id' => $section->id,
            'name' => $section->name,
            'grade_level' => $section->program->name ?? '-',
            'academic_term' => $activeTerm->getFullNameAttribute(),
            'total_students' => $classmates->count(),
            'adviser' => $section->teacher ? [
                'id' => $section->teacher->id,
                'name' => trim($section->teacher->first_name . ' ' . $section->teacher->last_name) ?: '-',
                'email' => $section->teacher->email_address ?: '-',
                'phone' => $section->teacher->contact_number ?: '-',
                'office' => $section->teacher->specialization ?: '-',
            ] : [
                'id' => null,
                'name' => '-',
                'email' => '-',
                'phone' => '-',
                'office' => '-',
            ],
            'classmates' => $classmates->map(function ($classmate) {
                return [
                    'id' => $classmate->id,
                    'name' => trim($classmate->first_name . ' ' . $classmate->last_name) ?: '-',
                    'lrn' => $classmate->lrn ?: '-',
                ];
            })->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $sectionData
        ]);
    }

    /**
     * Get student's subjects for the current term
     */
    public function getCurrentSubjects(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $student = $user->student;
        
        // Get current enrollment for active term using service
        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        if (!$activeTerm) {
            return response()->json(['error' => 'No active academic term found'], 404);
        }

        $enrollment = $this->academicTermService->getStudentCurrentEnrollment($student->id);

        // Build enrolled subjects from section_subjects (current term)
        $enrolledSubjects = [];
        if ($enrollment && $enrollment->section_id) {
            $sectionSubjects = SectionSubject::with([
                'subject',
                'teacher',
                'section'
            ])->where('section_id', $enrollment->section_id)->get();

            $enrolledSubjects = $sectionSubjects->map(function ($sectionSubject) use ($activeTerm, $student) {
                $subject = $sectionSubject->subject;
                $teacher = $sectionSubject->teacher;

                // Try to find a StudentSubject record for this student, section_subject, and current term
                $studentSubject = \App\Models\StudentSubject::where('student_id', $student->id)
                    ->where('section_subject_id', $sectionSubject->id)
                    ->where('academic_terms_id', $activeTerm->id)
                    ->first();

                // Format schedule with readable time (12-hour format with AM/PM)
                $schedule = '';
                $daysArray = [];
                if ($sectionSubject->days_of_week && $sectionSubject->start_time && $sectionSubject->end_time) {
                    $daysArray = is_array($sectionSubject->days_of_week)
                        ? $sectionSubject->days_of_week
                        : explode(', ', $sectionSubject->days_of_week);

                    $startTime = \Carbon\Carbon::parse($sectionSubject->start_time)->format('g:i A');
                    $endTime = \Carbon\Carbon::parse($sectionSubject->end_time)->format('g:i A');

                    $schedule = $startTime . ' - ' . $endTime;
                }

                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->name ?: 'Unknown Subject',
                    'teacher_name' => $teacher ? (trim($teacher->first_name . ' ' . $teacher->last_name) ?: null) : null,
                    'days_of_week' => $daysArray,
                    'time_display' => $schedule ?: null,
                    'room' => $sectionSubject->room ?: null,
                    'status' => 'enrolled',
                    'evaluation_status' => $studentSubject ? $studentSubject->evaluation_status : null,
                    'remedial_status' => $studentSubject ? $studentSubject->remedial_status : null,
                    'remedial_deadline' => $studentSubject && $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->toDateString() : null,
                    'academic_term' => $activeTerm->getFullNameAttribute(),
                ];
            })->toArray();
        }

        // Build taken subjects from student_subjects (historical records)
        $takenSubjects = StudentSubject::with(['subject', 'teacher', 'academicTerm'])
            ->where('student_id', $student->id)
            ->where('status', 'taken')
            ->get()
            ->map(function ($studentSubject) {
                $subject = $studentSubject->subject;
                $teacher = $studentSubject->teacher;
                $term = $studentSubject->academicTerm;

                return [
                    'id' => $studentSubject->id,
                    'subject_name' => $subject ? ($subject->name ?: 'Unknown Subject') : 'Unknown Subject',
                    'teacher_name' => $teacher ? (trim($teacher->first_name . ' ' . $teacher->last_name) ?: null) : null,
                    'days_of_week' => null,
                    'time_display' => null,
                    'room' => null,
                    'status' => 'taken',
                    'evaluation_status' => $studentSubject->evaluation_status,
                    'remedial_status' => $studentSubject->remedial_status,
                    'remedial_deadline' => $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->toDateString() : null,
                    'academic_term' => $term ? $term->getFullNameAttribute() : 'Unknown Term',
                ];
            })->toArray();

        // Combine all subjects
        $allSubjects = array_merge($enrolledSubjects, $takenSubjects);

        // Get section info for context
        $sectionInfo = [
            'name' => 'Not Assigned',
            'grade_level' => '-',
            'academic_term' => $activeTerm->getFullNameAttribute(),
        ];
        if ($enrollment && $enrollment->section_id) {
            $section = Section::with('program')->find($enrollment->section_id);
            $sectionInfo = [
                'name' => $section->name ?: '-',
                'grade_level' => $section->program->name ?? '-',
                'academic_term' => $activeTerm->getFullNameAttribute(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'academic_status' => $student->academic_status,
                'section_info' => $sectionInfo,
                'subjects' => $allSubjects,
            ]
        ]);
    }

    /**
     * Get student's academic summary (both section and subjects)
     */
    public function getAcademicSummary(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $student = $user->student;
        
        // Get current enrollment for active term using service
        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        if (!$activeTerm) {
            return response()->json(['error' => 'No active academic term found'], 404);
        }

        $enrollment = $this->academicTermService->getStudentCurrentEnrollment($student->id);

        if (!$enrollment || !$enrollment->section_id) {
            // Return empty data instead of 404 for students not yet assigned to sections
            return response()->json([
                'success' => true,
                'data' => [
                    'section' => [
                        'id' => null,
                        'name' => 'Not Assigned',
                        'grade_level' => '-',
                        'academic_term' => $activeTerm->getFullNameAttribute(),
                        'total_students' => 0,
                        'adviser' => [
                            'id' => null,
                            'name' => '-',
                            'email' => '-',
                            'phone' => '-',
                            'office' => '-',
                        ],
                        'classmates' => [],
                    ],
                    'subjects' => [],
                ]
            ]);
        }

        // Get section with adviser
        $section = Section::with([
            'program',
            'teacher',
        ])->find($enrollment->section_id);

        // Get classmates separately to avoid the ambiguous column issue
        $classmates = Student::join('student_enrollments', 'students.id', '=', 'student_enrollments.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('student_enrollments.section_id', $enrollment->section_id)
            ->where('student_enrollments.academic_term_id', $activeTerm->id)
            ->select('students.id', 'users.first_name', 'users.last_name', 'students.lrn')
            ->get();

        // Get section subjects
        $sectionSubjects = SectionSubject::with([
            'subject',
            'teacher'
        ])->where('section_id', $enrollment->section_id)->get();

        // Format section data
        $sectionData = [
            'id' => $section->id,
            'name' => $section->name ?: '-',
            'grade_level' => $section->program->name ?? '-',
            'academic_term' => $activeTerm->getFullNameAttribute(),
            'total_students' => $classmates->count(),
            'adviser' => $section->teacher ? [
                'id' => $section->teacher->id,
                'name' => trim($section->teacher->first_name . ' ' . $section->teacher->last_name) ?: '-',
                'email' => $section->teacher->email_address ?: '-',
                'phone' => $section->teacher->contact_number ?: '-',
                'office' => $section->teacher->specialization ?: '-',
            ] : [
                'id' => null,
                'name' => '-',
                'email' => '-',
                'phone' => '-',
                'office' => '-',
            ],
            'classmates' => $classmates->map(function ($classmate) {
                return [
                    'id' => $classmate->id,
                    'name' => trim($classmate->first_name . ' ' . $classmate->last_name) ?: '-',
                    'lrn' => $classmate->lrn ?: '-',
                ];
            })->toArray(),
        ];

        // Format subjects data
        $subjectsData = $sectionSubjects->map(function ($sectionSubject) {
            $subject = $sectionSubject->subject;
            $teacher = $sectionSubject->teacher;
            
            $schedule = '';
            if ($sectionSubject->days_of_week && $sectionSubject->start_time && $sectionSubject->end_time) {
                $days = is_array($sectionSubject->days_of_week) 
                    ? implode(', ', $sectionSubject->days_of_week)
                    : $sectionSubject->days_of_week;
                $schedule = $days . ' ' . $sectionSubject->start_time . '-' . $sectionSubject->end_time;
            }

            return [
                'id' => $subject->id,
                'name' => $subject->name ?: '-',
                'code' => $subject->name ?: '-',
                'teacher' => $teacher ? (trim($teacher->first_name . ' ' . $teacher->last_name) ?: '-') : '-',
                'schedule' => $schedule ?: '-',
                'room' => $sectionSubject->room ?: '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'section' => $sectionData,
                'subjects' => $subjectsData->toArray(),
            ]
        ]);
    }
}
