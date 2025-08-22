<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDocuments;
use App\Models\Applicants;
use App\Models\Documents;
use App\Models\DocumentSubmissions;
use App\Services\AcademicTermService;
use App\Services\ApplicantService;
use App\Services\EnrollmentPeriodService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class DocumentsSubmissionController extends Controller
{
    public function __construct(
        protected AcademicTermService $academic_term_service,
        protected EnrollmentPeriodService $enrollment_period_service,
        protected ApplicantService $applicant
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Applicants $applicant)
    {





        $assignedDocuments = $applicant->assignedDocuments()->get();
        $submittedDocuments = $assignedDocuments->flatMap->submissions;

        // dd($assignedDocuments);

        // $submittedDocuments = $assignedDocuments->submissions;
        // dd($submittedDocuments);




        return view('user-admin.pending-documents.document-details', compact('applicant', 'submittedDocuments', 'assignedDocuments'));
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

        $documents = $request->file('documents');
        $documents_id = $request->input('documents_id');

        $currentAcadTermId = $this->academic_term_service->fetchCurrentAcademicTerm()->id;
        $applicant = $this->applicant->fetchAuthenticatedApplicant();

        if (!$currentAcadTermId) {
            return response()->json(['message' => 'No academic term found']);
        }

        $enrollment_period = $this->enrollment_period_service->getActiveEnrollmentPeriod($currentAcadTermId);

        if (!$enrollment_period) {
            return response()->json(['message' => 'No active enrollment found']);
        }

        if (!$documents) {
            return response()->json(['message' => 'No documents received']);
        }

        // $request->validate([
        //     'documents' => 'required|array',
        //     'documents.*' => 'file|max:10240', // adjust max size (in KB) as needed
        //     'documents_id' => 'required|array',
        //     'documents_id.*' => 'integer|exists:documents,id',
        // ]);

        $uploadedFiles = [];

        // return response()->json(['files' => $documents]);

        foreach ($documents as $index => $doc) {

            $path = $doc->store('applicants', 'public');

            $uploadedFiles[] = [
                'academic_terms_id'     => $currentAcadTermId,
                'enrollment_period_id'  => $enrollment_period->id,
                'applicants_id'         => $applicant->id,
                'documents_id'          => intval($documents_id[$index]),
                'file_path'             => $path,
            ];
        }

        foreach ($uploadedFiles as $file) {
            //return response()->json(['files' => $file['documents_id']]);
            $applicant_document = ApplicantDocuments::updateOrCreate(
                [
                    'applicants_id' => $file['applicants_id'],
                    'documents_id'  => $file['documents_id'],
                ],
                ['status' => 'submitted']
            );

            $applicant_document->submissions()->updateOrCreate(
                [
                    'applicants_id' => $file['applicants_id'],
                    'documents_id'  => $file['documents_id'],
                ],
                $file
            );
        }

        //DocumentSubmissions::insert($uploadedFiles);
        return response()->json(['files' => $uploadedFiles]);
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
    public function update(Request $request, Applicants $applicant)
    {



        $assignedDocuments = $applicant->assignedDocuments()
            ->where('id', $request->document_id)
            ->first();

        // dd($assignedDocuments);

        if ($request->action == "verify") {

            // $request->validate([

            // ]);

            $assignedDocuments->update(
                ['status' => 'verified']
            );
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documents $documents)
    {
        //
    }
}
