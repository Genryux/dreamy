<?php

namespace App\Http\Controllers;

use App\Events\EnrollmentPeriodStatusUpdated;
use App\Models\EnrollmentPeriod;
use Illuminate\Http\Request;

class EnrollmentPeriodController extends Controller
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
        //dd($request->all());
        $validated = $request->validate([
            'academic_terms_id' => 'required|exists:academic_terms,id',
            'name' => 'required|string|max:255',
            'max_applicants' => 'required|integer|min:1',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date|after:application_start_date',
        ]);

        EnrollmentPeriod::create($validated);

        return redirect()->back()->with('success', 'Enrollment period created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->id);

        $enrollmentPeriod = EnrollmentPeriod::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:Ongoing,Paused,Closed',
        ]);

        $updateData = $validated;

        if ($request->status == 'Closed') {
            $updateData['active'] = false;
        }

        $enrollmentPeriod->update($updateData);

        event(new EnrollmentPeriodStatusUpdated($enrollmentPeriod));
        // Broadcast the event to update the enrollment period status

        return redirect()->back()->with('success', 'Enrollment period updated successfully.');

        //return response()->json(['message' => 'Enrollment period updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }
}
