<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ScheduleConflictService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    protected $scheduleConflictService;

    public function __construct(ScheduleConflictService $scheduleConflictService)
    {
        $this->scheduleConflictService = $scheduleConflictService;
    }

    public function getSections(Program $program, Request $request)
    {

        //dd($request->all());

        //return response()->json(['ewan' => $request->all()]);

        $query = Section::query()->where('program_id', $program->id);
        //dd($query);

        // search filter
        if ($search = $request->input('search.value')) {
            $query->whereAny(['name', 'year_level', 'room'], 'like', "%{$search}%");
        }

        // // Filtering
        // if ($program = $request->input('program_filter')) {
        //     $query->whereHas('record', fn($q) => $q->where('program', $program));
        // }

        if ($grade = $request->input('grade_filter')) {
            $query->where('year_level', $grade);
        }

        // // Sorting
        // // Column mapping: must match order of your <th> and JS columns
        // $columns = ['lrn', 'first_name', 'grade_level', 'program', 'contact_number', 'email_address'];

        // // Get sort column index and direction
        // $orderColumnIndex = $request->input('order.0.column');
        // $orderDir = $request->input('order.0.dir', 'asc');

        // // Map to actual column name
        // $sortColumn = $columns[$orderColumnIndex] ?? 'id';

        // // Apply sorting
        // $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        // $limit = $request->input('length', 10);  // default to 10 per page
        // $offset = $request->input('start', 0);

        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'name', 'year_level', 'room', 'total_enrolled_students'])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'name' => $item->name,
                    'adviser' => 'Not Assigned',
                    'year_level' => $item->year_level,
                    'room' => $item->room ?? 'Not Assigned',
                    'total_enrolled_students' => $item->enrolled_students_count ?? '-',
                    'id' => $item->id
                ];
            });

        //dd($data);

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function getAllSections(Request $request)
    {
        // Start with all sections, not filtered by program
        $query = Section::with(['teacher.user', 'program', 'enrollments']);

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('year_level', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%")
                  ->orWhereHas('teacher.user', function($teacherQuery) use ($search) {
                      $teacherQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('program', function($programQuery) use ($search) {
                      $programQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        // Program filter
        if ($programFilter = $request->input('program_filter')) {
            $query->whereHas('program', function($q) use ($programFilter) {
                $q->where('code', $programFilter);
            });
        }

        // Grade/Year level filter
        if ($gradeFilter = $request->input('grade_filter')) {
            $query->where('year_level', $gradeFilter);
        }

        // Sorting
        $columns = ['id', 'name', 'program', 'teacher', 'year_level', 'room', 'total_enrolled_students'];
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';

        // Handle special sorting for teacher and program columns
        if ($sortColumn === 'teacher') {
            $query->leftJoin('teachers', 'sections.teacher_id', '=', 'teachers.id')
                  ->leftJoin('users', 'teachers.user_id', '=', 'users.id')
                  ->orderBy('users.last_name', $orderDir)
                  ->orderBy('users.first_name', $orderDir)
                  ->select('sections.*');
        } elseif ($sortColumn === 'program') {
            $query->leftJoin('programs', 'sections.program_id', '=', 'programs.id')
                  ->orderBy('programs.code', $orderDir)
                  ->select('sections.*');
        } else {
            $query->orderBy($sortColumn, $orderDir);
        }

        $total = Section::count();
        $filtered = $query->count();

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($section, $key) use ($start) {
                $adviser = 'Not Assigned';
                
                // Debug: Check if teacher exists and what it contains
                if ($section->teacher_id) {
                    if ($section->teacher && $section->teacher->user) {
                        $adviser = $section->teacher->user->last_name . ', ' . $section->teacher->user->first_name;
                    } else if ($section->teacher) {
                        // Teacher exists but no user relationship
                        $adviser = $section->teacher->first_name . ' ' . $section->teacher->last_name;
                    }
                }

                $programCode = 'Not Set';
                if ($section->program) {
                    $programCode = $section->program->code;
                }

                return [
                    'index' => $start + $key + 1,
                    'name' => $section->name,
                    'program_code' => $programCode,
                    'adviser' => $adviser,
                    'year_level' => $section->year_level ?? 'Not Set',
                    'room' => $section->room ?? 'Not Assigned',
                    'total_enrolled_students' => $section->enrollments->count(),
                    'id' => $section->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    // For Students Table
    public function getStudents(Section $section, Request $request)
    {
        try {
            $query = Student::query()->where('section_id', $section->id);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('lrn', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                        })
                        ->orWhereHas('record', function ($recordQuery) use ($search) {
                            $recordQuery->where('contact_number', 'like', "%{$search}%")
                                ->orWhere('age', 'like', "%{$search}%")
                                ->orWhere('gender', 'like', "%{$search}%");
                        });
                });
            }

            if ($gender = $request->input('gender_filter')) {
                $query->whereHas('record', function ($recordQuery) use ($gender) {
                    $recordQuery->where('gender', $gender);
                });
            }

            // Get total count before applying sorting and pagination
            $total = $query->count();
            $filtered = $total;

            // Sorting
            $columns = ['lrn', 'full_name', 'age', 'gender'];
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'asc');

            if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
                $sortColumn = $columns[$orderColumnIndex];

                switch ($sortColumn) {
                    case 'lrn':
                        $query->orderBy('lrn', $orderDir);
                        break;
                    case 'full_name':
                        $query->leftJoin('users', 'students.user_id', '=', 'users.id')
                            ->orderBy('users.last_name', $orderDir)
                            ->orderBy('users.first_name', $orderDir)
                            ->select('students.*');
                        break;
                    case 'age':
                        $query->leftJoin('student_records', 'students.id', '=', 'student_records.student_id')
                            ->orderBy('student_records.age', $orderDir)
                            ->select('students.*');
                        break;
                    case 'gender':
                        $query->leftJoin('student_records', 'students.id', '=', 'student_records.student_id')
                            ->orderBy('student_records.gender', $orderDir)
                            ->select('students.*');
                        break;
                    default:
                        $query->orderBy('id', $orderDir);
                        break;
                }
            } else {
                $query->orderBy('id', 'asc');
            }

            $start = $request->input('start', 0);

            $data = $query
                ->with(['user', 'record'])
                ->offset($start)
                ->limit($request->length)
                ->get(['id', 'user_id', 'lrn', 'grade_level', 'program_id'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'lrn' => $item->lrn,
                        'full_name' => ($item->user?->last_name ?? '') . ', ' . ($item->user?->first_name ?? ''),
                        'age' => $item->record?->age ?? '-',
                        'gender' => $item->record?->gender ?? '-',
                        'contact_number' => $item->record?->contact_number ?? '-',
                        'id' => $item->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while fetching students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $sections = Section::all();
        $programs = Program::all();

        return view('user-admin.curriculum.section.index', compact('sections', 'programs'));
    }

    public function create()
    {
        // If using API, you may not need this
        return response()->json(['message' => 'Show form to create program']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_code' => 'required|string|max:255',
            'program_id'   => 'required|exists:programs,id',
            'year_level'   => 'required|string',
            'room'         => 'nullable|string|max:50',
            'adviser_id'   => 'nullable|exists:teachers,id',
            'auto_assign'  => 'nullable|in:on',
            'subjects'     => 'nullable|array'
        ]);

        try {
            $section = DB::transaction(function () use ($validated) {
                $section = Section::firstOrCreate(
                    [
                        'name'       => $validated['section_code'],
                        'program_id' => $validated['program_id']
                    ],
                    [
                        'teacher_id' => $validated['adviser_id'] ?? null,
                        'year_level' => $validated['year_level'],
                        'room'       => $validated['room'] ?? null,
                    ]
                );

                if (!empty($validated['subjects'])) {
                    foreach ($validated['subjects'] as $subject) {
                        $section->sectionSubjects()->firstOrCreate(
                            ['subject_id' => $subject],
                            [
                                'teacher'      => null,
                                'room'         => $validated['room'] ?? null,
                                'days_of_week' => null,
                                'start_time'   => null,
                                'end_time'     => null,
                            ]
                        );
                    }
                }

                return $section;
            });

            return response()->json([
                'success' => true,
                'message' => 'Section successfully created.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Section $section)
    {
        $year_level = $section->year_level;
        $program = $section->program->code;

        $students = Student::where('grade_level', $year_level)
            ->where('program_id', $section->program_id)
            ->where('section_id', null)
            ->with('user')
            ->get();

        // Get teachers filtered by the section's program ID
        $teachers = \App\Models\Teacher::where('status', 'active')
            ->with('user')
            ->get(['id', 'first_name', 'last_name', 'program_id']);

        // Load section subjects with their details
        $section->load([
            'sectionSubjects.subject',
            'sectionSubjects.teacher.user',
            'teacher.user',
            'program'
        ]);

        return view('user-admin.curriculum.section.show', compact('section', 'students', 'teachers'));
    }

    // Students who doesn't have a section yet (filtered by year level and program)
    public function getAvailableStudents(Section $section)
    {
        $year_level = $section->year_level;
        $program = $section->program->code;

        $students = Student::where('grade_level', $year_level)
            ->where('program_id', $section->program_id)
            ->where('section_id', null)
            ->with('user')
            ->get();

        return response()->json([
            'students' => $students
        ]);
    }

    public function getAvailableSubjects(Section $section)
    {
        // Get the current active academic term to determine the semester
        $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();

        if (!$activeTerm) {
            return response()->json([
                'subjects' => [],
                'error' => 'No active academic term found'
            ]);
        }

        // Get subjects that are not already assigned to this section
        $assignedSubjectIds = $section->sectionSubjects()->pluck('subject_id');

        $subjects = Subject::where('program_id', $section->program_id)
            ->where('grade_level', $section->year_level)
            ->where('semester', $activeTerm->semester) // Filter by current semester
            ->whereNotIn('id', $assignedSubjectIds)
            ->get(['id', 'name', 'category', 'semester']);

        return response()->json([
            'subjects' => $subjects,
            'current_semester' => $activeTerm->semester
        ]);
    }


    public function getSectionSubject($sectionSubjectId)
    {
        try {
            $sectionSubject = \App\Models\SectionSubject::with(['subject', 'teacher.user'])
                ->find($sectionSubjectId);

            if (!$sectionSubject) {
                return response()->json([
                    'error' => 'Section subject not found'
                ], 404);
            }

            return response()->json([
                'subject_id' => $sectionSubject->subject_id,
                'subject_name' => $sectionSubject->subject->name,
                'teacher_id' => $sectionSubject->teacher_id,
                'teacher_name' => $sectionSubject->teacher ? $sectionSubject->teacher->getFullNameAttribute() : null,
                'room' => $sectionSubject->room,
                'days_of_week' => $sectionSubject->days_of_week,
                'start_time' => $sectionSubject->start_time,
                'end_time' => $sectionSubject->end_time,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch section subject data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkScheduleConflict(Request $request, Section $section)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'section_subject_id' => 'nullable|exists:section_subjects,id',
        ]);

        // Validate schedule data
        $validationErrors = $this->scheduleConflictService->validateScheduleData($validated);
        if (!empty($validationErrors)) {
            return response()->json([
                'has_conflicts' => true,
                'conflicts' => array_map(fn($error) => ['type' => 'validation_error', 'message' => $error], $validationErrors),
                'suggestions' => []
            ], 422);
        }

        // Check for conflicts using the service
        $excludeId = $validated['section_subject_id'] ?? null;
        $result = $this->scheduleConflictService->checkConflicts($section, $validated, $excludeId);

        return response()->json($result);
    }


    public function assignSubject(Request $request, Section $section)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:100',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        // Validate schedule data
        $validationErrors = $this->scheduleConflictService->validateScheduleData($validated);
        if (!empty($validationErrors)) {
            return response()->json([
                'error' => 'Validation errors: ' . implode('; ', $validationErrors)
            ], 422);
        }

        // Check for conflicts using the service
        $conflictResult = $this->scheduleConflictService->checkConflicts($section, $validated);

        if ($conflictResult['has_conflicts']) {
            $conflictMessages = array_column($conflictResult['conflicts'], 'message');
            return response()->json([
                'error' => 'Schedule conflicts detected: ' . implode('; ', $conflictMessages)
            ], 422);
        }

        try {
            $sectionSubject = $section->sectionSubjects()->create($validated);

            return response()->json([
                'success' => 'Subject assigned successfully',
                'section_subject' => $sectionSubject->load(['subject', 'teacher'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to assign subject: ' . $e->getMessage()
            ], 500);
        }
    }


    public function updateSubject(Request $request, Section $section)
    {
        $validated = $request->validate([
            'section_subject_id' => 'required|exists:section_subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:100',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        // Get the section subject to update
        $sectionSubject = \App\Models\SectionSubject::find($validated['section_subject_id']);
        if (!$sectionSubject) {
            return response()->json([
                'error' => 'Section subject not found'
            ], 404);
        }

        // Remove section_subject_id from validated data as it's not a field to update
        unset($validated['section_subject_id']);

        // Validate schedule data
        $validationErrors = $this->scheduleConflictService->validateScheduleData($validated);
        if (!empty($validationErrors)) {
            return response()->json([
                'error' => 'Validation errors: ' . implode('; ', $validationErrors)
            ], 422);
        }

        // Check for conflicts using the service (exclude current record)
        $conflictResult = $this->scheduleConflictService->checkConflicts($section, $validated, $sectionSubject->id);

        if ($conflictResult['has_conflicts']) {
            $conflictMessages = array_column($conflictResult['conflicts'], 'message');
            return response()->json([
                'error' => 'Schedule conflicts detected: ' . implode('; ', $conflictMessages)
            ], 422);
        }

        try {
            $sectionSubject->update($validated);

            return response()->json([
                'success' => 'Subject updated successfully',
                'section_subject' => $sectionSubject->load(['subject', 'teacher'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update subject: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeSubjectFromSection(Request $request, Section $section)
    {
        $validated = $request->validate([
            'section_subject_id' => 'required|exists:section_subjects,id'
        ]);

        try {
            DB::transaction(function () use ($validated, $section) {
                $sectionSubject = \App\Models\SectionSubject::find($validated['section_subject_id']);

                if (!$sectionSubject || $sectionSubject->section_id !== $section->id) {
                    throw new \Exception('Section subject not found or does not belong to this section');
                }

                // Remove all student enrollments for this subject
                \App\Models\StudentSubject::where('section_subject_id', $sectionSubject->id)->delete();

                // Delete the section subject
                $sectionSubject->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Subject successfully removed from section'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subject from section: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatTime($time)
    {
        return date('g:i A', strtotime($time));
    }

    public function edit(Section $section)
    {



        return response()->json(['message' => 'Show form to edit program', 'section' => $section]);
    }

    public function update(Request $request, Section $section)
    {

        try {

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'room' => 'nullable|string|max:50',
                'teacher_id' => 'nullable|exists:teachers,id',
            ]);

            $section->update($validated);
            
            // Reload the section with teacher relationship
            $section->load('teacher.user');

            $newSectionName = $section->name;
            $newRoom = $section->room;
            $newTeacher = $section->teacher ? $section->teacher->getFullNameAttribute() : 'Not assigned';

            return response()->json([
                'success' => 'Section successfully updated',
                'newData' => [
                    'newSectionName' => $newSectionName,
                    'newRoom' => $newRoom,
                    'newTeacher' => $newTeacher
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ]);
        }

        return response()->json($section);
    }

    public function destroy(Section $section, Request $request)
    {
        try {
            DB::transaction(function () use ($section) {
                // Get all students in this section
                $students = $section->students;

                // Remove all students from this section
                foreach ($students as $student) {
                    // Update Student model (for admin panel compatibility)
                    $student->update(['section_id' => null]);

                    // Update StudentEnrollment model (for mobile app API)
                    $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                    if ($activeTerm) {
                        \App\Models\StudentEnrollment::where('student_id', $student->id)
                            ->where('academic_term_id', $activeTerm->id)
                            ->update(['section_id' => null]);
                    }
                }

                // Get all section subjects
                $sectionSubjects = $section->sectionSubjects;

                // Remove all student enrollments for each subject
                foreach ($sectionSubjects as $sectionSubject) {
                    \App\Models\StudentSubject::where('section_subject_id', $sectionSubject->id)->delete();
                }

                // Delete all section subjects
                $section->sectionSubjects()->delete();

                // Finally, delete the section itself
                $section->delete();
            });

            // Determine redirect URL based on referrer or program
            $redirectUrl = '/tracks'; // Default fallback (replaced /programs)

            if ($request->has('redirect_to')) {
                $redirectUrl = $request->input('redirect_to');
            } elseif ($request->header('referer')) {
                $referer = $request->header('referer');
                // If coming from a program page, redirect back to that program
                if (strpos($referer, '/programs/') !== false) {
                    $redirectUrl = $referer;
                }
                // If coming from tracks page, redirect back to tracks
                elseif (strpos($referer, '/tracks') !== false) {
                    $redirectUrl = $referer;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully',
                'redirect_url' => $redirectUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section: ' . $e->getMessage()
            ], 500);
        }
    }
}
