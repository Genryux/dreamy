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
     * Get the current academic term ID.
     *
     * @return int|null
     */

}   