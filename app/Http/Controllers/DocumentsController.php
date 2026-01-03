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
            ->get(['id', 'type', 'description', 'file_type_restriction', 'document_for'])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'type' => $item->type ?? '-',
                    'description' => $item->description ?? '-',
                    'file_type_restriction' => implode(', ', $item->file_type_restriction ?? []),
                    'document_for' => ucfirst($item->document_for ?? 'N/A'),
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
     * Get all submitted documents for DataTables (AJAX endpoint)
     */
    public function getSubmittedDocuments(Request $request)
    {
        $query = \App\Models\DocumentSubmissions::query()
            ->with(['documents', 'owner']);

        // Owner type filter (applicant or student)
        if ($ownerType = $request->input('owner_type')) {
            if ($ownerType === 'applicant') {
                $query->where('owner_type', 'App\\Models\\Applicants');
            } elseif ($ownerType === 'student') {
                $query->where('owner_type', 'App\\Models\\Student');
            }
            // If 'all' or empty, don't add any owner_type filter
        }

        // Document type filter
        if ($documentType = $request->input('document_type')) {
            $query->where('documents_id', $documentType);
        }

        // Search filter (search by LRN, name, or document type)
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->whereHasMorph('owner', [\App\Models\Applicants::class, \App\Models\Student::class], function ($subQ, $type) use ($search) {
                    if ($type === \App\Models\Applicants::class) {
                        $subQ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereHas('applicationForm', function ($formQ) use ($search) {
                                $formQ->where('lrn', 'like', "%{$search}%");
                            });
                    } elseif ($type === \App\Models\Student::class) {
                        $subQ->where('lrn', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQ) use ($search) {
                                $userQ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                    }
                })
                ->orWhereHas('documents', function ($subQ) use ($search) {
                    $subQ->where('type', 'like', "%{$search}%");
                });
            });
        }

        $total = \App\Models\DocumentSubmissions::count();
        $filtered = $query->count();

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        // Handle sorting - only submitted_at column is sortable
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

        $data = $query
            ->orderBy('submitted_at', $orderDir)
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($item, $key) use ($start) {
                $owner = $item->owner;
                
                // Determine if owner is Applicant or Student
                if ($item->owner_type === 'App\\Models\\Applicants') {
                    $lrn = $owner?->applicationForm?->lrn ?? 'N/A';
                    $name = $owner ? ($owner->last_name . ', ' . $owner->first_name) : 'N/A';
                    $ownerType = 'Applicant';
                } else if ($item->owner_type === 'App\\Models\\Student') {
                    $lrn = $owner?->lrn ?? 'N/A';
                    $name = $owner?->user ? ($owner->user->last_name . ', ' . $owner->user->first_name) : 'N/A';
                    $ownerType = 'Student';
                } else {
                    $lrn = 'N/A';
                    $name = 'N/A';
                    $ownerType = 'Unknown';
                }
                
                return [
                    'index' => $start + $key + 1,
                    'lrn' => $lrn,
                    'name' => $name,
                    'owner_type' => $ownerType,
                    'document_type' => $item->documents?->type ?? 'Unknown',
                    'submitted_at' => $item->submitted_at 
                        ? $item->submitted_at->format('M d, Y h:i A') 
                        : 'N/A',
                    'file_path' => $item->file_path,
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
     * Get all document types for filter dropdown
     */
    public function getDocumentTypes()
    {
        $documentTypes = Documents::select('id', 'type')->orderBy('type')->get();
        
        return response()->json([
            'success' => true,
            'data' => $documentTypes
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
                'document-for' => 'required|in:regular,transferee,both',
            ]);

            $document = Documents::create([
                'type' => $validated['doc-type'],
                'description' => $validated['description'],
                'file_type_restriction' => $validated['file-type-option'],
                'document_for' => $validated['document-for'],
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
                'document-for' => 'required|in:regular,transferee,both',
            ]);

            $document = Documents::findOrFail($id);
            
            // Store original values for comparison
            $originalValues = $document->toArray();
            
            $document->update([
                'type' => $validated['doc-type'],
                'description' => $validated['description'],
                'file_type_restriction' => $validated['file-type-option'],
                'document_for' => $validated['document-for'],
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
