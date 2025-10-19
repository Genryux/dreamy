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
use App\Models\Program;
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
                        ->orWhere('grade_level', 'like', "%{$search}%")
                        ->orWhereHas('program', function ($programQuery) use ($search) {
                            $programQuery->where('code', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                        });
                });
            }

            // Limit to avoid sending thousands at once
            $students = $query->with(['user', 'program'])->select('id', 'lrn', 'grade_level', 'program_id', 'user_id')
                ->limit(50)
                ->first();

            if (!$students) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found for the given search.',
                ]);
            }

            $schoolFee = SchoolFee::where(function ($q) use ($students) {
                $q->where('program_id', $students->program_id)
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
        // Get all active programs for the filter dropdown
        $programs = Program::where('status', 'active')
            ->orderBy('code')
            ->get(['id', 'code', 'name']);

        return view('user-admin.enrolled-students.index', compact('programs'));
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
            $query->where('program_id', $program);
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
            ->get(['id', 'lrn', 'grade_level', 'program_id'])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $item->lrn,
                    'full_name' => $item->user->last_name . ', ' . $item->user->first_name,
                    'grade_level' => $item->grade_level,
                    'program' => $item->program->code,
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
            // If no active term, get the most recent inactive term
            $selectedTerm = AcademicTerms::orderBy('year', 'desc')
                ->orderBy('semester', 'desc')
                ->first();
            
            // If still no term found, fallback to original method
            if (!$selectedTerm) {
                return $this->getOriginalUsers($request);
            }
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
            $query->whereHas('student', fn($q) => $q->where('program_id', $program));
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
            ->with(['student.user', 'student.record', 'section'])
            ->offset($start)
            ->limit($request->length)
            ->get()
            ->map(function ($enrollment, $key) use ($start) {
                $student = $enrollment->student;
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $student->lrn ?? '',
                    'full_name' => ($student->user->last_name ?? '-') . ', ' . ($student->user->first_name ?? ''),
                    'grade_level' => $student->grade_level ?? '-',
                    'program' => $student->program->code ?? '-',
                    'section' => $enrollment->section->name ?? '-',
                    'contact' => $student->record?->contact_number ?? '-',
                    'email' => $student->user->email ?? '-',
                    'status' => $enrollment->status,
                    'status_raw' => $enrollment->status,
                    'confirmed_at' => $enrollment->confirmed_at ? $enrollment->confirmed_at->format('M j, Y') : null,
                    'id' => $student->id,
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
     * Get application analytics data for dashboard
     */
    public function getApplicationAnalytics(Request $request)
    {
        try {
            // Get selected term from URL parameter or default to active term
            $termId = $request->get('term_id');
            $selectedTerm = $termId
                ? AcademicTerms::find($termId)
                : AcademicTerms::where('is_active', true)->first();

            if (!$selectedTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'No academic term found'
                ]);
            }

            // Get active enrollment period for the selected term
            $activeEnrollmentPeriod = \App\Services\EnrollmentPeriodService::class;
            $enrollmentPeriodService = app($activeEnrollmentPeriod);
            $enrollmentPeriod = $enrollmentPeriodService->getActiveEnrollmentPeriod($selectedTerm->id);

            if (!$enrollmentPeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active enrollment period found for the selected academic term'
                ]);
            }

            // Get program analytics for applications in the active enrollment period
            $programAnalytics = $this->getProgramAnalytics($enrollmentPeriod->id);

            // Get grade level analytics for applications in the active enrollment period
            $gradeLevelAnalytics = $this->getGradeLevelAnalytics($enrollmentPeriod->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'programs' => $programAnalytics,
                    'grade_levels' => $gradeLevelAnalytics
                ]
            ]);
        } catch (\Throwable $th) {
            \Log::error('Application analytics error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Get enrollment analytics data for charts
     */
    public function getEnrollmentAnalytics(Request $request)
    {
        try {
            // Get selected term from URL parameter or default to active term (same logic as getEnrollmentStats)
            $termId = $request->get('term_id');
            $selectedTerm = $termId
                ? AcademicTerms::find($termId)
                : AcademicTerms::where('is_active', true)->first();

            if (!$selectedTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'No academic term found'
                ]);
            }

            // Get program analytics for enrolled students in the selected academic term
            $programAnalytics = $this->getEnrolledProgramAnalytics($selectedTerm->id);

            // Get grade level analytics for enrolled students in the selected academic term
            $gradeLevelAnalytics = $this->getEnrolledGradeLevelAnalytics($selectedTerm->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'programs' => $programAnalytics,
                    'grade_levels' => $gradeLevelAnalytics
                ]
            ]);
        } catch (\Throwable $th) {
            \Log::error('Enrollment analytics error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Get program analytics for enrolled students
     */
    private function getEnrolledProgramAnalytics($academicTermId)
    {
        // Count enrolled students by program for the current academic term
        $programData = \App\Models\StudentEnrollment::join('students', 'student_enrollments.student_id', '=', 'students.id')
            ->join('programs', 'students.program_id', '=', 'programs.id')
            ->where('student_enrollments.academic_term_id', $academicTermId)
            ->select('programs.id as program_id', 'programs.code as program_code', 'programs.name as program_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('programs.id', 'programs.code', 'programs.name')
            ->orderBy('count', 'desc')
            ->get();

        return $programData->map(function ($item) {
            return [
                'id' => $item->program_id,
                'code' => $item->program_code,
                'name' => $item->program_name,
                'count' => $item->count
            ];
        });
    }

    /**
     * Get grade level analytics for enrolled students
     */
    private function getEnrolledGradeLevelAnalytics($academicTermId)
    {
        // Count enrolled students by grade level for the current academic term
        $gradeData = \App\Models\StudentEnrollment::join('students', 'student_enrollments.student_id', '=', 'students.id')
            ->where('student_enrollments.academic_term_id', $academicTermId)
            ->select('students.grade_level')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.grade_level')
            ->orderBy('students.grade_level')
            ->get();

        return $gradeData->map(function ($item) {
            return [
                'grade_level' => $item->grade_level,
                'count' => $item->count
            ];
        });
    }

    /**
     * Get program-wise applicant analytics with grade level breakdown
     */
    private function getProgramAnalytics($enrollmentPeriodId)
    {
        // Count applicants by program filtered by enrollment period
        $programData = Applicants::join('programs', 'applicants.program_id', '=', 'programs.id')
            ->where('applicants.enrollment_period_id', $enrollmentPeriodId)
            ->select('programs.id as program_id', 'programs.code as program_code', 'programs.name as program_name')
            ->selectRaw('COUNT(*) as applicant_count')
            ->groupBy('programs.id', 'programs.code', 'programs.name')
            ->orderBy('applicant_count', 'desc')
            ->get();

        return $programData->map(function ($item) use ($enrollmentPeriodId) {
            // Get grade level breakdown for each program
            $gradeBreakdown = $this->getProgramGradeBreakdown($item->program_id, $enrollmentPeriodId);

            return [
                'id' => $item->program_id,
                'code' => $item->program_code,
                'name' => $item->program_name,
                'count' => $item->applicant_count,
                'grade_11' => $gradeBreakdown['grade_11'],
                'grade_12' => $gradeBreakdown['grade_12']
            ];
        });
    }

    /**
     * Get grade level breakdown for a specific program (applicants)
     */
    private function getProgramGradeBreakdown($programId, $enrollmentPeriodId)
    {
        // Count applicants by grade level for the specific program and enrollment period
        // Join with application_forms to get grade_level
        $gradeData = Applicants::where('applicants.program_id', $programId)
            ->where('applicants.enrollment_period_id', $enrollmentPeriodId)
            ->join('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
            ->select('application_forms.grade_level')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('application_forms.grade_level')
            ->get();

        $breakdown = ['grade_11' => 0, 'grade_12' => 0];

        foreach ($gradeData as $grade) {
            if ($grade->grade_level === 'Grade 11') {
                $breakdown['grade_11'] = $grade->count;
            } elseif ($grade->grade_level === 'Grade 12') {
                $breakdown['grade_12'] = $grade->count;
            }
        }

        return $breakdown;
    }

    /**
     * Get grade level-wise applicant analytics
     */
    private function getGradeLevelAnalytics($enrollmentPeriodId)
    {
        // Count applicants by grade level filtered by enrollment period
        // Join with application_forms to get grade_level
        $gradeData = Applicants::where('applicants.enrollment_period_id', $enrollmentPeriodId)
            ->join('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
            ->select('application_forms.grade_level')
            ->selectRaw('COUNT(*) as applicant_count')
            ->groupBy('application_forms.grade_level')
            ->orderBy('application_forms.grade_level')
            ->get();

        return $gradeData->map(function ($item) {
            return [
                'grade_level' => $item->grade_level,
                'count' => $item->applicant_count
            ];
        });
    }

    /**
     * Promote applicant to become an officially enrolled student
     */
    // public function promoteApplicant(Request $request)
    // {

    //     $request->validate([
    //         'action' => 'required|string|in:enroll-student',
    //     ]);

    //     match ($request->action) {
    //         'enroll-student' => $applicants->update(['application_status' => 'Officially Enrolled']),
    //         default => abort(400, 'Invalid action'),
    //     };

    //     return redirect()->back();
    // }

    public function evaluateStudent(Request $request)
    {
        $validated = $request->validate([
            'result' => 'required|in:Passed,Failed,Incomplete'
        ]);

        $student = Student::find($request->id);

        try {
            // Update the student record's academic status
            $student->update([
                'academic_status' => $validated['result']
            ]);

            return redirect()->back()->with('success', 'Successfully evaluated student');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => "Failed to evaluate student: {$th->getMessage()}"]);
        }
    }

    public function promoteStudent(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:promote-to-next-year,mark-as-graduate'
        ]);

        $student = Student::find($request->id);

        try {

            if ($validated['action'] === 'promote-to-next-year') {
                if ($student->academic_status === 'Failed') {
                    return redirect()->back()->withErrors(['error' => 'Failed promote student: The student has been evaluated as Failed.']);
                }

                $student->update([
                    'grade_level' => 'Grade 12'
                ]);
                return redirect()->back()->with('success', 'Successfully promoted student');
            } else if ($validated['action'] === 'mark-as-graduate') {

                if ($student->academic_status === 'Failed') {
                    return redirect()->back()->withErrors(['error' => 'Failed promote student: The student has been evaluated as Failed.']);
                }

                DB::transaction(function () use ($student) {
                    $student->update([
                        'status' => 'Graduated'
                    ]);

                    $student->enrollments()->update([
                        'status' => 'Graduated'
                    ]);
                });

                return redirect()->back()->with('success', 'Successfully mark student as graduated');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => "Failed to promote student: {$th->getMessage()}"]);
        }
    }

    public function withdrawStudent(Request $request)
    {

        $student = Student::find($request->id);

        try {

            DB::transaction(function () use ($student) {
                $student->update([
                    'status' => 'Dropped'
                ]);

                $student->enrollments()->update([
                    'status' => 'Dropped'
                ]);
            });

            return redirect()->back()->with('success', 'Successfully dropped the student');

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => "Failed to drop student: {$th->getMessage()}"]);
        }
    }
}
