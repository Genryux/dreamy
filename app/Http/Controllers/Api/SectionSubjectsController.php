<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SectionSubject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $sectionSubjects = SectionSubject::with(['section', 'subject', 'teacher'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $sectionSubjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:100',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $sectionSubject = SectionSubject::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section subject created successfully',
            'data' => $sectionSubject->load(['section', 'subject', 'teacher'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SectionSubject $sectionSubject): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $sectionSubject->load(['section', 'subject', 'teacher', 'students'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SectionSubject $sectionSubject): JsonResponse
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:100',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $sectionSubject->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section subject updated successfully',
            'data' => $sectionSubject->load(['section', 'subject', 'teacher', 'students'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SectionSubject $sectionSubject): JsonResponse
    {
        $sectionSubject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section subject deleted successfully'
        ]);
    }
}
