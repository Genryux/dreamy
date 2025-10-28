<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProgramController extends Controller
{
    public function getPrograms(Request $request)
    {
        $query = Program::query();

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get(['id', 'code', 'name', 'created_at',])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'code' => $item->code,
                    'name' => $item->name,
                    'subjects' => $item->getTotalSubjects(),
                    'sections' => $item->getTotalSections(),
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
        $programCount = Program::count();
        $code = Program::pluck('code')->toArray();
        $programIds = Program::pluck('id')->toArray();

        $totalStudents = Student::whereIn('program_id', $programIds)->count();
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

    public function store(Request $request, Track $tracks)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:programs,code',
            'name' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Add track_id from the route parameter
        $validated['track_id'] = $tracks->id;

        $program = Program::create($validated);
        $programCount = Program::count();

        // Log the activity
        activity('curriculum_management')
            ->causedBy(auth()->user())
            ->performedOn($program)
            ->withProperties([
                'action' => 'created_program',
                'program_id' => $program->id,
                'program_code' => $program->code,
                'program_name' => $program->name,
                'track_id' => $program->track_id,
                'track_name' => $tracks->name,
                'status' => $program->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Program created');

        return response()->json([
            'success' => 'Program created successfully',
            'programCount' => $programCount,
            'data' => $program
        ], 201);
    }

    public function show(Program $program)
    {


        $programs = Program::all();

        $totalStudents = $program->totalStudents($program->id);
        $activeSections = $program->getTotalSections();

        if (FacadesRoute::is('program.sections')) {

            return view('user-admin.curriculum.program.show', compact('programs', 'program', 'totalStudents', 'activeSections'));
        } else if (FacadesRoute::is('program.subjects')) {

            return view('user-admin.curriculum.program.show', compact('program', 'programs', 'totalStudents', 'activeSections'));
        } else if (FacadesRoute::is('program.faculty')) {

            return view('user-admin.curriculum.program.show', compact('program', 'programs', 'totalStudents', 'activeSections'));
        }

        return response()->json($program);
    }

    public function edit(Program $program)
    {
        return response()->json(['message' => 'Show form to edit program', 'program' => $program]);
    }

    public function update(Request $request, Program $program)
    {


        try {

            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:programs,code,' . $program->id,
                'name' => 'required|string|max:255',
                'track' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ]);

            // Store original values for comparison
            $originalValues = $program->toArray();

            $program->update($validated);

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->performedOn($program)
                ->withProperties([
                    'action' => 'updated_program',
                    'program_id' => $program->id,
                    'program_code' => $program->code,
                    'program_name' => $program->name,
                    'original_values' => $originalValues,
                    'new_values' => $validated,
                    'changes' => array_diff_assoc($validated, $originalValues),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Program updated');

            return response()->json([
                'success' => true,
                'message' => 'Program updated successfully',
                'data' => $program
            ]);
        } catch (ValidationException $th) {

            // custom handling for validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $th->getMessage(),
            ], 422);

        } catch (Throwable $e) {
            // catch any other unexpected error
            return response()->json([
                'success' => false,
                'message' => 'Program failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Program $program)
    {
        try {
            // Check for dependencies before deletion
            $dependencies = [];

            // Check for students
            $studentCount = \App\Models\Student::where('program_id', $program->id)->count();
            if ($studentCount > 0) {
                $dependencies[] = "{$studentCount} student(s)";
            }

            // Check for sections
            $sectionCount = $program->sections()->count();
            if ($sectionCount > 0) {
                $dependencies[] = "{$sectionCount} section(s)";
            }

            // Check for subjects
            $subjectCount = $program->subjects()->count();
            if ($subjectCount > 0) {
                $dependencies[] = "{$subjectCount} subject(s)";
            }

            // Check for teachers
            $teacherCount = $program->teachers()->count();
            if ($teacherCount > 0) {
                $dependencies[] = "{$teacherCount} teacher(s)";
            }

            // If there are dependencies, return error
            if (!empty($dependencies)) {
                $dependencyList = implode(', ', $dependencies);
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete program. It is still referenced by: {$dependencyList}. Please remove these references first.",
                    'dependencies' => $dependencies
                ], 422);
            }

            // Store program details before deletion
            $programDetails = [
                'id' => $program->id,
                'code' => $program->code,
                'name' => $program->name,
                'track_id' => $program->track_id,
                'status' => $program->status
            ];

            // Safe to delete
            $program->delete();

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'deleted_program',
                    'program_id' => $programDetails['id'],
                    'program_code' => $programDetails['code'],
                    'program_name' => $programDetails['name'],
                    'track_id' => $programDetails['track_id'],
                    'status' => $programDetails['status'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Program deleted');

            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete program',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeachers(Request $request)
    {
        $query = Teacher::query();

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->with(['sectionSubjects'])
            ->offset($start)
            ->limit($length)
            ->get(['id', 'first_name', 'last_name', 'created_at',])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'code' => $item->code,
                    'name' => $item->name,
                    'subjects' => $item->getTotalSubjects(),
                    'sections' => $item->getTotalSections(),
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
}
