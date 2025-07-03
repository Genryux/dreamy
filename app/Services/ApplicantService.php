<?php 

namespace App\Services;

use App\Models\Applicants;
use App\Models\User;

class ApplicantService
{

    public function __construct(
        protected Applicants $applicants,
        protected UserService $userService
    ) {
        // Constructor can be used for dependency injection if needed
    }

    /**
     * Get the applicant data by ID.
     *
     * @param int $applicantId
     * @return array
     */
    public function getApplicantData(int $applicantId): array
    {
        // Fetch the applicant data from the database or any other source
        // This is a placeholder for actual fetching logic
        $applicant = $this->fetchApplicant($applicantId);

        if (!$applicant) {
            return [
                'id' => $applicantId,
                'name' => null,
                'email' => null,
                'status' => null,
            ];
        }

        return [
            'id' => $applicant->id,
            'name' => $applicant->name,
            'email' => $applicant->email,
            'status' => $applicant->status,
        ];
    }

    /**
     * Fetch the applicant by ID.
     *
     * @param int $applicantId
     * @return mixed
     */
    public function fetchApplicant(int $applicantId)
    {
        // Placeholder for actual fetching logic, e.g., from a database
        return Applicants::find($applicantId);
    }

    public function fetchAuthenticatedApplicant(): ?Applicants
    {
        // Fetch the authenticated applicant
        $authUser = $this->userService->fetchAuthenticatedUser();

        $applicant = $authUser->applicant()->first();

        if (!$applicant) {
            return null; 
        }

        return $applicant; 
    }
}    