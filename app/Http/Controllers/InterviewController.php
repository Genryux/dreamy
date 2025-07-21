<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\Documents;
use App\Models\Interview;
use App\Services\ApplicantService;
use App\Services\InterviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function __construct(
        protected ApplicantService $applicant,
        protected InterviewService $interviewService

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
    public function store(Request $request)
    {

        $action = $request->input('action');

        //dd($request->all());

        if ($action === 'accept-only') {

            $request->validate([

                'date' => ['nullable', 'date'],
                'time' => ['nullable'],
                'location' => ['nullable'],
                'add_info' => ['nullable'],

            ]);

            Interview::create([
                'applicants_id' => $request->id,
                'status' => 'Pending'
            ]);
        } else if ($action === 'accept-with-schedule') {

            $request->validate([

                'date' => ['required', 'date'],
                'time' => ['required'],
                'location' => ['required'],
                'add_info' => ['required'],

            ]);

            Interview::create([
                'applicants_id' => $request->id,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'add_info' => $request->add_info,
                'status' => 'Scheduled'
            ]);
        }

        $applicant = Applicants::find($request->id);

        if ($applicant) {
            $applicant->update([
                'application_status' => 'Selected'
            ]);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Interview $interview, Request $request)
    {

        $applicant = $this->applicant->fetchApplicant($request->id);
        $applicantDetails = $applicant->applicationForm;
        $interviewDetails = $applicant->interview;

        //dd($interviewDetails);

        return view('user-admin.selected.interview-details', [
            'applicant_form' => $applicantDetails,
            'interview_details' => $interviewDetails,
            'applicant' => $applicant
        ]);
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
    public function update(Request $request)
    {


        //dd(Auth::user());
        //dd($request->all());
        $applicant = $this->applicant->fetchApplicant($request->applicant_id);
       // dd($applicant);
        $interview = Interview::where('id', $request->id)
            ->where('applicants_id', $applicant->id)
            ->first();
        // dd($request->id, $applicant->id);
        // dd($interview);

        // FOR FUTURE REFACTORING
        // match ($request->action) {
        //     'status-change' => match ($request->status) {
        //         'enrolled' => $applicant->update(['application_status' => 'Officially Enrolled']),
        //         'declined' => $applicant->update(['application_status' => 'Declined']),
        //         default => abort(400, 'Invalid status'),
        //     },

        //     'send-notification' => match ($request->type) {
        //         'email' => $this->sendEmail($applicant),
        //         'sms' => $this->sendSms($applicant),
        //         default => abort(400, 'Invalid notification type'),
        //     },

        //     default => abort(400, 'Invalid action'),
        // };

        if ($request->input('action') === 'record-result') {

            if ($request->input('result') === 'Interview-Failed') {

                $interview->update([
                    'status' => 'Interview-Failed'
                ]);

                $applicant->update([
                    'application_status' => 'Completed-Failed'
                ]);
            } else if ($request->input('result') === 'Interview-Passed') {

                $interview->update([
                    'status' => 'Interview-Passed'
                ]);

                $applicant->update([
                    'application_status' => 'Pending-Documents'
                ]);

                $required_docs = Documents::all();
                $applicant->documents()->sync($required_docs->pluck('id')->toArray()); // Associate all required documents with the applicant
                $applicant->submissions()->delete(); // Clear previous submissions if any

            }
        } else if ($request->input('action') === 'edit-interview') {

            $validated = $request->validate([

                'date' => ['required', 'date'],
                'time' => ['required'],
                'location' => ['required'],
                'add_info' => ['required'],

            ]);

            $interview->update([
                'date' => $validated['date'],
                'time' => $validated['time'],
                'location' => $validated['location'],
                'add_info' => $validated['add_info'],
                'status' => 'Scheduled'
            ]);
        } else if ($request->input('action') === 'schedule-interview') {

            $validated = $request->validate([
                'date' => ['required', 'date'],
                'time' => ['required'],
                'location' => ['required'],
                'add_info' => ['required'],
                'applicant_id' => 'required|exists:interviews,applicants_id'
            ]);

            $interview->update([
                'date' => $validated['date'],
                'time' => $validated['time'],
                'location' => $validated['location'],
                'add_info' => $validated['add_info'],
                'status' => 'Scheduled'
            ]);
        } else if ($request->input('action') === 'update-docs') {

            $this->interviewService->updateInterviewStatus($applicant->id, $request->status);

            //dd('tangenamo');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        //
    }
}
