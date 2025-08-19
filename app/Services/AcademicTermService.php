<?php

namespace App\Services;

use App\Models\AcademicTerms;

class AcademicTermService
{
    /**
     * Get the current academic term data.
     *
     * @return array
     */
    public function getCurrentAcademicTermData(): array
    {
        // Fetch the current academic term data
        $academicTerm = $this->fetchCurrentAcademicTerm();

        if (!$academicTerm) {
            return [
                'id' => null,
                'name' => null,
                'start_date' => null,
                'end_date' => null,
                'is_active' => false,
            ];
        }

        return [
            'id' => $academicTerm->id,
            'name' => $academicTerm->name,
            'start_date' => $academicTerm->start_date,
            'end_date' => $academicTerm->end_date,
            'is_active' => $academicTerm->is_active,
        ];
    }

    /**
     * Fetch the current academic term from the database.
     *
     * @return mixed
     */
    public function fetchCurrentAcademicTerm()
    {
        return AcademicTerms::where('is_active', true)->first();
    }

}
