<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
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
                    'adviser' => '-',
                    'year_level' => $item->year_level,
                    'room' => $item->room ?? '-',
                    'total_enrolled_students' => $item->total_enrolled_students ?? '-',
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

    public function getStudents(Section $section, Request $request)
    {
        // dd($section->id);

        //return response()->json(['ewan' => $request->all()]);

        $query = Student::query()->where('section_id', $section->id);
        // dd($query->get());

        // // search filter
        // if ($search = $request->input('search.value')) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('lrn', 'like', "%{$search}%")
        //             ->where('first_name', 'like', "%{$search}%")
        //             ->orWhere('last_name', 'like', "%{$search}%")
        //             ->orWhere('email_address', 'like', "%{$search}%")
        //             ->orWhere('program', 'like', "%{$search}%")
        //             ->orWhere('grade_level', 'like', "%{$search}%")
        //             ->orWhere('contact_number', 'like', "%{$search}%")
        //             ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");;
        //     });
        // }

        // // Filtering
        // if ($program = $request->input('program_filter')) {
        //     $query->whereHas('record', fn($q) => $q->where('program', $program));
        // }

        // if ($grade = $request->input('grade_filter')) {
        //     $query->whereHas('record', fn($q) => $q->where('grade_level', $grade));
        // }

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
            ->get(['id', 'lrn', 'last_name', 'first_name', 'age', 'gender', 'contact_number'])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $item->lrn,
                    'full_name' => $item->last_name . ', ' . $item->first_name,
                    'age' => $item->age ?? '-',
                    'gender' => $item->gender ?? '-',
                    'contact_number' => $item->contact_number ?? '-',
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
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'year_level' => 'required|string',
            'room' => 'nullable|string|max:50',
            'total_enrolled_students' => 'nullable|integer|min:0',
        ]);

        $section = Section::create($validated);

        return response()->json($section, 201);
    }

    public function show(Section $section)
    {
        // dd($section->sectionSubjects);

        // foreach ($section->sectionSubjects as $subject) {
        //     $sub = Subject::find($subject->subject_id);

        //     dump($subject->subject_id, $sub->name);
        // }

        $year_level = $section->year_level;
        $program = $section->program->code;

        $students = Student::where('grade_level', $year_level)
            ->where('program', $program)
            ->where('section_id', null)
            ->get();

        return view('user-admin.section.show', compact('section', 'students'));
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
