<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $sections = Section::with(['program', 'students', 'sectionSubjects.subject', 'sectionSubjects.teacher'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:sections',
            'capacity' => 'required|integer|min:1',
            'total_enrolled_students' => 'nullable|integer|min:0',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50'
        ]);

        $section = Section::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully',
            'data' => $section->load(['program', 'students', 'sectionSubjects.subject', 'sectionSubjects.teacher'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $section->load(['program', 'students', 'sectionSubjects.subject', 'sectionSubjects.teacher'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section): JsonResponse
    {
        $validated = $request->validate([
            'program_id' => 'sometimes|exists:programs,id',
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:sections,code,' . $section->id,
            'capacity' => 'sometimes|integer|min:1',
            'total_enrolled_students' => 'nullable|integer|min:0',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50'
        ]);

        $section->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully',
            'data' => $section->load(['program', 'students', 'sectionSubjects.subject', 'sectionSubjects.teacher'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section): JsonResponse
    {
        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully'
        ]);
    }
} 