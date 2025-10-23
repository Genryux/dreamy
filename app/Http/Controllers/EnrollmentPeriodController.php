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
        $validated = $request->validate([
            'academic_terms_id' => 'required|exists:academic_terms,id',
            'name' => 'required|string|max:255',
            'max_applicants' => 'required|integer|min:1',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date|after:application_start_date',
            'period_type' => 'required|in:early,regular,late',
            'early_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Set default values
        $validated['early_discount_percentage'] = $validated['early_discount_percentage'] ?? 0.00;
        $validated['status'] = 'Ongoing';
        $validated['active'] = true;

        $enrollmentPeriod = EnrollmentPeriod::create($validated);

        // Log the activity
        activity('enrollment_period')
            ->causedBy(auth()->user())
            ->performedOn($enrollmentPeriod)
            ->withProperties([
                'action' => 'created',
                'period_details' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Enrollment period created');

        return redirect()->back()->with('success', 'Enrollment period created successfully.');
    }

    public function updateEnrollment(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:enrollment_periods,id',
            'academic_terms_id' => 'required|exists:academic_terms,id',
            'name' => 'required|string|max:255',
            'max_applicants' => 'required|integer|min:1',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date|after:application_start_date',
            'period_type' => 'required|in:early,regular,late',
            'early_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $enrollmentPeriod = EnrollmentPeriod::findOrFail($validated['id']);
        
        // Store original values for comparison
        $originalValues = $enrollmentPeriod->toArray();
        
        // Set default values
        $validated['early_discount_percentage'] = $validated['early_discount_percentage'] ?? 0.00;
        
        // Remove id from validated data before update
        unset($validated['id']);
        
        $enrollmentPeriod->update($validated);

        // Log the activity
        activity('enrollment_period')
            ->causedBy(auth()->user())
            ->performedOn($enrollmentPeriod)
            ->withProperties([
                'action' => 'updated',
                'original_values' => $originalValues,
                'new_values' => $validated,
                'changes' => array_diff_assoc($validated, $originalValues),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Enrollment period updated');

        return redirect()->back()->with('success', 'Enrollment period updated successfully.');
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
        $enrollmentPeriod = EnrollmentPeriod::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:Ongoing,Paused,Closed',
        ]);

        $updateData = $validated;

        if ($request->status == 'Closed') {
            $updateData['active'] = false;
        }

        // Store original values for comparison
        $originalValues = $enrollmentPeriod->toArray();
        
        $enrollmentPeriod->update($updateData);

        // Log the activity
        activity('enrollment_period')
            ->causedBy(auth()->user())
            ->performedOn($enrollmentPeriod)
            ->withProperties([
                'action' => 'status_updated',
                'original_status' => $originalValues['status'],
                'new_status' => $validated['status'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Enrollment period status updated');

        event(new EnrollmentPeriodStatusUpdated($enrollmentPeriod));

        // Check if request is AJAX/JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Enrollment period updated successfully.',
                'data' => $enrollmentPeriod
            ]);
        }

        return redirect()->back()->with('success', 'Enrollment period updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }
}