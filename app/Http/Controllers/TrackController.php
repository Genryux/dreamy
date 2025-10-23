<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Services\AcademicTermService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tracks = Track::with(['programs.sections', 'programs.subjects', 'programs.teachers'])->orderBy('name')->get();

        // Get current academic term data
        $academicTermService = app(AcademicTermService::class);
        $academicTermData = $academicTermService->getCurrentAcademicTermData();

        // Calculate comprehensive statistics
        $totalPrograms = $tracks->sum(function ($track) {
            return $track->programs()->count();
        });

        $totalSections = $tracks->sum(function ($track) {
            return $track->programs->sum(function ($program) {
                return $program->sections()->count();
            });
        });

        $totalSubjects = $tracks->sum(function ($track) {
            return $track->programs->sum(function ($program) {
                return $program->subjects()->count();
            });
        });

        $totalTeachers = $tracks->sum(function ($track) {
            return $track->programs->sum(function ($program) {
                return $program->teachers()->count();
            });
        });

        $totalStudents = $tracks->sum(function ($track) {
            return $track->programs->sum(function ($program) {
                return \App\Models\Student::where('program_id', $program->id)->count();
            });
        });

        $activeTracks = $tracks->where('status', 'active')->count();
        $inactiveTracks = $tracks->where('status', 'inactive')->count();

        return view('user-admin.curriculum.tracks.index', compact(
            'tracks',
            'academicTermData',
            'totalPrograms',
            'totalSections',
            'totalSubjects',
            'totalTeachers',
            'totalStudents',
            'activeTracks',
            'inactiveTracks'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:tracks,name',
                'code' => 'nullable|string|max:255|unique:tracks,code',
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive'
            ]);

            $track = Track::create($validated);

            return response()->json([
                'success' => true,
                'data' => $track,
                'message' => 'Track created successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create track',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Track $track): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $track,
            'message' => 'Track retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Track $track): JsonResponse
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:tracks,name,' . $track->id,
                'code' => 'nullable|string|max:255|unique:tracks,code,' . $track->id,
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive'
            ]);

            // Store original values for comparison
            $originalValues = $track->toArray();
            
            $track->update($validated);

            // Log the activity
            activity('curriculum_management')
                ->causedBy(auth()->user())
                ->performedOn($track)
                ->withProperties([
                    'action' => 'updated_track',
                    'track_id' => $track->id,
                    'track_name' => $track->name,
                    'original_values' => $originalValues,
                    'new_values' => $validated,
                    'changes' => array_diff_assoc($validated, $originalValues),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Track updated');

            return response()->json([
                'success' => true,
                'data' => $track->fresh(),
                'message' => 'Track updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => "Validation failed: {$e->getMessage()}",
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update track',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track): JsonResponse
    {
        try {
            $track->delete();

            return response()->json([
                'success' => true,
                'message' => 'Track deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete track',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get programs for a specific track.
     */
    public function getPrograms(Track $track): JsonResponse
    {
        try {
            // Get programs that belong to this track with sections and students count
            $programs = $track->programs()
                ->withCount(['sections', 'subjects'])
                ->select(['id', 'name', 'code', 'status'])
                ->orderBy('name')
                ->get()
                ->map(function ($program) {
                    // Get total students across all sections in this program
                    $totalStudents = \App\Models\Student::where('program_id', $program->id)->count();
                    
                    return [
                        'id' => $program->id,
                        'name' => $program->name,
                        'code' => $program->code,
                        'status' => $program->status,
                        'sections_count' => $program->getTotalSections(),
                        'teachers_count' => $program->totalTeachers(),
                        'subjects_count' => $program->subjects_count,
                        'students_count' => $totalStudents
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $programs,
                'message' => 'Programs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve programs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
