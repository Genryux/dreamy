<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SchoolFee;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\AcademicTerms;
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
        // Feature flag: use new per-term enrollment system or fallback to old system
        if (config('app.use_term_enrollments')) {
            return $this->getUsersFromEnrollments($request);
        }

        // Use original logic
        return $this->getOriginalUsers($request);
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

    /**
     * Get enrollment statistics for the current term
     */
    public function getEnrollmentStats(Request $request)
    {
        if (!config('app.use_term_enrollments')) {
            // Fallback: just count all students
            $total = Student::count();
            return response()->json([
                'total' => $total,
                'enrolled' => $total, // Assume all are enrolled in old system
                'pending' => 0,
            ]);
        }

        // Get selected term from URL parameter or default to active term
        $termId = $request->get('term_id');
        $selectedTerm = $termId 
            ? AcademicTerms::find($termId) 
            : AcademicTerms::where('is_active', true)->first();
        
        if (!$selectedTerm) {
            return response()->json([
                'total' => 0,
                'enrolled' => 0,
                'pending' => 0,
            ]);
        }

        $baseQuery = StudentEnrollment::where('academic_term_id', $selectedTerm->id);
        $total = $baseQuery->count();
        $enrolled = StudentEnrollment::where('academic_term_id', $selectedTerm->id)->where('status', 'enrolled')->count();
        $pending = StudentEnrollment::where('academic_term_id', $selectedTerm->id)->where('status', 'pending_confirmation')->count();

        return response()->json([
            'total' => $total,
            'enrolled' => $enrolled,
            'pending' => $pending,
            'term_name' => $selectedTerm->getFullNameAttribute(),
        ]);
    }

    /**
     * Original getUsers logic (extracted to avoid recursion)
     */
    private function getOriginalUsers(Request $request)
    {
        $query = Student::with('record');

        // search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
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
        $columns = ['lrn', 'first_name', 'grade_level', 'program', 'contact_number', 'email_address'];
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';
        $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'lrn', 'first_name', 'last_name', 'grade_level', 'program', 'contact_number', 'email_address'])
            ->map(function ($item, $key) use ($start) {
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

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Get users from the new per-term enrollment system
     */
    private function getUsersFromEnrollments(Request $request)
    {
        // Get selected term from URL parameter or default to active term
        $termId = $request->get('term_id');
        $selectedTerm = $termId 
            ? AcademicTerms::find($termId) 
            : AcademicTerms::where('is_active', true)->first();
        
        if (!$selectedTerm) {
            // Fallback to original method if no term found (avoid recursion)
            return $this->getOriginalUsers($request);
        }

        // Query enrollments for the selected term
        $query = StudentEnrollment::with(['student.record'])
            ->where('academic_term_id', $selectedTerm->id);
            
        // Filter by status if specified
        $statusFilter = $request->input('status_filter');
        if ($statusFilter && in_array($statusFilter, ['enrolled', 'pending_confirmation'])) {
            $query->where('status', $statusFilter);
        }
        // Default: show all statuses (enrolled + pending)

        // Search filter - search within the related student
        if ($search = $request->input('search.value')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            });
        }

        // Filtering by program and grade
        if ($program = $request->input('program_filter')) {
            $query->whereHas('student.record', fn($q) => $q->where('program', $program));
        }

        if ($grade = $request->input('grade_filter')) {
            $query->whereHas('student.record', fn($q) => $q->where('grade_level', $grade));
        }

        // Get total count before applying sorting/pagination
        $total = $query->count();
        $filtered = $total;

        // Apply sorting - use a simpler approach with eager loading
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        
        // For now, let's just order by enrollment id to avoid join complexity
        $query->orderBy('student_enrollments.id', $orderDir);

        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get()
            ->map(function ($enrollment, $key) use ($start) {
                $student = $enrollment->student;
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $student->lrn ?? '',
                    'full_name' => ($student->last_name ?? '') . ', ' . ($student->first_name ?? ''),
                    'grade_level' => $student->grade_level ?? '',
                    'program' => $student->program ?? '',
                    'contact' => $student->contact_number ?? '',
                    'email' => $student->email_address ?? '',
                    'status' => $enrollment->status === 'enrolled' ? 'Enrolled' : 'Pending Confirmation',
                    'status_raw' => $enrollment->status,
                    'confirmed_at' => $enrollment->confirmed_at ? $enrollment->confirmed_at->format('M j, Y') : null,
                    'id' => $student->id,
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
}
