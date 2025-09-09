<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SchoolFee;
use Illuminate\Http\Request;

class SchoolFeeController extends Controller
{

    public function getSchoolFees(Request $request)
    {

        //dd($request->all());

        //return response()->json(['ewan' => $request->all()]);

        $query = SchoolFee::query();
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
            ->with('program')
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'name', 'grade_level', 'amount','program_id'])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'name' => $item->name ?? '-',
                    'applied_to_program' => $item->program->code ?? 'All Programs',
                    'applied_to_level' => $item->grade_level ?? 'All Year Levels',
                    'amount' => '₱ ' . $item->amount ?? '-',
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

    // Display a listing of the resource.
    public function index()
    {
        $schoolFees = SchoolFee::all();
        $programs = Program::all();

        // foreach ($schoolFees as $fee) {
        //     if ($fee->program) {
        //         dump($fee->program->name);
        //     } else {
        //         dump('No program assigned');
        //     }
        // }

        // dd($schoolFees);

        return view('user-admin.school-fees.index', compact('schoolFees', 'programs'));
        return response()->json($schoolFees);
    }

    // Show the form for creating a new resource.
    public function create()
    {
        // For API, this might not be needed.
        return response()->json(['message' => 'Display create form']);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'amount' => 'required|numeric',
                'program_id' => 'nullable|exists:programs,id',
                'grade_level' => 'nullable|string',
                // Add other fields as needed
            ]);

            $schoolFee = SchoolFee::create($validated);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }

        return response()->json($schoolFee, 201);
    }

    // Display the specified resource.
    public function show($id)
    {
        $schoolFee = SchoolFee::findOrFail($id);
        return response()->json($schoolFee);
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // For API, this might not be needed.
        return response()->json(['message' => 'Display edit form']);
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $schoolFee = SchoolFee::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'student_id' => 'sometimes|required|integer',
            // Add other fields as needed
        ]);

        $schoolFee->update($validated);

        return response()->json($schoolFee);
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $schoolFee = SchoolFee::findOrFail($id);
        $schoolFee->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
