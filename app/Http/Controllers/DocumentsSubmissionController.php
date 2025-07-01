<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use App\Models\Documents;
use App\Models\DocumentSubmissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class DocumentsSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Applicants $applicant)
    {

        $required_docs = Documents::all();
        $documentSubmissions = DocumentSubmissions::where('applicants_id', $applicant->id)->get()->keyBy('documents_id');

        return view('user-admin.pending-documents.document-details', [
            'required_docs' => $required_docs,
            'submissions' => $documentSubmissions,
            'applicant' => $applicant,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Documents $documents, Request $request)
    {
 

    //    

      dd();

        // Return the view with the document details
        return view('user-admin.pending-documents.document-details', [
            'document' => $documents,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documents $documents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentSubmissions $submittedDocuments)
    {
        dd($request->all(), $submittedDocuments->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documents $documents)
    {
        //
    }
}
