<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $subjects = Subject::with(['program', 'sectionSubjects.section', 'sectionSubjects.teacher'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'units' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'status' => 'nullable|string|max:50'
        ]);

        $subject = Subject::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully',
            'data' => $subject->load(['program', 'sectionSubjects.section', 'sectionSubjects.teacher'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $subject->load(['program', 'sectionSubjects.section', 'sectionSubjects.teacher'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        $validated = $request->validate([
            'program_id' => 'sometimes|exists:programs,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'units' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'status' => 'nullable|string|max:50'
        ]);

        $subject->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully',
            'data' => $subject->load(['program', 'sectionSubjects.section', 'sectionSubjects.teacher'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully'
        ]);
    }
} 