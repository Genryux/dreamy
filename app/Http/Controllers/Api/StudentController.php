<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $students = Student::with(['user', 'sections', 'record'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'section_id' => 'nullable|exists:sections,id',
            'lrn' => 'required|string|max:12|unique:students',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'program' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'grade_level' => 'required|string|max:50',
            'enrollment_date' => 'nullable|date',
            'status' => 'nullable|string|max:50'
        ]);

        $student = Student::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student->load(['user', 'sections', 'record'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $student->load(['user', 'sections', 'record', 'assignedDocuments'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'section_id' => 'nullable|exists:sections,id',
            'lrn' => 'sometimes|string|max:12|unique:students,lrn,' . $student->id,
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'age' => 'nullable|integer|min:0',
            'program' => 'sometimes|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'grade_level' => 'sometimes|string|max:50',
            'enrollment_date' => 'nullable|date',
            'status' => 'nullable|string|max:50'
        ]);

        $student->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $student->load(['user', 'sections', 'record'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }
} 