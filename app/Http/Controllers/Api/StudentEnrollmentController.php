<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
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

        // Only allow confirmation if status is pending
        if ($enrollment->status !== 'pending_confirmation') {
            return response()->json([
                'error' => 'Enrollment cannot be confirmed',
                'current_status' => $enrollment->status
            ], 400);
        }

        // Update enrollment status
        $enrollment->update([
            'status' => 'enrolled',
            'confirmed_at' => now(),
            'enrolled_at' => now(),
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
        $enrollment = StudentEnrollment::with(['academicTerm', 'program', 'section'])
            ->where('student_id', $user->student->id)
            ->whereHas('academicTerm', function ($query) {
                $query->where('is_active', true);
            })
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'No enrollment found for current term'], 404);
        }

        return response()->json([
            'enrollment' => $enrollment
        ]);
    }
}
