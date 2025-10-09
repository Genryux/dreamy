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
        //dd($query);

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

        // Semester filter
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
        $subjects = Subject::all();
        return response()->json($subjects);
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

            // Audit logging for subject creation
            \Log::info('Subject created', [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'program_id' => $subject->program_id,
                'created_by' => auth()->user()->id,
                'created_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

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

    public function show(Subject $subject)
    {
        try {
            return response()->json([
                'success' => true,
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            \Log::error('Subject retrieval failed', [
                'error' => $e->getMessage(),
                'subject_id' => $subject->id ?? null,
                'user_id' => auth()->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Subject $subject)
    {
        return response()->json(['message' => 'Show form to edit subject', 'subject' => $subject]);
    }

    public function update(Request $request, Subject $subject)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'program_id' => 'required|exists:programs,id',
                'grade_level' => 'required|string|in:Grade 11,Grade 12',
                'category' => 'required|in:core,applied,specialized',
                'semester' => 'required|string|in:1st Semester,2nd Semester',
            ]);

            $subject->update($validated);

            // Calculate total subjects for the program
            $totalSubjects = Subject::where('program_id', $subject->program_id)->count();

            // Audit logging for subject update
            \Log::info('Subject updated', [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'program_id' => $subject->program_id,
                'updated_by' => auth()->user()->id,
                'updated_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'data' => $subject->fresh(),
                'totalSubjects' => $totalSubjects
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
                'subject_id' => $subject->id,
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

    public function destroy(Subject $subject)
    {
        try {
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
                    'message' => "Cannot delete subject. It is still referenced by: {$dependencyList}. Please remove these references first.",
                    'dependencies' => $dependencies
                ], 422);
            }

            // Audit logging for subject deletion
            \Log::info('Subject deleted', [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'program_id' => $subject->program_id,
                'deleted_by' => auth()->user()->id,
                'deleted_by_email' => auth()->user()->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            // Store program_id before deletion for count calculation
            $programId = $subject->program_id;

            // Safe to delete
            $subject->delete();

            // Calculate total subjects for the program after deletion
            $totalSubjects = Subject::where('program_id', $programId)->count();

            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully',
                'totalSubjects' => $totalSubjects
            ]);

        } catch (\Exception $e) {
            \Log::error('Subject deletion failed', [
                'error' => $e->getMessage(),
                'subject_id' => $subject->id,
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
