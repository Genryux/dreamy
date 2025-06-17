<?php

namespace App\Services;

use App\Models\Interview;

class InterviewService
{
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