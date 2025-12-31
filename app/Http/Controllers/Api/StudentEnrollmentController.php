<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentEnrollment;
use App\Services\EnrollmentPeriodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentPeriodService $enrollmentPeriodService
    ) {}

    /**
     * Confirm enrollment for the authenticated student
     */
    public function confirm(Request $request, StudentEnrollment $enrollment)
    {
        $user = Auth::user();
        
        // Ensure the user has a student record
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Ensure the enrollment belongs to this student
        if ($enrollment->student_id !== $user->student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if there's an active enrollment period for continuing students
        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriodForContinuingStudents();
        
        if (!$activeEnrollmentPeriod) {
            return response()->json([
                'error' => 'Re-enrollment period is not currently open',
                'message' => 'Please wait for the re-enrollment period to begin.'
            ], 400);
        }

        // Only allow confirmation if status is pending
        if ($enrollment->status !== 'pending_confirmation') {
            return response()->json([
                'error' => 'Enrollment cannot be confirmed',
                'current_status' => $enrollment->status
            ], 400);
        }

        // Update enrollment status and link to the enrollment period
        $enrollment->update([
            'status' => 'enrolled',
            'confirmed_at' => now(),
            'enrolled_at' => now(),
            'enrollment_period_id' => $activeEnrollmentPeriod->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment confirmed successfully',
            'enrollment' => $enrollment->load(['academicTerm', 'program', 'section'])
        ]);
    }

    /**
     * Get current enrollment status for authenticated student
     */
    public function current(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Get enrollment for active academic term
        $enrollment = StudentEnrollment::with(['academicTerm', 'program', 'section', 'enrollmentPeriod'])
            ->where('student_id', $user->student->id)
            ->whereHas('academicTerm', function ($query) {
                $query->where('is_active', true);
            })
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'No enrollment found for current term'], 404);
        }

        // Check if re-enrollment period is open for continuing students
        $reenrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriodForContinuingStudents();
        
        // Determine if confirmation button should be shown
        $canConfirm = $enrollment->status === 'pending_confirmation' && $reenrollmentPeriod !== null;

        return response()->json([
            'enrollment' => $enrollment,
            'can_confirm' => $canConfirm,
            'reenrollment_period_open' => $reenrollmentPeriod !== null,
            'reenrollment_period' => $reenrollmentPeriod ? [
                'id' => $reenrollmentPeriod->id,
                'name' => $reenrollmentPeriod->name,
                'end_date' => $reenrollmentPeriod->application_end_date,
            ] : null,
        ]);
    }
}
