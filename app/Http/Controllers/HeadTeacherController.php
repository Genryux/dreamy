<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\EnrollmentPeriod;
use App\Services\AcademicTermService;
use Illuminate\Http\Request;

class HeadTeacherController extends Controller
{
    public function dashboard()
    {
        $academicTermService = app(AcademicTermService::class);
        $academicTermData = $academicTermService->getCurrentAcademicTermData();
        
        // Get enrollment period status
        $enrollmentPeriod = EnrollmentPeriod::where('active', true)->first();
        $enrollmentStatus = 'closed';
        $enrollmentMessage = 'Enrollment period is currently closed';
        
        if ($enrollmentPeriod) {
            $now = now();
            if ($now->between($enrollmentPeriod->application_start_date, $enrollmentPeriod->application_end_date)) {
                $enrollmentStatus = 'open';
                $enrollmentMessage = 'Enrollment period is currently ongoing';
            } elseif ($now->lt($enrollmentPeriod->application_start_date)) {
                $enrollmentStatus = 'upcoming';
                $enrollmentMessage = 'Enrollment period will start soon';
            } else {
                $enrollmentStatus = 'closed';
                $enrollmentMessage = 'Enrollment period has ended';
            }
        }

        // Get enrolled students for current academic term
        $enrolledStudents = Student::whereHas('enrollments', function($query) use ($academicTermData) {
            $query->where('academic_term_id', $academicTermData['id'] ?? null);
        })->with(['user', 'program', 'sections'])->get();

        // Get all teachers/faculty
        $teachers = Teacher::with(['user', 'program'])->get();

        // Get sections with their details
        $sections = Section::with(['program', 'teacher.user', 'enrollments'])->get();

        // Calculate statistics
        $totalStudents = $enrolledStudents->count();
        $totalTeachers = $teachers->count();
        $totalSections = $sections->count();
        $activeTeachers = $teachers->where('status', 'active')->count();

        return view('user-head-teacher.dashboard', compact(
            'academicTermData',
            'enrollmentStatus',
            'enrollmentMessage',
            'enrolledStudents',
            'teachers',
            'sections',
            'totalStudents',
            'totalTeachers',
            'totalSections',
            'activeTeachers'
        ));
    }

    public function getTeachers(Request $request)
    {
        $query = Teacher::with(['user', 'program', 'sections']);

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('program', function($programQuery) use ($search) {
                      $programQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        // Program filter
        if ($programFilter = $request->input('program_filter')) {
            $query->where('program_id', $programFilter);
        }

        // Sorting
        $columns = ['id', 'employee_id', 'full_name', 'program', 'specialization', 'advising_count', 'email'];
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        if ($orderColumnIndex >= 1 && $orderColumnIndex <= 6) {
            $sortColumn = $columns[$orderColumnIndex] ?? 'id';
            if ($sortColumn === 'full_name') {
                $query->leftJoin('users', 'teachers.user_id', '=', 'users.id')
                      ->orderBy('users.last_name', $orderDir)
                      ->orderBy('users.first_name', $orderDir);
            } elseif ($sortColumn === 'program') {
                $query->leftJoin('programs', 'teachers.program_id', '=', 'programs.id')
                      ->orderBy('programs.code', $orderDir);
            } elseif ($sortColumn === 'email') {
                $query->leftJoin('users', 'teachers.user_id', '=', 'users.id')
                      ->orderBy('users.email', $orderDir);
            } elseif ($sortColumn === 'advising_count') {
                $query->withCount('sections')->orderBy('sections_count', $orderDir);
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
            ->withCount('sections')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($teacher, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'employee_id' => $teacher->employee_id ?? 'N/A',
                    'full_name' => $teacher->getFullNameAttribute(),
                    'program' => $teacher->program ? $teacher->program->code : 'N/A',
                    'specialization' => $teacher->specialization ?? 'N/A',
                    'advising_count' => $teacher->sections_count ?? 0,
                    'email' => $teacher->getEmailAttribute() ?? 'N/A',
                    'id' => $teacher->id
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
