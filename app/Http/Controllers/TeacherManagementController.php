<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeacherManagementController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $teachers = Teacher::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user-admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('user-admin.teachers.create');
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(), 'confirmed'],
            'contact_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => Teacher::generateEmployeeId(),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email_address' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'sections', 'sectionSubjects']);
        return view('user-admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('user-admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'contact_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $teacher->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);

            // Update teacher record
            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email_address' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Delete teacher record
            $teacher->delete();

            // Delete user account
            $teacher->user->delete();

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    /**
     * Toggle teacher status.
     */
    public function toggleStatus(Teacher $teacher)
    {
        try {
            $newStatus = $teacher->status === 'active' ? 'inactive' : 'active';
            $teacher->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher status updated successfully.',
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show teacher dashboard with their sections.
     */
    public function dashboard()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        // Get sections where teacher is adviser
        $advisedSections = $teacher->sections()->with(['program', 'enrollments'])->get();
        
        // Get sections where teacher teaches subjects
        $teachingSections = Section::whereHas('sectionSubjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['program', 'enrollments'])->get();

        // Combine and deduplicate sections
        $allSections = $advisedSections->merge($teachingSections)->unique('id');
        
        // Calculate analytics
        $totalSections = $allSections->count();
        $totalStudents = $allSections->sum(function($section) {
            return $section->enrollments->count();
        });
        $advisedSectionsCount = $advisedSections->count();
        $teachingSectionsCount = $teachingSections->count();

        return view('user-teacher.dashboard', compact(
            'teacher', 
            'allSections', 
            'advisedSections', 
            'teachingSections',
            'totalSections',
            'totalStudents',
            'advisedSectionsCount',
            'teachingSectionsCount'
        ));
    }

    /**
     * Get teacher's sections data for DataTables (AJAX).
     */
    public function getTeacherSections(Request $request)
    {
        try {
            $teacher = Auth::user()->teacher;
            
            if (!$teacher) {
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Teacher profile not found'
                ], 404);
            }

            // Get sections where teacher is adviser
            $advisedSections = $teacher->sections()->with(['program', 'enrollments']);
            
            // Get sections where teacher teaches subjects
            $teachingSections = Section::whereHas('sectionSubjects', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->with(['program', 'enrollments']);

            // Combine queries using union
            $query = $advisedSections->union($teachingSections);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('year_level', 'like', "%{$search}%")
                      ->orWhere('room', 'like', "%{$search}%")
                      ->orWhereHas('program', function($programQuery) use ($search) {
                          $programQuery->where('name', 'like', "%{$search}%")
                                     ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->where('year_level', $grade);
            }

            $total = $query->count();
            $filtered = $total;

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($section, $key) use ($start, $teacher) {
                    $isAdviser = $section->teacher_id === $teacher->id;
                    $studentCount = $section->enrollments->count();
                    
                    return [
                        'index' => $start + $key + 1,
                        'name' => $section->name,
                        'program' => $section->program->name ?? 'N/A',
                        'year_level' => $section->year_level,
                        'room' => $section->room ?? 'Not Assigned',
                        'total_students' => $studentCount,
                        'role' => $isAdviser ? 'Adviser' : null, // Only show role if adviser
                        'is_adviser' => $isAdviser,
                        'id' => $section->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getTeacherSections error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load sections data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific section for teachers (read-only view).
     */
    public function showSection(Section $section)
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        // Check if teacher has access to this section (either as adviser or teaches subjects)
        $isAdviser = $section->teacher_id === $teacher->id;
        $teachesSubjects = $section->sectionSubjects()->where('teacher_id', $teacher->id)->exists();
        
        if (!$isAdviser && !$teachesSubjects) {
            return redirect()->route('teacher.dashboard')->with('error', 'You do not have access to this section.');
        }

        // Load necessary relationships
        $section->load(['program', 'teacher', 'sectionSubjects.subject', 'enrollments.student']);
        
        return view('user-teacher.section.show', compact('section', 'teacher', 'isAdviser'));
    }

    /**
     * Get teachers data for DataTables (AJAX).
     */
    public function getTeachers(Request $request)
    {
        try {
            $query = Teacher::with(['user']);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%")
                      ->orWhere('specialization', 'like', "%{$search}%")
                      ->orWhere('email_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('email', 'like', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                $query->where('status', $status);
            }

            // Specialization filter
            if ($specialization = $request->input('specialization_filter')) {
                $query->where('specialization', 'like', "%{$specialization}%");
            }

            $total = $query->count();
            $filtered = $total;

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($teacher, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'employee_id' => $teacher->employee_id,
                        'full_name' => $teacher->getFullNameAttribute(),
                        'email' => $teacher->user ? $teacher->user->email : $teacher->email_address,
                        'specialization' => $teacher->specialization ?? 'Not specified',
                        'status' => $teacher->status,
                        'id' => $teacher->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getTeachers error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load teachers data: ' . $e->getMessage()
            ], 500);
        }
    }
}