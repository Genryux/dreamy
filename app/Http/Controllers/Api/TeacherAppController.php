<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerms;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAppController extends Controller
{
    /**
     * Get teacher dashboard data - Today's schedule and summary
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        // Get current day of week (0 = Sunday, 1 = Monday, etc.)
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeek;
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $currentDay = $dayNames[$dayOfWeek];

        // Get current active academic term
        $activeTerm = AcademicTerms::where('is_active', true)->first();

        // Get all section subjects assigned to this teacher
        $allSubjects = SectionSubject::with(['section.program', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->get();

        // Filter for today's schedule
        $todaySchedule = $allSubjects->filter(function ($sectionSubject) use ($currentDay) {
            $days = $sectionSubject->days_of_week ?? [];
            return in_array($currentDay, $days);
        })->sortBy('start_time')->values();

        // Count statistics
        $totalSections = $allSubjects->pluck('section_id')->unique()->count();
        $totalSubjects = $allSubjects->pluck('subject_id')->unique()->count();
        $totalStudents = StudentSubject::whereIn('section_subject_id', $allSubjects->pluck('id'))
            ->distinct('student_id')
            ->count('student_id');

        return response()->json([
            'success' => true,
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->full_name,
                    'employee_id' => $teacher->employee_id,
                    'email' => $teacher->email,
                    'specialization' => $teacher->specialization,
                ],
                'current_date' => $today->format('l, F j, Y'),
                'current_day' => $currentDay,
                'academic_term' => $activeTerm ? $activeTerm->full_name : 'No Active Term',
                'statistics' => [
                    'total_sections' => $totalSections,
                    'total_subjects' => $totalSubjects,
                    'total_students' => $totalStudents,
                    'classes_today' => $todaySchedule->count(),
                ],
                'today_schedule' => $todaySchedule->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'subject_id' => $item->subject_id,
                        'subject_name' => $item->subject->name ?? 'Unknown Subject',
                        'section_id' => $item->section_id,
                        'section_name' => $item->section->name ?? 'Unknown Section',
                        'program' => $item->section->program->name ?? 'N/A',
                        'year_level' => $item->section->year_level ?? 'N/A',
                        'room' => $item->room ?? 'TBA',
                        'start_time' => $item->start_time ? Carbon::parse($item->start_time)->format('g:i A') : null,
                        'end_time' => $item->end_time ? Carbon::parse($item->end_time)->format('g:i A') : null,
                        'time_display' => $this->formatTimeRange($item->start_time, $item->end_time),
                        'students_count' => $item->studentSubjects()->count(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get all subjects/classes taught by the teacher (full schedule)
     */
    public function myClasses(Request $request): JsonResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        // Get all section subjects assigned to this teacher
        $subjects = SectionSubject::with(['section.program', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->get();

        // Group by day of week
        $scheduleByDay = [];
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($dayOrder as $day) {
            $daySubjects = $subjects->filter(function ($item) use ($day) {
                $days = $item->days_of_week ?? [];
                return in_array($day, $days);
            })->sortBy('start_time')->values();

            if ($daySubjects->isNotEmpty()) {
                $scheduleByDay[$day] = $daySubjects->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'subject_id' => $item->subject_id,
                        'subject_name' => $item->subject->name ?? 'Unknown Subject',
                        'section_id' => $item->section_id,
                        'section_name' => $item->section->name ?? 'Unknown Section',
                        'program' => $item->section->program->name ?? 'N/A',
                        'year_level' => $item->section->year_level ?? 'N/A',
                        'room' => $item->room ?? 'TBA',
                        'start_time' => $item->start_time ? Carbon::parse($item->start_time)->format('g:i A') : null,
                        'end_time' => $item->end_time ? Carbon::parse($item->end_time)->format('g:i A') : null,
                        'time_display' => $this->formatTimeRange($item->start_time, $item->end_time),
                        'students_count' => $item->studentSubjects()->count(),
                    ];
                });
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'schedule_by_day' => $scheduleByDay,
                'all_classes' => $subjects->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'subject_id' => $item->subject_id,
                        'subject_name' => $item->subject->name ?? 'Unknown Subject',
                        'section_id' => $item->section_id,
                        'section_name' => $item->section->name ?? 'Unknown Section',
                        'program' => $item->section->program->name ?? 'N/A',
                        'year_level' => $item->section->year_level ?? 'N/A',
                        'room' => $item->room ?? 'TBA',
                        'days_of_week' => $item->days_of_week ?? [],
                        'start_time' => $item->start_time ? Carbon::parse($item->start_time)->format('g:i A') : null,
                        'end_time' => $item->end_time ? Carbon::parse($item->end_time)->format('g:i A') : null,
                        'time_display' => $this->formatTimeRange($item->start_time, $item->end_time),
                        'students_count' => $item->studentSubjects()->count(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get section details with enrolled students for a specific section subject
     */
    public function getSectionStudents(Request $request, int $sectionSubjectId): JsonResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        // Get the section subject and verify it belongs to this teacher
        $sectionSubject = SectionSubject::with(['section.program', 'subject'])
            ->where('id', $sectionSubjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$sectionSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or you are not authorized to view it.',
            ], 404);
        }

        // Get all students enrolled in this section subject
        $studentSubjects = StudentSubject::with(['student.user', 'student.record', 'student.program'])
            ->where('section_subject_id', $sectionSubjectId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'class_info' => [
                    'id' => $sectionSubject->id,
                    'subject_name' => $sectionSubject->subject->name ?? 'Unknown Subject',
                    'section_name' => $sectionSubject->section->name ?? 'Unknown Section',
                    'program' => $sectionSubject->section->program->name ?? 'N/A',
                    'year_level' => $sectionSubject->section->year_level ?? 'N/A',
                    'room' => $sectionSubject->room ?? 'TBA',
                    'days_of_week' => $sectionSubject->days_of_week ?? [],
                    'time_display' => $this->formatTimeRange($sectionSubject->start_time, $sectionSubject->end_time),
                ],
                'students_count' => $studentSubjects->count(),
                'students' => $studentSubjects->map(function ($studentSubject) {
                    $student = $studentSubject->student;
                    $record = $student->record;
                    
                    return [
                        'id' => $student->id,
                        'student_subject_id' => $studentSubject->id,
                        'lrn' => $student->lrn,
                        'name' => $student->full_name,
                        'first_name' => $student->user->first_name ?? '',
                        'last_name' => $student->user->last_name ?? '',
                        'email' => $student->user->email ?? '',
                        'gender' => $record->gender ?? 'N/A',
                        'grade_level' => $student->grade_level,
                        'program' => $student->program->name ?? 'N/A',
                        'evaluation_status' => $studentSubject->evaluation_status ?? 'pending',
                        'contact_number' => $record->contact_number ?? null,
                    ];
                })->sortBy('name')->values(),
                'summary' => [
                    'total' => $studentSubjects->count(),
                    'passed' => $studentSubjects->where('evaluation_status', 'passed')->count(),
                    'failed' => $studentSubjects->where('evaluation_status', 'failed')->count(),
                    'pending' => $studentSubjects->where('evaluation_status', null)->count(),
                ],
            ],
        ]);
    }

    /**
     * Get individual student details
     */
    public function getStudentDetails(Request $request, int $sectionSubjectId, int $studentId): JsonResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        // Verify this section subject belongs to this teacher
        $sectionSubject = SectionSubject::where('id', $sectionSubjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$sectionSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or you are not authorized to view it.',
            ], 404);
        }

        // Get the student subject record
        $studentSubject = StudentSubject::with(['student.user', 'student.record', 'student.program', 'student.section'])
            ->where('student_id', $studentId)
            ->where('section_subject_id', $sectionSubjectId)
            ->first();

        if (!$studentSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found in this class.',
            ], 404);
        }

        $student = $studentSubject->student;
        $record = $student->record;

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'student_subject_id' => $studentSubject->id,
                    'lrn' => $student->lrn,
                    'name' => $student->full_name,
                    'first_name' => $student->user->first_name ?? '',
                    'last_name' => $student->user->last_name ?? '',
                    'middle_name' => $record->middle_name ?? '',
                    'email' => $student->user->email ?? '',
                    'gender' => $record->gender ?? 'N/A',
                    'birthdate' => $record->birthdate ?? null,
                    'age' => $record->age ?? null,
                    'contact_number' => $record->contact_number ?? null,
                    'current_address' => $record->current_address ?? null,
                    'grade_level' => $student->grade_level,
                    'program' => $student->program->name ?? 'N/A',
                    'section' => $student->section->name ?? 'N/A',
                    'status' => $student->status,
                ],
                'enrollment' => [
                    'evaluation_status' => $studentSubject->evaluation_status ?? 'pending',
                    'subject_name' => $sectionSubject->subject->name ?? 'Unknown',
                ],
                'guardian_info' => [
                    'father_name' => $record->father_name ?? null,
                    'father_contact' => $record->father_contact_number ?? null,
                    'mother_name' => $record->mother_name ?? null,
                    'mother_contact' => $record->mother_contact_number ?? null,
                    'guardian_name' => $record->guardian_name ?? null,
                    'guardian_contact' => $record->guardian_contact_number ?? null,
                ],
            ],
        ]);
    }

    /**
     * Evaluate student - Mark as passed or failed
     */
    public function evaluateStudent(Request $request, int $sectionSubjectId, int $studentId): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:passed,failed',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;

        // Verify this section subject belongs to this teacher
        $sectionSubject = SectionSubject::where('id', $sectionSubjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$sectionSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or you are not authorized to evaluate students.',
            ], 404);
        }

        // Get the student subject record
        $studentSubject = StudentSubject::where('student_id', $studentId)
            ->where('section_subject_id', $sectionSubjectId)
            ->first();

        if (!$studentSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found in this class.',
            ], 404);
        }

        // Update the evaluation status
        $studentSubject->evaluation_status = $request->status;
        $studentSubject->save();

        // Get student info for response
        $student = Student::with('user')->find($studentId);

        return response()->json([
            'success' => true,
            'message' => "Student {$student->full_name} has been marked as {$request->status}.",
            'data' => [
                'student_id' => $studentId,
                'student_name' => $student->full_name,
                'section_subject_id' => $sectionSubjectId,
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Bulk evaluate students
     */
    public function bulkEvaluateStudents(Request $request, int $sectionSubjectId): JsonResponse
    {
        $request->validate([
            'evaluations' => 'required|array|min:1',
            'evaluations.*.student_id' => 'required|integer|exists:students,id',
            'evaluations.*.status' => 'required|in:passed,failed',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;

        // Verify this section subject belongs to this teacher
        $sectionSubject = SectionSubject::where('id', $sectionSubjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$sectionSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or you are not authorized to evaluate students.',
            ], 404);
        }

        $updated = 0;
        $errors = [];

        foreach ($request->evaluations as $evaluation) {
            $studentSubject = StudentSubject::where('student_id', $evaluation['student_id'])
                ->where('section_subject_id', $sectionSubjectId)
                ->first();

            if ($studentSubject) {
                $studentSubject->evaluation_status = $evaluation['status'];
                $studentSubject->save();
                $updated++;
            } else {
                $errors[] = "Student ID {$evaluation['student_id']} not found in this class.";
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updated} students evaluated successfully.",
            'data' => [
                'updated_count' => $updated,
                'errors' => $errors,
            ],
        ]);
    }

    /**
     * Get teacher profile information
     */
    public function profile(Request $request): JsonResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'pin_enabled' => $user->pin_enabled,
                ],
                'teacher' => [
                    'id' => $teacher->id,
                    'employee_id' => $teacher->employee_id,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'full_name' => $teacher->full_name,
                    'email_address' => $teacher->email_address,
                    'contact_number' => $teacher->contact_number,
                    'specialization' => $teacher->specialization,
                    'status' => $teacher->status,
                    'program' => $teacher->program->name ?? null,
                ],
            ],
        ]);
    }

    /**
     * Update teacher profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;

        // Update allowed fields
        if ($request->has('first_name')) {
            $teacher->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
            $teacher->last_name = $request->last_name;
        }
        if ($request->has('specialization')) {
            $teacher->specialization = $request->specialization;
        }
        if ($request->has('contact_number')) {
            $teacher->contact_number = $request->contact_number;
        }
        if ($request->has('email_address')) {
            $teacher->email_address = $request->email_address;
        }

        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'full_name' => $teacher->full_name,
                    'specialization' => $teacher->specialization,
                    'contact_number' => $teacher->contact_number,
                    'email_address' => $teacher->email_address,
                ],
            ],
        ]);
    }

    /**
     * Helper: Format time range display
     */
    private function formatTimeRange(?string $start, ?string $end): string
    {
        if (!$start || !$end) {
            return 'TBA';
        }

        $startFormatted = Carbon::parse($start)->format('g:i A');
        $endFormatted = Carbon::parse($end)->format('g:i A');

        return "{$startFormatted} - {$endFormatted}";
    }
}
