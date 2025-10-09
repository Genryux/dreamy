<?php

namespace App\Http\Controllers;

use App\Models\Track;
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
        $tracks = Track::orderBy('name')->get();

        return view('user-admin.curriculum.tracks.index', compact('tracks'));
        
        return response()->json([
            'success' => true,
            'data' => $tracks->count(),
            'message' => 'Tracks retrieved successfully'
        ]);
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

            $track->update($validated);

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
                    $totalStudents = \App\Models\Student::where('program', $program->code)->count();
                    
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
