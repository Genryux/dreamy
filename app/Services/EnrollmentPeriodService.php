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
     * Get the current academic term ID.
     *
     * @return int|null
     */

}   