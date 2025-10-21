<?php

namespace App\Http\Controllers;

use App\Mail\ApplicantProgressMail;
use App\Models\ApplicantDocuments;
use App\Models\Applicants;
use App\Models\Documents;
use App\Models\DocumentSubmissions;
use App\Models\SchoolFee;
use App\Models\SchoolSetting;
use App\Models\User;
use App\Notifications\ImmediateNotification;
use App\Notifications\QueuedNotification;
use App\Services\AcademicTermService;
use App\Services\ApplicantService;
use App\Services\EnrollmentPeriodService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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

        $schoolFees = SchoolFee::all();

        if ($schoolFees->isEmpty()) {
            $schoolFees = null;
        }

        $schoolSettings = SchoolSetting::first();

        if (!isset($schoolSettings->down_payment)) {
            $schoolSettings = null;
        }

        return view('user-admin.applications.pending-documents.show', compact('applicant', 'submittedDocuments', 'assignedDocuments', 'schoolFees', 'schoolSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Get document restrictions for dynamic validation
     */
    public function getDocumentRestrictions()
    {
        $documents = Documents::select('id', 'type', 'file_type_restriction', 'max_file_size')
            ->whereNotNull('file_type_restriction')
            ->whereNotNull('max_file_size')
            ->get();

        $restrictions = [];
        foreach ($documents as $document) {
            $restrictions[$document->id] = [
                'type' => $document->type,
                'allowed_types' => $document->file_type_restriction,
                'max_size_kb' => $document->max_file_size,
                'max_size_mb' => round($document->max_file_size / 1024, 2),
                'accept_string' => '.' . implode(',.', $document->file_type_restriction)
            ];
        }

        return response()->json($restrictions);
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

        // Dynamic validation based on document restrictions
        $validationRules = [
            'documents' => 'required|array',
            'documents_id' => 'required|array',
            'documents_id.*' => 'integer|exists:documents,id',
        ];

        // Get document restrictions for validation
        $documentRestrictions = Documents::whereIn('id', $documents_id)
            ->whereNotNull('file_type_restriction')
            ->whereNotNull('max_file_size')
            ->get()
            ->keyBy('id');

        // Add file validation rules for each document
        foreach ($documents as $index => $document) {
            $docId = $documents_id[$index];
            if (isset($documentRestrictions[$docId])) {
                $restriction = $documentRestrictions[$docId];

                // Build mimes rule
                $mimesRule = 'mimes:' . implode(',', $restriction->file_type_restriction);

                // Build max size rule (convert KB to KB for Laravel validation)
                $maxSizeRule = 'max:' . $restriction->max_file_size;

                $validationRules["documents.{$index}"] = "file|{$mimesRule}|{$maxSizeRule}";
            } else {
                // Fallback validation if no restrictions found
                $validationRules["documents.{$index}"] = 'file|max:10240'; // 10MB default
            }
        }

        $request->validate($validationRules);

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


                // Get document types for the submitted documents
                $submittedDocumentIds = collect($uploadedFiles)->pluck('documents_id')->toArray();
                $documentTypes = Documents::whereIn('id', $submittedDocumentIds)
                    ->pluck('type')
                    ->toArray();
                $documentTypesText = implode(', ', $documentTypes);

                $admins = User::role(['registrar', 'super_admin'])->get();
                Notification::send($admins, new QueuedNotification(
                    "Document Received",
                    $applicant->first_name . " has submitted a document that requires verification.<br>Document Type: " . $documentTypesText,
                    url("/applications/pending-document/submission-details/{$applicant->id}")
                ));

                // Send broadcast for real-time updates (separate broadcasts, no N+1)
                Notification::route('broadcast', 'admins')
                    ->notify(new ImmediateNotification(
                        "Document Received",
                        $applicant->first_name . " has submitted a document that requires verification.<br>Document Type: " . $documentTypesText,
                        url("/applications/pending-document/submission-details/{$applicant->id}")
                    ));
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

        $totalAssignedDocs = $applicant->assignedDocuments()->count();
        $totalVerifiedCount = $applicant->assignedDocuments()->where('status', 'Verified')->count();

        if ($totalVerifiedCount === $totalAssignedDocs) {

            $recipientEmail = $applicant->user->email;
            $loginUrl = config('app.url') . '/portal/login';

            if ($recipientEmail) {
                $title = 'Documents Verified — Dreamy School Enrollment';
                $body = "Congratulations! All your submitted documents have been verified. You’ll be notified as soon as your enrollment is confirmed.";
                Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                    applicantName: $applicant->first_name ?? 'Applicant',
                    title: $title,
                    bodyText: $body,
                    loginUrl: $loginUrl
                ));
            }
        }



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
