<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    public function getUsers(Request $request)
    {
        $query = Students::with('record');
        //dd($query->get());

        // search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Filtering
        if ($program = $request->input('program_filter')) {
             $query->whereHas('record', fn($q) => $q->where('program', $program));
        }

        if ($grade = $request->input('grade_filter')) {
            $query->whereHas('record', fn($q) => $q->where('grade_level', $grade));
        }

        // Sorting
        // Column mapping: must match order of your <th> and JS columns
        $columns = ['lrn', 'first_name', 'grade_level', 'program', 'contact_number', 'email_address'];

        // Get sort column index and direction
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        // Map to actual column name
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';

        // Apply sorting
        $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        // $limit = $request->input('length', 10);  // default to 10 per page
        // $offset = $request->input('start', 0);

        $data = $query
            ->offset($request->start)
            ->limit($request->length)
            ->get(['id', 'lrn', 'first_name', 'last_name', 'grade_level', 'program' , 'contact_number', 'email_address'])
            ->map(function ($item) {
                // dd($item);
                return [
                    'lrn' => $item->lrn,
                    'full_name' => $item->first_name . ' ' . $item->last_name,
                    'grade_level' => $item->grade_level,
                    'program' => $item->program,
                    'contact' => $item->contact_number,
                    'email' => $item->email_address,
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

        // $enrolled_students = Students::all();

        // dd($enrolled_students);
        $data = User::limit(10)->get();

        // dd($data);

        return view('user-admin.enrolled-students');
    }

    public function create() {}

    public function store() {}

    public function update() {}

    public function show() {}

    public function edit() {}
}
