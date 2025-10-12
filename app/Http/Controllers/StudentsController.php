<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SchoolFee;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentSubject;
use App\Models\SectionSubject;
use App\Models\AcademicTerms;
use App\Models\Applicants;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{

    public function getStudent(Request $request)
    {
        $query = Student::query();

        try {
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('lrn', 'like', "%{$search}%")
                        ->orWhere('program', 'like', "%{$search}%")
                        ->orWhere('grade_level', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                        });
                });
            }

            // Limit to avoid sending thousands at once
            $students = $query->with('user')->select('id', 'lrn', 'grade_level', 'program', 'user_id')
                ->limit(50)
                ->first();

            if (!$students) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found for the given search.',
                ]);
            }

            $schoolFee = SchoolFee::where(function ($q) use ($students) {
                $q->whereHas('program', function ($sub) use ($students) {
                    $sub->where('code', $students->program);
                })
                    ->orWhereNull('program_id'); // include general fees
            })
                ->where(function ($q) use ($students) {
                    $q->where('grade_level', $students->grade_level)
                        ->orWhereNull('grade_level'); // allow default fees
                })
                ->get();

            // Get the active academic term
            $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();

            return response()->json([
                'success' => true,
                'data' => $students,
                'fees' => $schoolFee,
                // 'hasInvoice' => $activeTerm ? Invoice::where('student_id', $students->id)
                //     ->where('academic_term_id', $activeTerm->id)
                //     ->exists() : false
            ]);
        } catch (\Throwable $th) {
            \Log::error('Student search error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ]);
        }
    }

    public function assignSection(Section $section, Request $request)
    {
        $selectedStudents = array_map('intval', $request->input('student'));

        try {
            DB::transaction(function () use ($selectedStudents, $section) {
                // Update Student model (for admin panel compatibility)
                Student::whereIn('id', $selectedStudents)
                    ->update(['section_id' => $section->id]);

                // Update StudentEnrollment model (for mobile app API)
                // Get the active academic term
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();

                if ($activeTerm) {
                    \App\Models\StudentEnrollment::whereIn('student_id', $selectedStudents)
                        ->where('academic_term_id', $activeTerm->id)
                        ->update(['section_id' => $section->id]);

                    // Auto-assign subjects: Get all subjects offered by this section
                    $sectionSubjects = \App\Models\SectionSubject::where('section_id', $section->id)->get();

                    foreach ($selectedStudents as $studentId) {
                        foreach ($sectionSubjects as $sectionSubject) {
                            // Check if student is already enrolled in this subject
                            $existingEnrollment = \App\Models\StudentSubject::where('student_id', $studentId)
                                ->where('section_subject_id', $sectionSubject->id)
                                ->first();

                            // Only create if not already enrolled
                            if (!$existingEnrollment) {
                                \App\Models\StudentSubject::create([
                                    'student_id' => $studentId,
                                    'section_subject_id' => $sectionSubject->id,
                                    'status' => 'enrolled'
                                ]);
                            }
                        }
                    }
                }
            });

            $studentCount = $section->students->count();

            return response()->json([
                'success' => 'Section successfully assigned to the selected students',
                'count'   => $studentCount
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'request' => $request->all()
            ]);
        }

        return response()->json([
            'request' => $request->all()
        ]);
    }

    public function removeStudentFromSection(Section $section, Request $request)
    {
        $studentId = $request->input('student_id');

        try {
            DB::transaction(function () use ($studentId, $section) {
                // Update Student model (for admin panel compatibility)
                Student::where('id', $studentId)
                    ->update(['section_id' => null]);

                // Update StudentEnrollment model (for mobile app API)
                // Get the active academic term
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();

                if ($activeTerm) {
                    \App\Models\StudentEnrollment::where('student_id', $studentId)
                        ->where('academic_term_id', $activeTerm->id)
                        ->update(['section_id' => null]);

                    // Remove student from all subjects in this section
                    $sectionSubjects = \App\Models\SectionSubject::where('section_id', $section->id)->get();

                    foreach ($sectionSubjects as $sectionSubject) {
                        \App\Models\StudentSubject::where('student_id', $studentId)
                            ->where('section_subject_id', $sectionSubject->id)
                            ->delete();
                    }
                }
            });

            $studentCount = $section->students->count();

            return response()->json([
                'success' => true,
                'message' => 'Student successfully removed from section',
                'studentCount' => $studentCount
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove student from section: ' . $th->getMessage()
            ]);
        }
    }

    public function getUsers(Request $request)
    {
        // Feature flag: use new per-term enrollment system or fallback to old system
        if (config('app.use_term_enrollments')) {
            return $this->getUsersFromEnrollments($request);
        }

        // Use original logic
        return $this->getOriginalUsers($request);
    }

    public function index()
    {

        // $enrolled_students = Students::all();

        // dd($enrolled_students);
        $data = User::limit(10)->get();

        // dd($data);

        return view('user-admin.enrolled-students.index');
    }

    /**
     * Get enrollment statistics for the current term
     */
    public function getEnrollmentStats(Request $request)
    {
        if (!config('app.use_term_enrollments')) {
            // Fallback: just count all students
            $total = Student::count();
            return response()->json([
                'total' => $total,
                'enrolled' => $total, // Assume all are enrolled in old system
                'pending' => 0,
            ]);
        }

        // Get selected term from URL parameter or default to active term
        $termId = $request->get('term_id');
        $selectedTerm = $termId
            ? AcademicTerms::find($termId)
            : AcademicTerms::where('is_active', true)->first();

        if (!$selectedTerm) {
            return response()->json([
                'total' => 0,
                'enrolled' => 0,
                'pending' => 0,
            ]);
        }

        $baseQuery = StudentEnrollment::where('academic_term_id', $selectedTerm->id);
        $total = $baseQuery->count();
        $enrolled = StudentEnrollment::where('academic_term_id', $selectedTerm->id)->where('status', 'enrolled')->count();
        $pending = StudentEnrollment::where('academic_term_id', $selectedTerm->id)->where('status', 'pending_confirmation')->count();

        return response()->json([
            'total' => $total,
            'enrolled' => $enrolled,
            'pending' => $pending,
            'term_name' => $selectedTerm->getFullNameAttribute(),
        ]);
    }

    /**
     * Original getUsers logic (extracted to avoid recursion)
     */
    private function getOriginalUsers(Request $request)
    {
        $query = Student::with('record');

        // search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                    })
                    ->orWhereHas('record', function ($recordQuery) use ($search) {
                        $recordQuery->where('contact_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filtering
        if ($program = $request->input('program_filter')) {
            $query->where('program', $program);
        }

        if ($grade = $request->input('grade_filter')) {
            $query->where('grade_level', $grade);
        }

        // Sorting
        $columns = ['lrn', 'grade_level', 'program'];
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';
        $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        $start = $request->input('start', 0);

        $data = $query
            ->with(['user', 'record'])
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'lrn', 'grade_level', 'program'])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $item->lrn,
                    'full_name' => $item->user->last_name . ', ' . $item->user->first_name,
                    'grade_level' => $item->grade_level,
                    'program' => $item->program,
                    'contact' => $item->record?->contact_number ?? '-',
                    'email' => $item->user->email,
                    'id' => $item->record?->id ?? $item->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Get users from the new per-term enrollment system
     */
    private function getUsersFromEnrollments(Request $request)
    {
        // Get selected term from URL parameter or default to active term
        $termId = $request->get('term_id');
        $selectedTerm = $termId
            ? AcademicTerms::find($termId)
            : AcademicTerms::where('is_active', true)->first();

        if (!$selectedTerm) {
            // Fallback to original method if no term found (avoid recursion)
            return $this->getOriginalUsers($request);
        }

        // Query enrollments for the selected term
        $query = StudentEnrollment::with(['student.record'])
            ->where('academic_term_id', $selectedTerm->id);

        // Filter by status if specified
        $statusFilter = $request->input('status_filter');
        if ($statusFilter && in_array($statusFilter, ['enrolled', 'pending_confirmation'])) {
            $query->where('status', $statusFilter);
        }
        // Default: show all statuses (enrolled + pending)

        // Search filter - search within the related student
        if ($search = $request->input('search.value')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                    })
                    ->orWhereHas('record', function ($recordQuery) use ($search) {
                        $recordQuery->where('contact_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filtering by program and grade
        if ($program = $request->input('program_filter')) {
            $query->whereHas('student', fn($q) => $q->where('program', $program));
        }

        if ($grade = $request->input('grade_filter')) {
            $query->whereHas('student', fn($q) => $q->where('grade_level', $grade));
        }

        // Get total count before applying sorting/pagination
        $total = $query->count();
        $filtered = $total;

        // Apply sorting - use a simpler approach with eager loading
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        // For now, let's just order by enrollment id to avoid join complexity
        $query->orderBy('student_enrollments.id', $orderDir);

        $start = $request->input('start', 0);

        $data = $query
            ->with(['student.user', 'student.record'])
            ->offset($start)
            ->limit($request->length)
            ->get()
            ->map(function ($enrollment, $key) use ($start) {
                $student = $enrollment->student;
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $student->lrn ?? '',
                    'full_name' => ($student->user->last_name ?? '') . ', ' . ($student->user->first_name ?? ''),
                    'grade_level' => $student->grade_level ?? '',
                    'program' => $student->program ?? '',
                    'contact' => $student->record?->contact_number ?? '',
                    'email' => $student->user->email ?? '',
                    'status' => $enrollment->status === 'enrolled' ? 'Enrolled' : 'Pending Confirmation',
                    'status_raw' => $enrollment->status,
                    'confirmed_at' => $enrollment->confirmed_at ? $enrollment->confirmed_at->format('M j, Y') : null,
                    'id' => $student->record?->id ?? $student->id,
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Promote applicant to become an officially enrolled student
     */
    public function promoteApplicant(Request $request, Applicants $applicants)
    {

        $request->validate([
            'action' => 'required|string|in:enroll-student',
        ]);

        match ($request->action) {
            'enroll-student' => $applicants->update(['application_status' => 'Officially Enrolled']),
            default => abort(400, 'Invalid action'),
        };

        return redirect()->back();
    }
}
