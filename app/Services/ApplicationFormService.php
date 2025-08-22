<?php

namespace App\Services;

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\ApplicationForm;
use App\Models\EnrollmentPeriod;
use Illuminate\Support\Facades\Log;

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
        return Applicants::find($applicantId);
    }

    public function fetchApplicationWithAnyStatus(array $status)
    {
        return Applicants::withAnyStatus($status);
    }

    public function fetchApplicationWithStatus(string $status)
    {
        return Applicants::withStatus($status);
    }

    public function fetchRecentPendingApplications(int $limit = 10)
    {
        return Applicants::withStatus('Pending')
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
        ?Applicants $applicant,
        ?AcademicTerms $academicTerm,
        ?EnrollmentPeriod $activeEnrollmentPeriod

    ): ApplicationForm {
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

            'preferred_sched'            => 'required|string',
            'is_returning'               => 'required|boolean',
            'lrn'                        => 'nullable|digits:12|unique:application_forms,lrn',
            'grade_level'                => 'required|string',
            'primary_track'              => 'required|string',
            'secondary_track'            => 'nullable|string',
            'last_name'                  => 'required|string',
            'first_name'                 => 'required|string',
            'middle_name'                => 'nullable|string',
            'extension_name'             => 'nullable|string',
            'birthdate'                  => 'required|date|before:today',
            'age'                        => 'required|integer',
            'gender'                     => 'required|string',
            'contact_number'             => 'required|string',
            'place_of_birth'             => 'required|string',
            'mother_tongue'              => 'nullable|string',
            'belongs_to_ip'              => 'nullable|boolean',
            'is_4ps_beneficiary'         => 'nullable|boolean',

            'cur_house_no'               => 'required|string',
            'cur_street'                 => 'nullable|string',
            'cur_barangay'               => 'required|string',
            'cur_city'                   => 'required|string',
            'cur_province'               => 'required|string',
            'cur_country'                => 'required|string',
            'cur_zip_code'               => 'required|numeric',

            'perm_house_no'              => 'required|string',
            'perm_street'                => 'nullable|string',
            'perm_barangay'              => 'required|string',
            'perm_city'                  => 'required|string',
            'perm_province'              => 'required|string',
            'perm_country'               => 'required|string',
            'perm_zip_code'              => 'required|numeric',

            'father_last_name'           => 'required|string',
            'father_first_name'          => 'required|string',
            'father_middle_name'         => 'nullable|string',
            'father_contact_number'      => 'nullable|string',
            'mother_last_name'           => 'required|string',
            'mother_first_name'          => 'required|string',
            'mother_middle_name'         => 'nullable|string',
            'mother_contact_number'      => 'nullable|string',
            'guardian_last_name'         => 'required|string',
            'guardian_first_name'        => 'required|string',
            'guardian_middle_name'       => 'nullable|string',
            'guardian_contact_number'    => 'required|string',
            'has_special_needs'          => 'nullable|boolean',
            'special_needs'              => 'nullable|array',

            'last_grade_level_completed' => 'nullable|integer',
            'last_school_attended'       => 'nullable|string',
            'last_school_year_completed' => 'nullable|date|before:now',
            'school_id'                  => 'nullable|string',
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
        $form = new ApplicationForm();
        // dd($form);
        $form->fill($data);

        if (!$form->save()) {
            Log::error('Failed to save application form.', ['data' => $data]);
            throw new \Exception('Application form could not be saved.');
            dd("alsddkadklasdjklasjkl");
        }

        return $form;
    }
}
