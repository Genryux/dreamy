<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SchoolFee;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{

    public function getStudent(Request $request)
    {
        $query = Student::query();

        try {
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('lrn', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                });
            }

            // Limit to avoid sending thousands at once
            $students = $query->select('id', 'lrn', 'first_name', 'last_name', 'grade_level', 'program')
                ->limit(50)
                ->first();

            if (!$students) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found for the given search.',
                ]);
            }

            $schoolFee = SchoolFee::where(function ($q) use ($students) {
                $q->whereHas('program', function ($sub) use ($students) {
                    $sub->where('code', $students->program);
                })
                    ->orWhereNull('program_id'); // include general fees
            })
                ->where(function ($q) use ($students) {
                    $q->where('grade_level', $students->grade_level)
                        ->orWhereNull('grade_level'); // allow default fees
                })
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students,
                'fees' => $schoolFee,
                'hasInvoice' => Invoice::where('student_id', $students->id)
                    ->where('status', 'unpaid')
                    ->exists()
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }

    public function assignSection(Section $section, Request $request)
    {

        $selectedStudents = array_map('intval', $request->input('student'));

        try {
            Student::whereIn('id', $selectedStudents)
                ->update(['section_id' => $section->id]);

            $studentCount = $section->students->count();

            return response()->json([
                'success' => 'Section successfully assigned to the selected students',
                'count'   => $studentCount
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'request' => $request->all()
            ]);
        }

        return response()->json([
            'request' => $request->all()
        ]);
    }

    public function getUsers(Request $request)
    {

        //dd($request->all());

        //return response()->json(['ewan' => $request->all()]);

        $query = Student::with('record');
        //dd($query->get());

        // search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");;
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

        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'lrn', 'first_name', 'last_name', 'grade_level', 'program', 'contact_number', 'email_address'])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $item->lrn,
                    'full_name' => $item->last_name . ', ' . $item->first_name,
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

        return view('user-admin.enrolled-students.index');
    }

    public function create() {}

    public function store() {}

    public function update() {}

    public function show() {}

    public function edit() {}
}
