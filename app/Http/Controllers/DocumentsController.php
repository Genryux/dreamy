<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Documents;
use App\Models\User;
use Dom\Document;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function __construct()
    {
        // You can inject services or perform any setup here if needed
    }

    /**
     * Display a listing of the documents.
     */
    public function index()
    {
        return view('user-admin.documents.index');
    }

    /**
     * Get documents for DataTables (AJAX endpoint)
     */
    public function getDocuments(Request $request)
    {
        $query = Documents::query();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->whereAny(['type', 'description'], 'like', "%{$search}%");
        }

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get(['id', 'type', 'description', 'file_type_restriction', 'max_file_size'])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'type' => $item->type ?? '-',
                    'description' => $item->description ?? '-',
                    'file_type_restriction' => implode(', ', $item->file_type_restriction ?? []),
                    'max_file_size' => ($item->max_file_size ?? 0) . ' KB',
                    'id' => $item->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        return response()->json(['message' => 'Display create form']);
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'doc-type' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file-type-option' => 'required|array',
                'file-type-option.*' => 'in:pdf,jpeg,png',
                'file-size' => 'required|numeric|min:1|max:10000',
            ]);

            $document = Documents::create([
                'type' => $validated['doc-type'],
                'description' => $validated['description'],
                'file_type_restriction' => $validated['file-type-option'],
                'max_file_size' => $validated['file-size'],
            ]);

            // Log the activity
            activity('document_management')
                ->causedBy(auth()->user())
                ->performedOn($document)
                ->withProperties([
                    'action' => 'created',
                    'document_id' => $document->id,
                    'document_type' => $document->type,
                    'description' => $document->description,
                    'file_type_restriction' => $document->file_type_restriction,
                    'max_file_size' => $document->max_file_size,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Document created');

            return response()->json([
                'success' => true,
                'id' => $document->id,
                'type' => $document->type,
                'message' => 'Document created successfully'
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Document creation failed', [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create document: ' . $th->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified document.
     */
    public function show($id)
    {
        try {
            $document = Documents::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'document' => [
                    'id' => $document->id,
                    'type' => $document->type,
                    'description' => $document->description,
                    'file_type_restriction' => $document->file_type_restriction,
                    'max_file_size' => $document->max_file_size,
                ]
            ]);
        } catch (\Throwable $th) {
            \Log::error('Document show failed', [
                'error' => $th->getMessage(),
                'document_id' => $id,
                'user_id' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load document: ' . $th->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        return response()->json(['message' => 'Display edit form']);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'doc-type' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file-type-option' => 'required|array',
                'file-type-option.*' => 'in:pdf,jpeg,png',
                'file-size' => 'required|numeric|min:1|max:10000',
            ]);

            $document = Documents::findOrFail($id);
            
            // Store original values for comparison
            $originalValues = $document->toArray();
            
            $document->update([
                'type' => $validated['doc-type'],
                'description' => $validated['description'],
                'file_type_restriction' => $validated['file-type-option'],
                'max_file_size' => $validated['file-size'],
            ]);

            // Log the activity
            activity('document_management')
                ->causedBy(auth()->user())
                ->performedOn($document)
                ->withProperties([
                    'action' => 'updated',
                    'document_id' => $document->id,
                    'original_values' => $originalValues,
                    'new_values' => $validated,
                    'changes' => array_diff_assoc($validated, $originalValues),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Document updated');

            return response()->json([
                'success' => true,
                'id' => $document->id,
                'type' => $document->type,
                'message' => 'Document updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Document update failed', [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update document: ' . $th->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy($id)
    {
        try {
            $document = Documents::findOrFail($id);
            
            // Check if document is referenced in any applicant documents
            if ($document->applicantDocuments()->exists()) {
                $applicantCount = $document->applicantDocuments()->count();
                return response()->json([
                    'success' => false,
                    'has_applicant_documents' => true,
                    'error' => "Cannot delete document '{$document->type}' because it is currently being used by {$applicantCount} applicant(s). Please remove it from all applicants first before deleting."
                ], 422);
            }
            
            // Store document details before deletion
            $documentDetails = [
                'id' => $document->id,
                'type' => $document->type,
                'description' => $document->description,
                'file_type_restriction' => $document->file_type_restriction,
                'max_file_size' => $document->max_file_size
            ];
            
            $document->delete();
            
            // Log the activity
            activity('document_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'deleted',
                    'document_id' => $documentDetails['id'],
                    'document_type' => $documentDetails['type'],
                    'document_description' => $documentDetails['description'],
                    'file_type_restriction' => $documentDetails['file_type_restriction'],
                    'max_file_size' => $documentDetails['max_file_size'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Document deleted');
            
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Throwable $th) {
            \Log::error('Document deletion failed', [
                'error' => $th->getMessage(),
                'document_id' => $id,
                'user_id' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete document: ' . $th->getMessage()
            ], 422);
        }
    }
}
