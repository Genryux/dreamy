<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $documents = Documents::paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'document_type' => 'nullable|string|max:100',
            'file_format' => 'nullable|string|max:50',
            'max_file_size' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50'
        ]);

        $document = Documents::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document created successfully',
            'data' => $document
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documents $document): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $document
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documents $document): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'sometimes|boolean',
            'document_type' => 'nullable|string|max:100',
            'file_format' => 'nullable|string|max:50',
            'max_file_size' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50'
        ]);

        $document->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data' => $document
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documents $document): JsonResponse
    {
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
} 