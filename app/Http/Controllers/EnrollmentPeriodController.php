<?php

namespace App\Http\Controllers;

use App\Events\EnrollmentPeriodStatusUpdated;
use App\Models\Applicants;
use App\Models\EnrollmentPeriod;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
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
            'application_start_date' => 'required|date|after_or_equal:today',
            'application_end_date' => 'required|date|after:application_start_date',
            'period_type' => 'required|in:early,regular,late',
            'early_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check for overlapping enrollment periods for the same academic term
        $overlapping = EnrollmentPeriod::where('academic_terms_id', $validated['academic_terms_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('application_start_date', [$validated['application_start_date'], $validated['application_end_date']])
                    ->orWhereBetween('application_end_date', [$validated['application_start_date'], $validated['application_end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('application_start_date', '<=', $validated['application_start_date'])
                          ->where('application_end_date', '>=', $validated['application_end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withErrors(['application_start_date' => 'This enrollment period overlaps with an existing period for the selected academic term.'])
                ->withInput();
        }

        // Set default values
        $validated['early_discount_percentage'] = $validated['early_discount_percentage'] ?? 0.00;
        $validated['status'] = 'Ongoing';
        $validated['active'] = true;

        // Constraint: Only one active enrollment period at a time
        // Deactivate any currently active enrollment periods
        EnrollmentPeriod::where('active', true)->update(['active' => false]);

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

        // Check for overlapping enrollment periods (excluding current period)
        $overlapping = EnrollmentPeriod::where('academic_terms_id', $validated['academic_terms_id'])
            ->where('id', '!=', $validated['id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('application_start_date', [$validated['application_start_date'], $validated['application_end_date']])
                    ->orWhereBetween('application_end_date', [$validated['application_start_date'], $validated['application_end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('application_start_date', '<=', $validated['application_start_date'])
                          ->where('application_end_date', '>=', $validated['application_end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withErrors(['application_start_date' => 'This enrollment period overlaps with an existing period for the selected academic term.'])
                ->withInput();
        }
        
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

            // If this is an enrollment period for continuing students, handle unconfirmed students
            if ($enrollmentPeriod->period_for === 'old') {
                $this->handleUnconfirmedStudents($enrollmentPeriod);
            }

            // Auto-archive all non-enrolled applicants for this enrollment period
            $archivedCount = $this->archiveNonEnrolledApplicants($enrollmentPeriod);
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

    /**
     * Handle students who didn't confirm their enrollment when the period is closed.
     * - Updates student_enrollments.status to 'withdrawn'
     * - Updates students.status to 'Dropped'
     */
    private function handleUnconfirmedStudents(EnrollmentPeriod $enrollmentPeriod)
    {
        // Get all student enrollments linked to this period that are still pending confirmation
        $unconfirmedEnrollments = StudentEnrollment::where('enrollment_period_id', $enrollmentPeriod->id)
            ->where('status', 'pending_confirmation')
            ->with('student')
            ->get();

        foreach ($unconfirmedEnrollments as $enrollment) {
            // Update the enrollment status to withdrawn
            $enrollment->update([
                'status' => 'withdrawn',
            ]);

            // Update the student status to Dropped
            if ($enrollment->student) {
                $enrollment->student->update([
                    'status' => 'Dropped',
                ]);
            }
        }

        \Log::info("Handled unconfirmed students for enrollment period {$enrollmentPeriod->id}", [
            'period_name' => $enrollmentPeriod->name,
            'unconfirmed_count' => $unconfirmedEnrollments->count(),
        ]);
    }

    /**
     * Archive all non-enrolled applicants when the enrollment period is closed.
     * Applicants with status 'Officially Enrolled' are excluded from archiving.
     */
    private function archiveNonEnrolledApplicants(EnrollmentPeriod $enrollmentPeriod): int
    {
        // Get all applicants for this enrollment period who are NOT officially enrolled
        $applicantsToArchive = Applicants::where('enrollment_period_id', $enrollmentPeriod->id)
            ->where('is_archived', false)
            ->where('application_status', '!=', 'Officially Enrolled')
            ->get();

        $archivedCount = 0;
        $now = Carbon::now();

        foreach ($applicantsToArchive as $applicant) {
            $applicant->update([
                'is_archived' => true,
                'archived_at' => $now,
                'archive_reason' => 'Enrollment period ended',
            ]);
            $archivedCount++;
        }

        \Log::info("Auto-archived applicants for enrollment period {$enrollmentPeriod->id}", [
            'period_name' => $enrollmentPeriod->name,
            'archived_count' => $archivedCount,
        ]);

        return $archivedCount;
    }

    /**
     * Get count of applicants that will be archived when closing enrollment period.
     * Used by the end enrollment modal to show the user how many will be affected.
     */
    public function getArchivableApplicantsCount($id)
    {
        try {
            $enrollmentPeriod = EnrollmentPeriod::findOrFail($id);

            // Count applicants who are NOT officially enrolled and NOT already archived
            $count = Applicants::where('enrollment_period_id', $id)
                ->where('is_archived', false)
                ->where('application_status', '!=', 'Officially Enrolled')
                ->count();

            // Get breakdown by status for detailed display
            $breakdown = Applicants::where('enrollment_period_id', $id)
                ->where('is_archived', false)
                ->where('application_status', '!=', 'Officially Enrolled')
                ->selectRaw('application_status, COUNT(*) as count')
                ->groupBy('application_status')
                ->pluck('count', 'application_status')
                ->toArray();

            return response()->json([
                'success' => true,
                'count' => $count,
                'breakdown' => $breakdown,
                'enrollment_period' => [
                    'id' => $enrollmentPeriod->id,
                    'name' => $enrollmentPeriod->name,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getArchivableApplicantsCount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get archivable applicants count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for date conflicts with existing enrollment periods.
     * Used for live validation in the create enrollment period modal.
     */
    public function checkDateConflict(Request $request)
    {
        try {
            $validated = $request->validate([
                'academic_terms_id' => 'required|exists:academic_terms,id',
                'application_start_date' => 'required|date',
                'application_end_date' => 'required|date',
            ]);

            // Find overlapping enrollment periods
            $overlappingPeriods = EnrollmentPeriod::where('academic_terms_id', $validated['academic_terms_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('application_start_date', [$validated['application_start_date'], $validated['application_end_date']])
                        ->orWhereBetween('application_end_date', [$validated['application_start_date'], $validated['application_end_date']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('application_start_date', '<=', $validated['application_start_date'])
                              ->where('application_end_date', '>=', $validated['application_end_date']);
                        });
                })
                ->get(['id', 'name', 'application_start_date', 'application_end_date', 'period_type']);

            if ($overlappingPeriods->isNotEmpty()) {
                $conflictDetails = $overlappingPeriods->map(function ($period) {
                    return [
                        'id' => $period->id,
                        'name' => $period->name,
                        'period_type' => ucfirst($period->period_type),
                        'start_date' => Carbon::parse($period->application_start_date)->format('M d, Y'),
                        'end_date' => Carbon::parse($period->application_end_date)->format('M d, Y'),
                    ];
                });

                return response()->json([
                    'hasConflict' => true,
                    'conflictingPeriods' => $conflictDetails,
                    'message' => 'The selected dates overlap with an existing enrollment period.',
                ]);
            }

            return response()->json([
                'hasConflict' => false,
                'message' => 'No conflicts found.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'hasConflict' => false,
                'error' => 'Error checking conflicts: ' . $e->getMessage(),
            ], 500);
        }
    }
}