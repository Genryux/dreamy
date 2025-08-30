<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $studentDocuments = StudentDocument::with(['student', 'document'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $studentDocuments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'document_id' => 'required|exists:documents,id',
            'file_path' => 'required|string|max:500',
            'file_name' => 'required|string|max:255',
            'file_size' => 'nullable|integer|min:1',
            'file_type' => 'nullable|string|max:100',
            'upload_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string'
        ]);

        $studentDocument = StudentDocument::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student document created successfully',
            'data' => $studentDocument->load(['student', 'document'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentDocument $studentDocument): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $studentDocument->load(['student', 'document'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentDocument $studentDocument): JsonResponse
    {
        $validated = $request->validate([
            'file_path' => 'sometimes|string|max:500',
            'file_name' => 'sometimes|string|max:255',
            'file_size' => 'nullable|integer|min:1',
            'file_type' => 'nullable|string|max:100',
            'upload_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string'
        ]);

        $studentDocument->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student document updated successfully',
            'data' => $studentDocument->load(['student', 'document'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentDocument $studentDocument): JsonResponse
    {
        $studentDocument->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student document deleted successfully'
        ]);
    }
} 