<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Subject;
use App\Services\AcademicTermService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function __construct(
        protected AcademicTermService $academic_term_service
    ) {}

    public function getAutoAssignSubjects(Request $request)
    {
        $termData = $this->academic_term_service->getCurrentAcademicTermData();

        $programId = $request->get('program_id');
        $yearLevel = $request->get('year_level');

        // Get current semester
        $currentSemester = $termData['semester'];

        // Get all core subjects (fixed for every program)
        $coreSubjects = Subject::where('category', 'core')
            ->where('grade_level', $yearLevel)
            ->where('semester', $currentSemester)
            ->get();

        // Get applied subjects filtered by program
        $appliedSubjects = Subject::where('category', 'applied')
            ->where('program_id', $programId)
            ->where('grade_level', $yearLevel)
            ->where('semester', $currentSemester)
            ->get();

        // Combine core and applied subjects
        $subjects = $coreSubjects->concat($appliedSubjects);

        return response()->json(['subjects' => $subjects]);
    }

    public function getSubjects(Program $program, Request $request)
    {

        //dd($request->all());

        //return response()->json(['ewan' => $request->all()]);

        $query = Subject::query()->where('program_id', $program->id);

        // search filter
        if ($search = $request->input('search.value')) {
            $query->whereAny(['name', 'category', 'grade_level', 'semester'], 'like', "%{$search}%");
        }

        // Category filter
        if ($category = $request->input('category_filter')) {
            $query->where('category', strtolower($category));
        }

        // Year level filter
        if ($grade = $request->input('grade_filter')) {
            $query->where('grade_level', $grade);
        }

        // Semester filter - only filter if explicitly specified
        if ($semester = $request->input('semester_filter')) {
            $query->where('semester', $semester);
        }

        // Sorting
        // Column mapping: must match order of your <th> and JS columns
        // Index 0: index (not sortable), Index 1: name, Index 2: category, Index 3: year_level, Index 4: semester, Index 5: actions (not sortable)
        $columns = ['id', 'name', 'category', 'grade_level', 'semester'];

        // Get sort column index and direction
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        // Map to actual column name (skip index column 0 and actions column 5)
        if ($orderColumnIndex >= 1 && $orderColumnIndex <= 4) {
            $sortColumn = $columns[$orderColumnIndex] ?? 'id';
            $query->orderBy($sortColumn, $orderDir);
        } else {
            // Default sorting by id if invalid column index
            $query->orderBy('id', 'desc');
        }

        $total = $query->count();
        $filtered = $total;

        // $limit = $request->input('length', 10);  // default to 10 per page
        // $offset = $request->input('start', 0);

        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'name', 'category', 'grade_level', 'semester'])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'name' => $item->name ?? '-',
                    'category' => $item->category ?? '-',
                    'year_level' => $item->grade_level ?? '-',
                    'semester' => $item->semester ?? '-',
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

    public function index()
    {
        $programs = \App\Models\Program::where('status', 'active')->get();
        return view('user-admin.curriculum.subject.index', compact('programs'));
    }

    public function getAllSubjects(Request $request)
    {
        $query = Subject::with(['program']);

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%")
                  ->orWhere('semester', 'like', "%{$search}%")
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

        // Grade filter
        if ($gradeFilter = $request->input('grade_filter')) {
            $query->where('grade_level', $gradeFilter);
        }

        // Category filter
        if ($categoryFilter = $request->input('category_filter')) {
            $query->where('category', $categoryFilter);
        }

        // Sorting
        $columns = ['id', 'name', 'category', 'grade_level', 'semester', 'program'];
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        if ($orderColumnIndex >= 1 && $orderColumnIndex <= 5) {
            $sortColumn = $columns[$orderColumnIndex] ?? 'id';
            if ($sortColumn === 'program') {
                $query->leftJoin('programs', 'subjects.program_id', '=', 'programs.id')
                      ->orderBy('programs.code', $orderDir);
            } else {
                $query->orderBy($sortColumn, $orderDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $total = $query->count();
        $filtered = $total;

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($subject, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'name' => $subject->name,
                    'category' => ucfirst($subject->category),
                    'grade_level' => $subject->grade_level,
                    'semester' => $subject->semester,
                    'program' => $subject->program ? $subject->program->code : 'Not Set',
                    'id' => $subject->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        // If using API, you may not need this
        return response()->json(['message' => 'Show form to create program']);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'program_id' => 'required|exists:programs,id',
                'grade_level' => 'required|string|in:Grade 11,Grade 12',
                'category' => 'required|in:core,applied,specialized',
                'semester' => 'required|string|in:1st Semester,2nd Semester',
            ]);

            $subject = Subject::create($validated);

            // Calculate total subjects for the program
            $totalSubjects = Subject::where('program_id', $subject->program_id)->count();

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->performedOn($subject)
                ->withProperties([
                    'action' => 'created',
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'program_id' => $subject->program_id,
                    'program_name' => $subject->program->name ?? 'Unknown',
                    'grade_level' => $subject->grade_level,
                    'category' => $subject->category,
                    'semester' => $subject->semester,
                    'total_subjects_in_program' => $totalSubjects,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Subject created');

            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'data' => $subject,
                'totalSubjects' => $totalSubjects
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Subject creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => auth()->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'program_id' => $subject->program_id,
                    'grade_level' => $subject->grade_level,
                    'category' => $subject->category,
                    'semester' => $subject->semester,
                ]
            ]);
        } catch (\Throwable $th) {
            \Log::error('Subject show failed', [
                'error' => $th->getMessage(),
                'subject_id' => $id,
                'user_id' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Subject not found'
            ], 404);
        }
    }

    public function edit(Subject $subject)
    {
        return response()->json(['message' => 'Show form to edit subject', 'subject' => $subject]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'program_id' => 'required|exists:programs,id',
                'grade_level' => 'required|string|in:Grade 11,Grade 12',
                'category' => 'required|in:core,applied,specialized',
                'semester' => 'required|string|in:1st Semester,2nd Semester',
            ]);

            $subject = Subject::findOrFail($id);
            
            // Store original values for comparison
            $originalValues = $subject->toArray();
            
            $subject->update($validated);

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->performedOn($subject)
                ->withProperties([
                    'action' => 'updated',
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'program_id' => $subject->program_id,
                    'program_name' => $subject->program->name ?? 'Unknown',
                    'original_values' => $originalValues,
                    'new_values' => $validated,
                    'changes' => array_diff_assoc($validated, $originalValues),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Subject updated');

            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'data' => $subject->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Subject update failed', [
                'error' => $e->getMessage(),
                'subject_id' => $id,
                'request_data' => $request->all(),
                'user_id' => auth()->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            
            // Check for dependencies before deletion
            $dependencies = [];

            // Check for section subjects
            $sectionSubjectCount = $subject->sectionSubjects()->count();
            if ($sectionSubjectCount > 0) {
                $dependencies[] = "{$sectionSubjectCount} section subject(s)";
            }

            // If there are dependencies, return error
            if (!empty($dependencies)) {
                $dependencyList = implode(', ', $dependencies);
                return response()->json([
                    'success' => false,
                    'has_section_subjects' => true,
                    'error' => "Cannot delete subject '{$subject->name}' because it is currently being used in {$sectionSubjectCount} section subject(s). Please remove it from all sections first before deleting."
                ], 422);
            }

            // Store subject details before deletion
            $subjectDetails = [
                'id' => $subject->id,
                'name' => $subject->name,
                'program_id' => $subject->program_id,
                'program_name' => $subject->program->name ?? 'Unknown',
                'grade_level' => $subject->grade_level,
                'category' => $subject->category,
                'semester' => $subject->semester
            ];

            // Safe to delete
            $subject->delete();

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'deleted',
                    'subject_id' => $subjectDetails['id'],
                    'subject_name' => $subjectDetails['name'],
                    'program_id' => $subjectDetails['program_id'],
                    'program_name' => $subjectDetails['program_name'],
                    'grade_level' => $subjectDetails['grade_level'],
                    'category' => $subjectDetails['category'],
                    'semester' => $subjectDetails['semester'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Subject deleted');

            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Subject deletion failed', [
                'error' => $e->getMessage(),
                'subject_id' => $id,
                'user_id' => auth()->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
