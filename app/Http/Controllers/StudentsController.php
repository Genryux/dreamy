<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    public function getUsers(Request $request)
    {
        $query = User::query();

        // search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%");
            });
        }

        // Filtering
        if ($program = $request->input('program_filter')) {
            $query->where('first_name', 'like', "%{$program}%");
        }

        if ($grade = $request->input('grade_filter')) {
            $query->where('email', 'like', "%{$grade}%");
        }


        // Sorting
        // Column mapping: must match order of your <th> and JS columns
        $columns = ['id', 'first_name', 'last_name', 'grade_level', 'program'];

        // Get sort column index and direction
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        // Map to actual column name
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';

        // Apply sorting
        $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        $data = $query
            ->offset($request->start)
            ->limit($request->length)
            ->get(['id', 'first_name', 'last_name', 'email'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'first_name' => $item->first_name,
                    'last_name' => $item->last_name,
                    'email' => $item->email,
                    'id' => $item->id
                ];
            });

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
