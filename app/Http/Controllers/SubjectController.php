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

        $subjects = Subject::where('program_id', $programId)
            ->where('grade_level', $yearLevel)
            ->where('semester', $termData['semester'])
            ->get();

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'grade_level' => 'required|string',
            'category' => 'nullable|in:core,applied,specialized',
            'semester' => 'required|string',
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject);
    }

    public function edit(Subject $subject)
    {
        return response()->json(['message' => 'Show form to edit program', 'program' => $subject]);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'grade_level' => 'required|string',
            'category' => 'nullable|in:core,applied,specialized',
            'semester' => 'required|string',
        ]);

        $subject->update($validated);

        return response()->json($subject);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }
}
