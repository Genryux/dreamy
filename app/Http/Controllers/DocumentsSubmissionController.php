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

        return view('user-admin.applications.pending-documents.show', compact('applicant', 'submittedDocuments', 'assignedDocuments'));
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

        $currentAcadTerm = $this->academic_term_service->fetchCurrentAcademicTerm();
        $applicant = $this->applicant->fetchAuthenticatedApplicant();

        if (!$currentAcadTerm) {
            return response()->json(['message' => 'No academic term found']);
        }

        $currentAcadTermId = $currentAcadTerm->id;

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

        try {
            DB::transaction(function () use ($documents, $currentAcadTermId, $enrollment_period, $applicant, $documents_id) {
                foreach ($documents as $index => $doc) {

                    $path = $doc->store('applicants', 'public');

                    $uploadedFiles[] = [
                        'academic_terms_id'     => $currentAcadTermId,
                        'enrollment_period_id'  => $enrollment_period->id,
                        'documents_id'          => intval($documents_id[$index]),
                        'file_path'             => $path,
                    ];
                }

                foreach ($uploadedFiles as $file) {
                    //return response()->json(['files' => $file['documents_id']]);
                    $applicant_document = ApplicantDocuments::updateOrCreate(
                        [
                            'applicants_id' => $applicant->id,
                            'documents_id'  => $file['documents_id'],
                        ],
                        ['status' => 'submitted']
                    );

                    $applicant->submissions()->updateOrCreate(
                        [
                            'documents_id'  => $file['documents_id'],
                        ],
                        $file
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Uploaded files have been successfully submitted.'
            ]);
        } catch (\Throwable $th) {
            // return response()->json(['error' => $th->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while submitting the files.'
            ]);
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
        $data = $request->validate([
            'document_id' => ['required', 'integer'],
            'action'      => ['required', 'string', 'in:verify,reject'],
        ]);

        // Attempt to locate the assigned document for this applicant
        $assigned = $applicant->assignedDocuments()
            ->where('id', $data['document_id'])
            ->first();

        if (! $assigned) {
            return redirect()->back()->withErrors(['document' => 'Requested document not found for this applicant.']);
        }

        // Map actions to statuses (easy to extend in the future)
        $actionToStatus = [
            'verify' => 'verified',
            'reject' => 'rejected',
        ];

        $newStatus = $actionToStatus[$data['action']] ?? null;

        if ($newStatus === null) {
            return redirect()->back()->withErrors(['action' => 'Invalid action.']);
        }

        // Only update when there's an actual change to avoid unnecessary writes
        if (($assigned->status ?? null) !== $newStatus) {
            try {
                $assigned->update(['status' => $newStatus]);
            } catch (\Throwable $e) {
                return redirect()->back()->withErrors(['error' => 'Failed to update document status.']);
            }
        }

        // Return appropriate success message based on action
        $successMessage = $data['action'] === 'verify' 
            ? 'Document successfully verified' 
            : 'Document successfully rejected';
            
        return redirect()->back()->with('status', $successMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documents $documents)
    {
        //
    }
}
