<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\StudentSubject;
use App\Models\Student;
use Carbon\Carbon;
    use App\Services\AcademicTermService;
    use App\Notifications\PrivateImmediateNotification;
    use App\Notifications\PrivateQueuedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeacherManagementController extends Controller
{
    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $teachers = Teacher::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user-admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('user-admin.teachers.create');
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(), 'confirmed'],
            'contact_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => Teacher::generateEmployeeId(),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email_address' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'sections', 'sectionSubjects']);
        return view('user-admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('user-admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'contact_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $teacher->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);

            // Update teacher record
            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email_address' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Delete teacher record
            $teacher->delete();

            // Delete user account
            $teacher->user->delete();

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    /**
     * Toggle teacher status.
     */
    public function toggleStatus(Teacher $teacher)
    {
        try {
            $newStatus = $teacher->status === 'active' ? 'inactive' : 'active';
            $teacher->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher status updated successfully.',
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show teacher dashboard with their sections.
     */
    public function dashboard()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        // Get sections where teacher is adviser
        $advisedSections = $teacher->sections()->with(['program', 'enrollments'])->get();
        
        // Get sections where teacher teaches subjects
        $teachingSections = Section::whereHas('sectionSubjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['program', 'enrollments'])->get();

        // Combine and deduplicate sections
        $allSections = $advisedSections->merge($teachingSections)->unique('id');
        
        // Calculate analytics
        $totalSections = $allSections->count();
        $totalStudents = $allSections->sum(function($section) {
            return $section->enrollments->count();
        });
        $advisedSectionsCount = $advisedSections->count();
        $teachingSectionsCount = $teachingSections->count();

        // Get current academic term data
        $academicTermService = app(AcademicTermService::class);
        $academicTermData = $academicTermService->getCurrentAcademicTermData();

        // Get all programs for the filter
        $programs = \App\Models\Program::where('status', 'active')->get();

        // Note: Dashboard UI now lists subjects (not sections); counts retained for existing stats card usage
        return view('user-teacher.dashboard', compact(
            'teacher', 
            'allSections', 
            'advisedSections', 
            'teachingSections',
            'totalSections',
            'totalStudents',
            'advisedSectionsCount',
            'teachingSectionsCount',
            'programs',
            'academicTermData'
        ));
    }

    /**
     * Get teacher's sections data for DataTables (AJAX).
     */
    public function getTeacherSections(Request $request)
    {
        try {
            $teacher = Auth::user()->teacher;
            
            if (!$teacher) {
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Teacher profile not found'
                ], 404);
            }

            // Get sections where teacher is adviser
            $advisedSections = $teacher->sections()->with(['program', 'enrollments']);
            
            // Get sections where teacher teaches subjects
            $teachingSections = Section::whereHas('sectionSubjects', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->with(['program', 'enrollments']);

            // Combine queries using union
            $query = $advisedSections->union($teachingSections);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('year_level', 'like', "%{$search}%")
                      ->orWhere('room', 'like', "%{$search}%")
                      ->orWhereHas('program', function($programQuery) use ($search) {
                          $programQuery->where('name', 'like', "%{$search}%")
                                     ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->where('year_level', $grade);
            }

            // Program filter
            if ($programFilter = $request->input('program_filter')) {
                $query->whereHas('program', function($q) use ($programFilter) {
                    $q->where('code', $programFilter);
                });
            }

            $total = $query->count();
            $filtered = $total;

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($section, $key) use ($start, $teacher) {
                    $isAdviser = $section->teacher_id === $teacher->id;
                    $studentCount = $section->enrollments->count();
                    
                    return [
                        'index' => $start + $key + 1,
                        'name' => $section->name,
                        'program' => $section->program->name ?? 'N/A',
                        'year_level' => $section->year_level,
                        'room' => $section->room ?? 'Not Assigned',
                        'total_students' => $studentCount,
                        'role' => $isAdviser ? 'Adviser' : null, // Only show role if adviser
                        'is_adviser' => $isAdviser,
                        'id' => $section->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getTeacherSections error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load sections data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher's subjects (section_subjects) for DataTables / cards.
     */
    public function getTeacherSubjects(Request $request)
    {
        try {
            $teacher = Auth::user()->teacher;

            if (!$teacher) {
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Teacher profile not found'
                ], 404);
            }

            $query = SectionSubject::with(['subject', 'section.program'])
                ->where('teacher_id', $teacher->id);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('subject', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('section', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                           ->orWhere('year_level', 'like', "%{$search}%")
                           ->orWhere('room', 'like', "%{$search}%")
                           ->orWhereHas('program', function ($pq) use ($search) {
                               $pq->where('name', 'like', "%{$search}%")
                                  ->orWhere('code', 'like', "%{$search}%");
                           });
                    });
                });
            }

            if ($programFilter = $request->input('program_filter')) {
                $query->whereHas('section.program', function ($q) use ($programFilter) {
                    $q->where('code', $programFilter);
                });
            }

            if ($grade = $request->input('grade_filter')) {
                $query->whereHas('section', function ($q) use ($grade) {
                    $q->where('year_level', $grade);
                });
            }

            $total = $query->count();
            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($sectionSubject, $key) use ($start) {
                    $section = $sectionSubject->section;
                    $program = $section?->program;

                    $days = $sectionSubject->days_of_week;
                    $daysText = is_array($days) ? implode(', ', $days) : ($days ?: '-');
                    $schedule = $daysText;
                    if ($sectionSubject->start_time && $sectionSubject->end_time) {
                        $startTime = \Carbon\Carbon::parse($sectionSubject->start_time)->format('g:i A');
                        $endTime = \Carbon\Carbon::parse($sectionSubject->end_time)->format('g:i A');
                        $schedule = trim(($daysText ? $daysText . ' ' : '') . $startTime . ' - ' . $endTime);
                    }

                    $studentCount = StudentSubject::where('section_subject_id', $sectionSubject->id)->count();

                    return [
                        'index' => $start + $key + 1,
                        'id' => $sectionSubject->id,
                        'subject' => $sectionSubject->subject->name ?? 'N/A',
                        'section' => $section?->name ?? 'N/A',
                        'program' => $program->name ?? 'N/A',
                        'year_level' => $section?->year_level ?? 'N/A',
                        'room' => $sectionSubject->room ?? ($section?->room ?? 'N/A'),
                        'schedule' => $schedule ?: 'N/A',
                        'students' => $studentCount,
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subject-centric page for teacher.
     */
    public function showSubject($id)
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        $sectionSubject = SectionSubject::with(['subject', 'section.program', 'teacher.user'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($id);

        $studentCount = StudentSubject::where('section_subject_id', $sectionSubject->id)->count();

        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        return view('user-teacher.subject.show', [
            'sectionSubject' => $sectionSubject,
            'studentCount' => $studentCount,
            'teacher' => $teacher,
            'activeTerm' => $activeTerm,
        ]);
    }

    /**
     * Students under a subject (AJAX for DataTable).
     */
    public function getSubjectStudents(Request $request, $id)
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Teacher profile not found'
            ], 404);
        }

        $sectionSubject = SectionSubject::where('teacher_id', $teacher->id)->findOrFail($id);

        $query = StudentSubject::with(['student.user'])
            ->where('section_subject_id', $sectionSubject->id);

        if ($search = $request->input('search.value')) {
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where(DB::raw("concat_ws(' ', first_name, last_name)"), 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get()
            ->map(function ($studentSubject, $key) use ($start) {
                $user = $studentSubject->student?->user;
                return [
                    'index' => $start + $key + 1,
                    'id' => $studentSubject->student_id,
                    'student_subject_id' => $studentSubject->id,
                    'lrn' => $studentSubject->student?->lrn,
                    'name' => $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown',
                    'evaluation_status' => $studentSubject->evaluation_status,
                    'remedial_status' => $studentSubject->remedial_status,
                    'remedial_deadline' => $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->toDateString() : null,
                    'remedial_deadline_display' => $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->format('M d, Y') : null,
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    /**
     * Update evaluation/remedial status for a student in a subject.
     */
    public function updateSubjectStudentEvaluation(Request $request, $id, $studentId)
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher profile not found'], 404);
        }

        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        if (!$activeTerm || $activeTerm->status !== 'Closing') {
            return response()->json([
                'success' => false,
                'message' => 'Student evaluations are only allowed when the current academic term is set to Closing.',
            ], 403);
        }

        $request->validate([
            'evaluation_status' => 'nullable|in:passed,failed,pending',
            'remedial_status' => 'nullable|in:cleared,failed,pending',
            'remedial_deadline' => 'nullable|date',
        ]);

        $sectionSubject = SectionSubject::where('teacher_id', $teacher->id)->findOrFail($id);

        $studentSubject = StudentSubject::where('section_subject_id', $sectionSubject->id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $evaluation = $request->input('evaluation_status');
        $studentSubject->evaluation_status = $evaluation;
        $studentSubject->remedial_status = $request->input('remedial_status');

        // Auto-assign a 30-day remedial deadline when evaluation is failed and no deadline provided yet
        if ($evaluation === 'failed' && !$studentSubject->remedial_deadline) {
            $studentSubject->remedial_deadline = Carbon::now()->addDays(30);
            // Set remedial_status to null (pending) if not set
            if (!$studentSubject->remedial_status) {
                $studentSubject->remedial_status = null;
            }
        } else {
            $studentSubject->remedial_deadline = $request->input('remedial_deadline') ? Carbon::parse($request->input('remedial_deadline')) : null;
        }
        $studentSubject->save();

        if (in_array($evaluation, ['passed', 'failed'], true)) {
            $studentUser = $studentSubject->student?->user;
            $subjectName = $sectionSubject->subject->name ?? 'Subject';
            $sectionName = $sectionSubject->section->name ?? 'Section';
            $statusLabel = ucfirst($evaluation);

            if ($studentUser) {
                $sharedId = 'student-eval-' . $studentSubject->id . '-' . uniqid();
                $studentUser->notify(new PrivateQueuedNotification(
                    'Subject Evaluation Result',
                    "Your {$subjectName} evaluation for {$sectionName} has been marked as {$statusLabel}.",
                    url('/student/grades'),
                    $sharedId
                ));

                $studentUser->notify(new PrivateImmediateNotification(
                    'Subject Evaluation Result',
                    "Your {$subjectName} evaluation for {$sectionName} has been marked as {$statusLabel}.",
                    url('/student/grades'),
                    $sharedId
                ));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Student evaluation updated',
        ]);
    }

    /**
     * Teaching history page (web).
     */
    public function teachingHistory()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        return view('user-teacher.history', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Teaching history data (grouped by subject/section/term).
     */
    public function getTeachingHistoryData(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher profile not found.',
                'data' => [],
            ], 404);
        }

        $studentSubjects = StudentSubject::with(['student.user', 'subject', 'sectionSubject.section', 'academicTerm'])
            ->where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)
                    ->orWhereHas('sectionSubject', function ($sub) use ($teacher) {
                        $sub->where('teacher_id', $teacher->id);
                    });
            })
            ->get();

        $grouped = $studentSubjects->groupBy(function ($item) {
            $subjectId = $item->subject_id ?? 'none';
            $sectionName = $item->sectionSubject->section->name ?? 'No Section';
            $termName = $item->academicTerm->full_name ?? 'No Term';
            return $subjectId . '|' . $sectionName . '|' . $termName;
        })->map(function ($items) {
            $first = $items->first();
            return [
                'subject_id' => $first->subject_id,
                'subject_name' => $first->subject->name ?? 'Unknown Subject',
                'section_name' => $first->sectionSubject->section->name ?? null,
                'academic_term' => $first->academicTerm->full_name ?? null,
                'students' => $items->map(function ($studentSubject) {
                    $student = $studentSubject->student;
                    return [
                        'student_subject_id' => $studentSubject->id,
                        'student_id' => $student->id,
                        'name' => $student->full_name,
                        'lrn' => $student->lrn,
                        'evaluation_status' => $studentSubject->evaluation_status,
                        'remedial_status' => $studentSubject->remedial_status,
                        'remedial_deadline' => $studentSubject->remedial_deadline,
                        'remedial_deadline_display' => $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->format('M d, Y') : null,
                        'is_remedial_status_finalized' => (bool) $studentSubject->is_remedial_status_finalized,
                        'subject_name' => $studentSubject->subject->name ?? 'Unknown Subject',
                        'section_name' => $studentSubject->sectionSubject->section->name ?? null,
                        'academic_term' => $studentSubject->academicTerm->full_name ?? null,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    /**
     * Get a single student subject record for history view.
     */
    public function getStudentSubjectHistory(int $studentSubjectId)
    {
        $teacher = Auth::user()->teacher;

        $studentSubject = StudentSubject::with(['student.user', 'subject', 'sectionSubject.section', 'academicTerm'])
            ->find($studentSubjectId);

        if (!$studentSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        $isAuthorized = ($studentSubject->teacher_id === $teacher->id)
            || ($studentSubject->sectionSubject && $studentSubject->sectionSubject->teacher_id === $teacher->id);

        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this record.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->mapStudentSubjectHistory($studentSubject),
        ]);
    }

    /**
     * Update remedial status for a student_subject (web).
     */
    public function updateStudentSubjectRemedial(Request $request, int $studentSubjectId)
    {
        $request->validate([
            'status' => 'required|in:failed,cleared',
        ]);

        $teacher = Auth::user()->teacher;

        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        if (!$activeTerm || $activeTerm->status !== 'Closing') {
            return response()->json([
                'success' => false,
                'message' => 'Remedial updates are only allowed when the current academic term is set to Closing.',
            ], 403);
        }

        $studentSubject = StudentSubject::with(['student.user', 'subject', 'sectionSubject', 'academicTerm'])
            ->find($studentSubjectId);

        if (!$studentSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        $isAuthorized = ($studentSubject->teacher_id === $teacher->id)
            || ($studentSubject->sectionSubject && $studentSubject->sectionSubject->teacher_id === $teacher->id);

        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this record.',
            ], 403);
        }

        if ($studentSubject->evaluation_status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Remedial status can only be updated when evaluation status is failed.',
            ], 422);
        }

        if ($studentSubject->is_remedial_status_finalized) {
            return response()->json([
                'success' => false,
                'message' => 'Remedial status is already finalized.',
            ], 422);
        }

        $status = $request->status;
        $studentSubject->remedial_status = $status;

        if ($status === 'failed' && !$studentSubject->remedial_deadline) {
            $studentSubject->remedial_deadline = Carbon::now()->addDays(30);
        }

        $studentSubject->is_remedial_status_finalized = true;
        $studentSubject->finalized_at = Carbon::now();
        $studentSubject->save();

        return response()->json([
            'success' => true,
            'data' => $this->mapStudentSubjectHistory($studentSubject),
        ]);
    }

    /**
     * Map student_subject for history payload.
     */
    private function mapStudentSubjectHistory(StudentSubject $studentSubject): array
    {
        $student = $studentSubject->student;

        return [
            'student_subject_id' => $studentSubject->id,
            'student_id' => $student->id,
            'student_name' => $student->full_name,
            'lrn' => $student->lrn,
            'subject_name' => $studentSubject->subject->name ?? 'Unknown Subject',
            'section_name' => $studentSubject->sectionSubject->section->name ?? null,
            'academic_term' => $studentSubject->academicTerm->full_name ?? null,
            'evaluation_status' => $studentSubject->evaluation_status,
            'remedial_status' => $studentSubject->remedial_status,
            'remedial_deadline' => $studentSubject->remedial_deadline,
            'remedial_deadline_display' => $studentSubject->remedial_deadline ? $studentSubject->remedial_deadline->format('M d, Y') : null,
            'is_remedial_status_finalized' => (bool) $studentSubject->is_remedial_status_finalized,
            'finalized_at' => $studentSubject->finalized_at,
        ];
    }

    /**
     * Show a specific section for teachers (read-only view).
     */
    public function showSection(Section $section)
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        // Check if teacher has access to this section (either as adviser or teaches subjects)
        $isAdviser = $section->teacher_id === $teacher->id;
        $teachesSubjects = $section->sectionSubjects()->where('teacher_id', $teacher->id)->exists();
        
        if (!$isAdviser && !$teachesSubjects) {
            return redirect()->route('teacher.dashboard')->with('error', 'You do not have access to this section.');
        }

        // Load necessary relationships
        $section->load(['program', 'teacher.user', 'sectionSubjects.subject', 'sectionSubjects.teacher.user', 'enrollments.student']);
        
        return view('user-teacher.section.show', compact('section', 'teacher', 'isAdviser'));
    }

    /**
     * Get teachers data for DataTables (AJAX).
     */
    public function getTeachers(Request $request)
    {
        try {
            $query = Teacher::with(['user']);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%")
                      ->orWhere('specialization', 'like', "%{$search}%")
                      ->orWhere('email_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('email', 'like', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                $query->where('status', $status);
            }

            // Specialization filter
            if ($specialization = $request->input('specialization_filter')) {
                $query->where('specialization', 'like', "%{$specialization}%");
            }

            $total = $query->count();
            $filtered = $total;

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($teacher, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'employee_id' => $teacher->employee_id,
                        'full_name' => $teacher->getFullNameAttribute(),
                        'email' => $teacher->user ? $teacher->user->email : $teacher->email_address,
                        'specialization' => $teacher->specialization ?? 'Not specified',
                        'status' => $teacher->status,
                        'id' => $teacher->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getTeachers error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load teachers data: ' . $e->getMessage()
            ], 500);
        }
    }
}