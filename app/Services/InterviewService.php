<?php

namespace App\Services;

use App\Models\Applicants;
use App\Models\Interview;

class InterviewService
{

    public function __construct(
        public Applicants $applicants,
    )
    {
        // Constructor can be used for dependency injection if needed
    }
    
    public function getInterviewData(int $applicantId): array
    {

        $data = $this->fetchInterview($applicantId);

        if (!$data) {
            return [
                'applicant_id' => $applicantId,
                'status' => null,
                'date' => null,
                'time' => null,
                'location' => null,
                'add_info' => null,
            ];
        }

        return [
            'applicant_id' => $data->applicantId,
            'status' => $data->status,
            'date' => $data->date,
            'time' => $data->time,
            'location' => $data->location,
            'add_info' => $data->add_info,
        ];
    }

    public function fetchInterview(int $applicantId): ?Interview
    {
        // Fetch the interview by ID
        return Interview::where('applicant_id', $applicantId)->first();
    }

    public function updateInterviewStatus(int $applicantId, string $status): Interview
    {
        
        $applicant = $this->applicants->with('interview')->find($applicantId);
        
        if (!$applicant || !$applicant->interview) {
            throw new \Exception('Interview not found for the given applicant ID.');
        }

        // Update the interview status
        $applicant->interview->update(['status' => $status]);

        return $applicant->interview;

    }

    public function updateInterviewInfo(int $applicantId, array $data): Interview
    {
        // Find the interview by applicant ID
        $interview = Interview::where('applicant_id', $applicantId)->first();

        if (!$interview) {
            throw new \Exception('Interview not found for the given applicant ID.');
        }

        // Update the interview details
        $interview->update($data);

        return $interview;
    }



    /**
     * Validate and process the interview data.
     *
     * @param array $data
     * @return array
     */
    public function processInterview(array $data): array
    {
        // Validate the data
        $this->validateData($data);

        // Process the interview (e.g., save to database)
        // This is a placeholder for actual processing logic
        $interview = $this->saveInterview($data);

        return [
            'status' => 'success',
            'interview' => $interview,
        ];
    }

    /**
     * Validate the interview data.
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    protected function validateData(array $data): void
    {
        if (empty($data['applicant_id']) || empty($data['status'])) {
            throw new \InvalidArgumentException('Applicant ID and status are required.');
        }
        // Additional validation rules can be added here
    }

    /**
     * Save the interview data to the database.
     *
     * @param array $data
     * @return array
     */
    protected function saveInterview(array $data): array
    {
        // Placeholder for saving logic, e.g., Interview::create($data);
        return $data; // Return the saved interview data for now
    }
}
