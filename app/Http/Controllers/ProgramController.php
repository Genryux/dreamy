<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadesRoute;

class ProgramController extends Controller
{
    public function getPrograms(Request $request)
    {
        // dd($section->id);

        //return response()->json(['ewan' => $request->all()]);

        $query = Program::query();

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
            ->get(['id', 'code', 'name', 'created_at',])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'code' => $item->code,
                    'name' => $item->name,
                    'subjects' => $item->getTotalSubjects(),
                    'sections' => $item->getTotalSections(),
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
        $programCount = Program::count();
        $code = Program::pluck('code')->toArray();
        $programIds = Program::pluck('id')->toArray();

        $totalStudents = Student::whereIn('program', $code)->count();
        $activeSections = Section::whereIn('program_id', $programIds)->count();
        $specializedSubjects = Subject::whereIn('program_id', $programIds)
            ->whereIn('category', ['specialized', 'applied'])
            ->count();
        return view('user-admin.program.index', compact('totalStudents', 'activeSections', 'specializedSubjects', 'programCount'));
    }

    public function create()
    {
        // If using API, you may not need this
        return response()->json(['message' => 'Show form to create program']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:programs,code',
            'name' => 'required|string|max:255',
        ]);

        $program = Program::create($validated);

        return response()->json($program, 201);
    }

    public function show(Program $program)
    {
        
        $programs = Program::all();
        
        $totalStudents = $program->totalStudents($program->code);
        $activeSections = $program->getTotalSections();

        if (FacadesRoute::is('program.sections')) {

            return view('user-admin.program.show', compact('programs', 'program', 'totalStudents', 'activeSections'));
        } else if (FacadesRoute::is('program.subjects')) {

            return view('user-admin.program.show', compact('program', 'programs', 'totalStudents', 'activeSections'));
        }

        return response()->json($program);
    }

    public function edit(Program $program)
    {
        return response()->json(['message' => 'Show form to edit program', 'program' => $program]);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:programs,code,' . $program->id,
            'name' => 'required|string|max:255',
        ]);

        $program->update($validated);

        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }
}
