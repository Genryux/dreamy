<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\Documents;
use App\Models\Interview;
use App\Services\ApplicantService;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function __construct(protected ApplicantService $applicant) {}
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

        return view('user-admin.selected.interview-details', [
            'applicant_details' => $applicantDetails,
            'interview_details' => $interviewDetails
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


        if ($request->input('action') === 'record-result') {


            $interview = Interview::where('applicants_id', $request->id)->firstOrFail();

            
            $applicant = $this->applicant->fetchApplicant($request->id);
//dd($applicant);
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

            return redirect()->back();
        } else if ($request->input('action') === 'edit-interview') {

            $validated = $request->validate([

                'date' => ['required', 'date'],
                'time' => ['required'],
                'location' => ['required'],
                'add_info' => ['required'],

            ]);

            $interview = Interview::where('applicants_id', $request->id)->firstOrFail();

            $interview->update([
                'date' => $validated['date'],
                'time' => $validated['time'],
                'location' => $validated['location'],
                'add_info' => $validated['add_info'],
                'status' => 'Scheduled'
            ]);

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        //
    }
}
