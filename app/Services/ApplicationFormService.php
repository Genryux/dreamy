<?php 

namespace App\Services;

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\EnrollmentPeriod;

class ApplicationFormService
{

    public function getApplicationFormData(int $applicantId): array
    {
        // Fetch the application form data for the given applicant ID
        // This is a placeholder for actual fetching logic
        $applicationData = $this->fetchApplicationById($applicantId);

        if (!$applicationData) {
            return [
                'applicant_id' => $applicantId,
                'name' => null,
                'email' => null,
                'phone' => null,
                'address' => null,
            ];
        }

        return [
            'applicant_id' => $applicationData['applicant_id'],
            'name' => $applicationData['name'],
            'email' => $applicationData['email'],
            'phone' => $applicationData['phone'],
            'address' => $applicationData['address'],
        ];
    }

    public function fetchApplicationById(int $applicantId)
    {
        return Applicant::find($applicantId);
    }

    public function fetchApplicationWithAnyStatus(array $status)
    {
        return Applicant::withAnyStatus($status);
    }

    public function fetchApplicationWithStatus(string $status)
    {
        return Applicant::withStatus($status);
    }

    public function fetchRecentPendingApplications(int $limit = 10)
    {
        return Applicant::withStatus('Pending')
            ->latest()
            ->limit($limit)
            ->get();
    }


    /**
     * Validate and process the application form data.
     *
     * @param array $data
     * @return array
     */
    public function processApplicationForm(
        array $data, 
        ?Applicant $applicant, 
        ?AcademicTerms $academicTerm, 
        ?EnrollmentPeriod $activeEnrollmentPeriod
    
    ): ApplicationForm
    {
        $data['applicant_id'] = $applicant->id ?? null;
        $data['academic_term_id'] = $academicTerm->id ?? null;
        $data['enrollment_period_id'] = $activeEnrollmentPeriod->id ?? null;

        // Validate the data
        $this->validateData($data);

        return $this->saveApplication($data);

    }

    /**
     * Validate the application form data.
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function validateData(array $data): array
    {
        return validator($data, [
            'lrn' => ['required', 'digits:12', 'unique:application_forms,lrn'],
            'full_name' => ['required', 'string'],
            'age' => ['required', 'integer'],
            'birthdate' => ['required', 'date'],
            'desired_program' => ['required', 'string'],
            'grade_level' => ['required']
        ])->validate();
    }

    /**
     * Save the application data to the database.
     *
     * @param array $data
     * @return \App\Models\ApplicationForm
     */
    public function saveApplication(array $data): ApplicationForm
    {
        // Placeholder for saving logic, e.g., 
        return ApplicationForm::create($data); // Return the saved application data for now
    }
}