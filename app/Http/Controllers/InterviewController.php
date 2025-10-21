<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\Documents;
use App\Models\Interview;
use App\Services\ApplicantService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantProgressMail;
use App\Services\InterviewService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterviewController extends Controller
{
    public function __construct(
        protected ApplicantService $applicant,
        protected InterviewService $interviewService,
        protected NotificationService $notificationService

    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Applicants $applicant, Request $request)
    {
        $action = $request->input('action');

        $user_applicant = $applicant->user;

        $user = Auth::user();
        $role = $user?->getRoleNames()->first() ?? 'No Role';

        $loginUrl = config('app.url') . '/portal/login';
        $recipientEmail = $user_applicant->email ?? null;

        if ($action === 'accept-only') {
            $request->validate([
                'date' => ['nullable', 'date'],
                'time' => ['nullable'],
                'location' => ['nullable'],
                'add_info' => ['nullable'],
            ]);
        } elseif ($action === 'accept-with-schedule' || $action === 'schedule-admission' || $action === 'edit-admission') {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required',
                'location' => 'required|string',
                'contact_person' => 'nullable|exists:teachers,id',
                'add_info' => 'required|string',
            ]);
        }

        try {
            $result = DB::transaction(function () use ($action, $request, $applicant, $user, $role, $user_applicant, $recipientEmail, $loginUrl) {
                $msg = '';

                if ($action === 'accept-only') {
                    $applicant->interview()->updateOrCreate(
                        [
                            'applicants_id' => $applicant->id
                        ],
                        [
                            'status' => null
                        ]
                    );
                    $msg = 'Applicant successfully accepted.';
                    // Send acceptance email instead of private notification

                    if ($recipientEmail) {
                        $title = 'Application Accepted — Dreamy School Enrollment';
                        $body = "Your application has been successfully accepted and is now awaiting scheduling.\n\nPlease log in to your account to view your application status and next steps.";
                        Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                            applicantName: $applicant->first_name ?? 'Applicant',
                            title: $title,
                            bodyText: $body,
                            loginUrl: $loginUrl
                        ));
                    }
                } elseif ($action === 'update-status') {
                    $applicant->interview()->updateOrCreate(
                        [
                            'applicants_id' => $applicant->id
                        ],
                        [
                            'status' => 'Taking-Exam',
                        ]
                    );

                    $msg = 'Status successfully updated.';
                } elseif ($action === 'accept-with-schedule' || $action === 'schedule-admission') {
                    $applicant->interview()->updateOrCreate(
                        [
                            'applicants_id' => $applicant->id
                        ],
                        [
                            'date' => $request->date,
                            'time' => $request->time,
                            'location' => $request->location,
                            'add_info' => $request->add_info,
                            'teacher_id' => $request->contact_person,
                            'status' => 'Scheduled',
                        ]
                    );

                    if ($action === 'accept-with-schedule') {
                        $msg = 'Applicant successfully accepted and scheduled.';

                        if ($recipientEmail) {
                            $title = 'Application Accepted & Scheduled — Dreamy School Enrollment';
                            $body = "Congratulations! Your application has been successfully accepted and your schedule has been confirmed.\n\nPlease log in to your account to view your scheduled dates and next steps.";
                            Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                                applicantName: $applicant->first_name ?? 'Applicant',
                                title: $title,
                                bodyText: $body,
                                loginUrl: $loginUrl
                            ));
                        }
                    } else {
                        $msg = 'Applicant successfully scheduled.';
                        if ($recipientEmail) {
                            $title = 'Schedule Confirmed — Dreamy School Enrollment';
                            $body = "Great news! Your schedule is confirmed. Log in to your account to see your scheduled dates and next steps.";
                            Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                                applicantName: $applicant->first_name ?? 'Applicant',
                                title: $title,
                                bodyText: $body,
                                loginUrl: $loginUrl
                            ));
                        }
                    }
                } elseif ($action === 'edit-admission') {
                    $applicant->interview()->updateOrCreate(
                        [
                            'applicants_id' => $applicant->id
                        ],
                        [
                            'date' => $request->date,
                            'time' => $request->time,
                            'location' => $request->location,
                            'add_info' => $request->add_info,
                            'teacher_id' => $request->contact_person,
                        ]
                    );

                    $msg = 'Admission schedule successfully updated.';
                }

                $applicant->update([
                    'application_status' => 'Accepted',
                    'accepted_by' => "{$user->first_name} - {$role}",
                    'accepted_at' => Carbon::now(),
                ]);

                return $msg;
            });

            return response()->json([
                'success' => true,
                'message' => $result
            ]);
        } catch (\Throwable $th) {
            Log::critical('Unhandled throwable', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.' . $th->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Handled exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept applicant: Something went wrong while processing your request.'
            ], 500);
        }
    }

    public function recordAdmissionResult(Applicants $applicant, Request $request)
    {

        $request->validate([
            'result' => 'required|in:Exam-Failed,Exam-Passed',
            'due-date' => 'required_if:result,Exam-Passed|date|nullable',
        ]);

        $user = Auth::user();
        $role = $user?->getRoleNames()->first() ?? 'No Role';

        $user_applicant = $applicant->user;

        $loginUrl = config('app.url') . '/portal/login';
        $recipientEmail = $user_applicant->email ?? null;

        try {

            $result = DB::transaction(function () use ($applicant, $request, $user, $role, $loginUrl, $recipientEmail) {

                if ($request->input('result') === 'Exam-Failed') {

                    $applicant->interview()->update([
                        'status' => 'Exam-Failed',
                        'recorded_by' => "{$user->first_name} - {$role}",
                        'recorded_at' => Carbon::now()
                    ]);

                    $applicant->update([
                        'application_status' => 'Completed-Failed'
                    ]);


                    if ($recipientEmail) {
                        $title = 'Admission Exam Result — Dreamy School Enrollment';
                        $body = "We regret to inform you that you did not pass the admission exam. Please check your account for your results and guidance on available next steps.";
                        Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                            applicantName: $applicant->first_name ?? 'Applicant',
                            title: $title,
                            bodyText: $body,
                            loginUrl: $loginUrl
                        ));
                    }

                    return 'Result successfully recorded.';
                }

                $applicant->interview()->update([
                    'status' => 'Exam-Passed',
                    'recorded_by' => "{$user->first_name} - {$role}",
                    'recorded_at' => Carbon::now()
                ]);

                $applicant->update([
                    'application_status' => 'Pending-Documents'
                ]);

                $required_docs = Documents::all();
                $applicant->assignedDocuments()->delete();
                $applicant->submissions()->delete(); // Clear previous submissions if any

                // Assign fresh requirements
                foreach ($required_docs as $doc) {

                    $applicant->assignedDocuments()->create([
                        'documents_id'  => $doc->id,
                        'status'        => 'Pending', // default
                        'submit_before' => $request->input('due-date')

                    ]);
                }

                if ($recipientEmail) {
                    $title = 'Admission Exam Result — Dreamy School Enrollment';
                    $body = "Congratulations! You have successfully passed the admission exam. Please log in to your account to view your results and next steps in the enrollment process.";
                    Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                        applicantName: $applicant->first_name ?? 'Applicant',
                        title: $title,
                        bodyText: $body,
                        loginUrl: $loginUrl
                    ));
                }

                return 'Result recorded and documents successfully assigned.';
            });

            return response()->json([
                'success' => true,
                'message' => $result
            ]);
        } catch (\Throwable $th) {
            Log::error('Error recording admission result', ['error' => $th]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while recording the result.'
            ]);
        }
    }

    public function updateStatus(Applicants $applicant, Request $request)
    {

        $validated = $request->validate([
            'status'       => 'required|string|in:Exam-Completed'
        ]);

        try {

            $applicant->interview->update(['status' => $validated['status']]);

            // Clear the dashboard cache for this user
            $cacheKey = 'admission_dashboard_' . auth()->id();
            \Cache::forget($cacheKey);

            return redirect()->back();
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showAdmissionDetails(Applicants $applicant)
    {
        $applicant_details = $applicant?->applicationForm;
        $interview_details = $applicant?->interview;

        // If no interview record exists, create a default one to avoid null errors
        if (!$interview_details) {
            $interview_details = new \App\Models\Interview([
                'id' => null,
                'applicants_id' => $applicant->id,
                'status' => null,
                'date' => null,
                'time' => null,
                'location' => null,
                'interviewer_name' => null,
                'notes' => null,
                'created_at' => null,
                'updated_at' => null
            ]);
        }

        return view('user-admin.applications.accepted-applications.show', compact('applicant', 'applicant_details', 'interview_details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interview $interview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request)
    // {

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'You reached weansdmasdkl'
    //     ]);

    //     //dd($request->id, $request->applicant_id);

    //     //dd(Auth::user());
    //     //dd($request->$request->applicant_id);
    //     $applicant = $this->applicant->fetchApplicant($request->applicant_id);
    //     //dd($applicant);
    //     $interview = Interview::where('id', $request->id)
    //         ->where('applicants_id', $applicant->id)
    //         ->first();
    //     // dd($request->id, $applicant->id);
    //     // dd($interview);

    //     // FOR FUTURE REFACTORING
    //     // match ($request->action) {
    //     //     'status-change' => match ($request->status) {
    //     //         'enrolled' => $applicant->update(['application_status' => 'Officially Enrolled']),
    //     //         'declined' => $applicant->update(['application_status' => 'Declined']),
    //     //         default => abort(400, 'Invalid status'),
    //     //     },

    //     //     'send-notification' => match ($request->type) {
    //     //         'email' => $this->sendEmail($applicant),
    //     //         'sms' => $this->sendSms($applicant),
    //     //         default => abort(400, 'Invalid notification type'),
    //     //     },

    //     //     default => abort(400, 'Invalid action'),
    //     // };


    //     } else if ($request->input('action') === 'edit-interview') {

    //         $validated = $request->validate([

    //             'date' => ['required', 'date'],
    //             'time' => ['required'],
    //             'location' => ['required'],
    //             'add_info' => ['required'],

    //         ]);

    //         $interview->update([
    //             'date' => $validated['date'],
    //             'time' => $validated['time'],
    //             'location' => $validated['location'],
    //             'add_info' => $validated['add_info'],
    //             'status' => 'Scheduled'
    //         ]);
    //     } else if ($request->input('action') === 'schedule-admission') {

    //         $validated = $request->validate([
    //             'date' => ['required', 'date'],
    //             'time' => ['required'],
    //             'location' => ['required'],
    //             'add_info' => ['required'],
    //             'applicant_id' => 'required|exists:interviews,applicants_id'
    //         ]);

    //         $interview->update([
    //             'date' => $validated['date'],
    //             'time' => $validated['time'],
    //             'location' => $validated['location'],
    //             'add_info' => $validated['add_info'],
    //             'status' => 'Scheduled'
    //         ]);
    //     } else if ($request->input('action') === 'update-docs') {

    //         $validated = $request->validate([
    //             'status' => 'required|string|in:Interview-Completed',
    //             'applicant_id' => 'required|exists:interviews,applicants_id'
    //         ]);

    //         $this->interviewService->updateInterviewStatus($applicant->id, $request->status);

    //         //dd('tangenamo');
    //     }
    //     return redirect()->back();
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        //
    }
}
