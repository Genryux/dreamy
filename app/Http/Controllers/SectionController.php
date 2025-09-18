<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{

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

        $conflicts = [];


        // 1. SECTION-LEVEL CONFLICT CHECK
        // A section cannot have overlapping subjects at the same time (regardless of room or teacher)
        if (!empty($validated['start_time']) && !empty($validated['end_time'])) {
            $sectionConflicts = $section->sectionSubjects()
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with('subject')
                ->get();


            foreach ($sectionConflicts as $existing) {
                if ($this->hasTimeConflict($validated, $existing)) {
                    $conflicts[] = [
                        'type' => 'section_conflict',
                        'message' => "Section {$section->name} already has {$existing->subject->name} scheduled at " . $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time),
                        'subject' => $existing->subject->name,
                        'section' => $section->name,
                        'time' => $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time)
                    ];
                }
            }
        }

        // 2. ROOM-LEVEL CONFLICT CHECK
        // If two different sections want to use the same room at the same time → conflict
        if (!empty($validated['room']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            $roomConflicts = \App\Models\SectionSubject::where('room', $validated['room'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with(['subject', 'section'])
                ->get();


            foreach ($roomConflicts as $existing) {
                if ($this->hasTimeConflict($validated, $existing)) {
                    $conflicts[] = [
                        'type' => 'room_conflict',
                        'message' => "Room {$validated['room']} is already booked for {$existing->subject->name} in {$existing->section->name} (" . $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time) . ")",
                        'subject' => $existing->subject->name,
                        'section' => $existing->section->name,
                        'room' => $validated['room'],
                        'time' => $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time)
                    ];
                }
            }
        }

        // 3. TEACHER-LEVEL CONFLICT CHECK
        // If the same teacher is assigned to two different sections/subjects at the same time → conflict
        if (!empty($validated['teacher_id']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            $teacherConflicts = \App\Models\SectionSubject::where('teacher_id', $validated['teacher_id'])
                ->where('id', '!=', $request->input('section_subject_id', 0)) // Exclude current subject if editing
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with(['subject', 'section'])
                ->get();


            foreach ($teacherConflicts as $conflict) {
                if ($this->hasTimeConflict($validated, $conflict)) {
                    $conflicts[] = [
                        'type' => 'teacher_conflict',
                        'message' => "Teacher is already assigned to {$conflict->subject->name} in {$conflict->section->name} (" . $this->formatTime($conflict->start_time) . " - " . $this->formatTime($conflict->end_time) . ")",
                        'subject' => $conflict->subject->name,
                        'section' => $conflict->section->name,
                        'time' => $this->formatTime($conflict->start_time) . " - " . $this->formatTime($conflict->end_time)
                    ];
                }
            }
        }


        // Generate schedule suggestions if there are conflicts
        $suggestions = [];
        if (!empty($conflicts)) {
            $suggestions = $this->generateScheduleSuggestions($section, $validated);
        }

        return response()->json([
            'has_conflicts' => !empty($conflicts),
            'conflicts' => $conflicts,
            'suggestions' => $suggestions
        ]);
    }

    private function hasTimeConflict($newSchedule, $existingSchedule)
    {
        if (empty($newSchedule['start_time']) || empty($newSchedule['end_time']) || 
            empty($existingSchedule->start_time) || empty($existingSchedule->end_time)) {
            return false;
        }

        // Check if days overlap
        $newDays = $newSchedule['days_of_week'] ?? [];
        $existingDays = $existingSchedule->days_of_week ?? [];
        
        if (empty(array_intersect($newDays, $existingDays))) {
            return false;
        }

        // Check if times overlap
        $newStart = strtotime($newSchedule['start_time']);
        $newEnd = strtotime($newSchedule['end_time']);
        $existingStart = strtotime($existingSchedule->start_time);
        $existingEnd = strtotime($existingSchedule->end_time);

        return ($newStart < $existingEnd && $newEnd > $existingStart);
    }

    private function generateScheduleSuggestions($section, $requestedSchedule)
    {
        $suggestions = [];
        $requestedDays = $requestedSchedule['days_of_week'] ?? [];
        $requestedDuration = 60; // Default 1 hour duration
        
        if (!empty($requestedSchedule['start_time']) && !empty($requestedSchedule['end_time'])) {
            $requestedDuration = (strtotime($requestedSchedule['end_time']) - strtotime($requestedSchedule['start_time'])) / 60;
        }

        // Available time slots (8 AM to 5 PM)
        $timeSlots = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        ];

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // If no specific days requested, suggest all days
        if (empty($requestedDays)) {
            $requestedDays = $daysOfWeek;
        }

        foreach ($requestedDays as $day) {
            foreach ($timeSlots as $startTime) {
                $endTime = date('H:i', strtotime($startTime . ' +' . $requestedDuration . ' minutes'));
                
                // Skip if end time goes beyond 5 PM
                if (strtotime($endTime) > strtotime('17:00')) {
                    continue;
                }

                $testSchedule = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'days_of_week' => [$day],
                    'room' => $requestedSchedule['room'] ?? '',
                    'teacher_id' => $requestedSchedule['teacher_id'] ?? ''
                ];

                // Check if this time slot is available
                if ($this->isTimeSlotAvailable($section, $testSchedule)) {
                    $suggestions[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'display' => "{$day} " . $this->formatTime($startTime) . " - " . $this->formatTime($endTime)
                    ];
                }
            }
        }

        // Limit to 10 suggestions to avoid overwhelming the UI
        return array_slice($suggestions, 0, 10);
    }

    private function isTimeSlotAvailable($section, $testSchedule)
    {
        // Check section-level conflicts
        $sectionConflicts = $section->sectionSubjects()
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        foreach ($sectionConflicts as $existing) {
            if ($this->hasTimeConflict($testSchedule, $existing)) {
                return false;
            }
        }

        // Check room-level conflicts (if room is specified)
        if (!empty($testSchedule['room'])) {
            $roomConflicts = \App\Models\SectionSubject::where('room', $testSchedule['room'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            foreach ($roomConflicts as $existing) {
                if ($this->hasTimeConflict($testSchedule, $existing)) {
                    return false;
                }
            }
        }

        // Check teacher-level conflicts (if teacher is specified)
        if (!empty($testSchedule['teacher_id'])) {
            $teacherConflicts = \App\Models\SectionSubject::where('teacher_id', $testSchedule['teacher_id'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            foreach ($teacherConflicts as $existing) {
                if ($this->hasTimeConflict($testSchedule, $existing)) {
                    return false;
                }
            }
        }

        return true;
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

        // Double-check for conflicts on the server side as a safety measure
        $conflicts = [];

        // 1. SECTION-LEVEL CONFLICT CHECK
        // A section cannot have overlapping subjects at the same time (regardless of room or teacher)
        if (!empty($validated['start_time']) && !empty($validated['end_time'])) {
            $sectionConflicts = $section->sectionSubjects()
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with('subject')
                ->get();

            foreach ($sectionConflicts as $existing) {
                if ($this->hasTimeConflict($validated, $existing)) {
                    $conflicts[] = "Section {$section->name} already has {$existing->subject->name} scheduled at " . $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time);
                }
            }
        }

        // 2. ROOM-LEVEL CONFLICT CHECK
        // If two different sections want to use the same room at the same time → conflict
        if (!empty($validated['room']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            $roomConflicts = \App\Models\SectionSubject::where('room', $validated['room'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with(['subject', 'section'])
                ->get();

            foreach ($roomConflicts as $existing) {
                if ($this->hasTimeConflict($validated, $existing)) {
                    $conflicts[] = "Room {$validated['room']} is already booked for {$existing->subject->name} in {$existing->section->name} (" . $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time) . ")";
                }
            }
        }

        // 3. TEACHER-LEVEL CONFLICT CHECK
        // If the same teacher is assigned to two different sections/subjects at the same time → conflict
        if (!empty($validated['teacher_id']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            $teacherConflicts = \App\Models\SectionSubject::where('teacher_id', $validated['teacher_id'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->with(['subject', 'section'])
                ->get();

            foreach ($teacherConflicts as $conflict) {
                if ($this->hasTimeConflict($validated, $conflict)) {
                    $conflicts[] = "Teacher is already assigned to {$conflict->subject->name} in {$conflict->section->name} (" . $this->formatTime($conflict->start_time) . " - " . $this->formatTime($conflict->end_time) . ")";
                }
            }
        }

        if (!empty($conflicts)) {
            return response()->json([
                'error' => 'Schedule conflicts detected: ' . implode('; ', $conflicts)
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
