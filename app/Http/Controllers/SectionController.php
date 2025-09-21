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
    // For Students Table
    public function getStudents(Section $section, Request $request)
    {
        try {
            $query = Student::query()->where('section_id', $section->id);

            // Search filter
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

            if ($gender = $request->input('gender_filter')) {
                $query->whereHas('record', function ($recordQuery) use ($gender) {
                    $recordQuery->where('gender', $gender);
                });
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
                ->get(['id', 'user_id', 'lrn', 'grade_level', 'program'])
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

        return view('user-admin.section.index', compact('sections'));
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
                        'adviser_id' => $validated['adviser_id'] ?? null,
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

            return response()->json($section, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Section $section)
    {
        $year_level = $section->year_level;
        $program = $section->program->code;

        $students = Student::where('grade_level', $year_level)
            ->where('program', $program)
            ->where('section_id', null)
            ->with('user')
            ->get();

        // Load section subjects with their details
        $section->load([
            'sectionSubjects.subject',
            'sectionSubjects.teacher',
            'program'
        ]);

        return view('user-admin.section.show', compact('section', 'students'));
    }

    // Students who doesn't have a section yet (filtered by year level and program)
    public function getAvailableStudents(Section $section)
    {
        $year_level = $section->year_level;
        $program = $section->program->code;

        $students = Student::where('grade_level', $year_level)
            ->where('program', $program)
            ->where('section_id', null)
            ->with('user')
            ->get();

        return response()->json([
            'students' => $students
        ]);
    }

    public function getAvailableSubjects(Section $section)
    {
        // Get subjects that are not already assigned to this section
        $assignedSubjectIds = $section->sectionSubjects()->pluck('subject_id');
        
        $subjects = Subject::where('program_id', $section->program_id)
            ->where('grade_level', $section->year_level)
            ->whereNotIn('id', $assignedSubjectIds)
            ->get(['id', 'name', 'category']);

        return response()->json([
            'subjects' => $subjects
        ]);
    }

    public function getTeachers()
    {
        $teachers = \App\Models\Teacher::where('status', 'active')
            ->get(['id', 'first_name', 'last_name']);

        return response()->json([
            'teachers' => $teachers
        ]);
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
        $excludeId = $request->input('section_subject_id', null);
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
                'name' => 'required|string|max:255',
                'room' => 'nullable|string|max:50',
            ]);

            $section->update($validated);

            $newSectionName = $section->name;
            $newRoom = $section->room;

            return response()->json([
                'success' => 'Section successfully updated',
                'newData' => ['newSectionName' => $newSectionName, 'newRoom' => $newRoom]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ]);
        }

        return response()->json($section);
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }
}
