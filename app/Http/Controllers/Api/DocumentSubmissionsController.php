<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubmissions;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentSubmissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $documentSubmissions = DocumentSubmissions::with(['owner', 'document'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $documentSubmissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'owner_type' => 'required|string|in:App\Models\Student,App\Models\Applicants',
            'owner_id' => 'required|integer',
            'file_path' => 'required|string|max:500',
            'file_name' => 'required|string|max:255',
            'file_size' => 'nullable|integer|min:1',
            'file_type' => 'nullable|string|max:100',
            'submission_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
            'reviewed_by' => 'nullable|exists:users,id',
            'reviewed_at' => 'nullable|date'
        ]);

        $documentSubmission = DocumentSubmissions::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document submission created successfully',
            'data' => $documentSubmission->load(['owner', 'document'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentSubmissions $documentSubmission): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $documentSubmission->load(['owner', 'document'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentSubmissions $documentSubmission): JsonResponse
    {
        $validated = $request->validate([
            'file_path' => 'sometimes|string|max:500',
            'file_name' => 'sometimes|string|max:255',
            'file_size' => 'nullable|integer|min:1',
            'file_type' => 'nullable|string|max:100',
            'submission_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
            'reviewed_by' => 'nullable|exists:users,id',
            'reviewed_at' => 'nullable|date'
        ]);

        $documentSubmission->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document submission updated successfully',
            'data' => $documentSubmission->load(['owner', 'document'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentSubmissions $documentSubmission): JsonResponse
    {
        $documentSubmission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document submission deleted successfully'
        ]);
    }
} 