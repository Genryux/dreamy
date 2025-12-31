<?php

namespace App\Services;

use App\Models\EnrollmentPeriod;

class EnrollmentPeriodService
{
    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}
    /**
     * Get the active enrollment period for the current academic term.
     *
     * @return mixed
     */
    public function getActiveEnrollmentPeriod($currentAcademicTermId)
    {
        return EnrollmentPeriod::where('active', true)
            ->where('academic_terms_id', $currentAcademicTermId)
            ->first();
    }

    /**
     * Get any active enrollment period across all academic terms.
     * Used when we enforce only one active enrollment period at a time.
     *
     * @return mixed
     */
    public function getAnyActiveEnrollmentPeriod()
    {
        return EnrollmentPeriod::where('active', true)
            ->with('academicTerms') // Eager load the related academic term
            ->first();
    }

    /**
     * Get active enrollment period for new applicants only.
     * Used for applicant portal to only show periods meant for new students.
     *
     * @return EnrollmentPeriod|null
     */
    public function getActiveEnrollmentPeriodForNewApplicants()
    {
        return EnrollmentPeriod::where('active', true)
            ->where('period_for', 'new')
            ->with('academicTerms')
            ->first();
    }

    /**
     * Get active enrollment period for continuing/old students only.
     * Used for student portal to show re-enrollment periods.
     *
     * @return EnrollmentPeriod|null
     */
    public function getActiveEnrollmentPeriodForContinuingStudents()
    {
        return EnrollmentPeriod::where('active', true)
            ->where('period_for', 'old')
            ->with('academicTerms')
            ->first();
    }

    /**
     * Get active enrollment period by type.
     *
     * @param string $periodFor 'new' or 'old'
     * @return EnrollmentPeriod|null
     */
    public function getActiveEnrollmentPeriodByType(string $periodFor)
    {
        return EnrollmentPeriod::where('active', true)
            ->where('period_for', $periodFor)
            ->with('academicTerms')
            ->first();
    }

}   