<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
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
                'applicant_id' => $request->id,
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
                'applicant_id' => $request->id,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'add_info' => $request->add_info,
                'status' => 'Scheduled'
            ]);

        }

        $applicant = Applicant::find($request->id);

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

        $applicant = Applicant::find($request->id);
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

        $validated = $request->validate([

            'date' => ['required', 'date'],
            'time' => ['required'],
            'location' => ['required'],
            'add_info' => ['required'],

        ]);

        $interview = Interview::where('applicant_id', $request->id)->firstOrFail();

        $interview->update([
            'date' => $validated['date'],
            'time' => $validated['time'],
            'location' => $validated['location'],
            'add_info' => $validated['add_info'],
            'status' => 'Scheduled'
        ]);

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
