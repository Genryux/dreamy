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
        
        $required_docs = Documents::all();

        return view('user-admin.pending-documents.document-list', [
            'required_docs' => $required_docs,
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        // Logic to show the document creation form

        return view('documents.create');
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {

        
        // Logic to validate and store the document
        // ...

        $validated = $request->validate([
            'doc-type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file-type-option' => 'required|array',
            'file-type-option.*' => 'in:pdf,jpeg,png',
            'file-size' => 'required|numeric|min:1|max:10000',
        ]);

        Documents::create([
            'type' => $validated['doc-type'],
            'description' => $validated['description'],
            'file_type_restriction' => $validated['file-type-option'],
            'max_file_size' => $validated['file-size'],
        ]);

        return redirect()->back()->with('success', 'Document created successfully.');
    }
    
}
