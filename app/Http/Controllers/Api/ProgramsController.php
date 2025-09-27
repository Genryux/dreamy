<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $programs = Program::with(['sections', 'subjects'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $programs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:programs',
            'name' => 'required|string|max:255',
            'track' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive'
        ]);

        $program = Program::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Program created successfully',
            'data' => $program->load(['sections', 'subjects'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $program->load(['sections', 'subjects'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|max:50|unique:programs,code,' . $program->id,
            'name' => 'sometimes|string|max:255',
            'track' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive'
        ]);

        $program->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Program updated successfully',
            'data' => $program->load(['sections', 'subjects'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program): JsonResponse
    {
        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program deleted successfully'
        ]);
    }
} 