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
        $request->validate([

            'date' => ['required', 'date'],
            'time' => ['required'],
            'location' => 'required',
            'add_info' => 'required',

        ]);

        Interview::create([
            'applicant_id' => $request->id,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'add_info' => $request->add_info,
            'status' => 'Scheduled'
        ]);

        $applicant = Applicant::find($request->id);

        if ($applicant) {
            $applicant->update([
                'application_status' => 'Selected'
            ]);
        }

        return redirect('pending-applications');
    }

    /**
     * Display the specified resource.
     */
    public function show(Interview $interview)
    {
        return view('user-admin.selected.interview-details');
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
    public function update(Request $request, Interview $interview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        //
    }
}
