<?php

namespace App\Services;

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\ApplicationForm;
use App\Models\EnrollmentPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationFormService
{

    public function __construct(
        protected AcademicTermService $academicTermService,
        protected EnrollmentPeriodService $enrollmentPeriodService
    ) {}

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

    public function createApplication(Applicants $applicant, array $form)
    {

        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        if (!$activeTerm) {
            throw new \InvalidArgumentException('No active academic term found. Please activate an academic term first.');
        }

        $enrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($activeTerm->id);

        if (!$enrollmentPeriod) {
            throw new \InvalidArgumentException('No enrollment period found. Please setup an enrollment period first.');
        }

        return DB::transaction(function () use ($applicant, $activeTerm, $enrollmentPeriod, $form) {

            //update the applicant
            $applicant->update([
                'track_id' => $form['primary_track'],
                'program_id' => $form['secondary_track'],
                'application_status' => 'Pending'
            ]);

            //create the application form
            $applicant->applicationForm()->create([
                'academic_terms_id'          => $activeTerm->id,
                'enrollment_period_id'       => $enrollmentPeriod->id,

                'preferred_sched'            => $form['preferred_sched'],
                'is_returning'               => $form['is_returning'],
                'lrn'                        => $form['lrn'],
                'grade_level'                => $form['grade_level'],
                'acad_term_applied'          => $activeTerm->year,
                'semester_applied'           => $activeTerm->semester,
                'admission_date'             => Carbon::now(),

                'last_name'                  => $form['last_name'],
                'first_name'                 => $form['first_name'],
                'middle_name'                => $form['middle_name'],
                'extension_name'             => $form['extension_name'],
                'gender'                     => $form['gender'],
                'birthdate'                  => $form['birthdate'],
                'age'                        => $form['age'],
                'place_of_birth'             => $form['place_of_birth'],
                'mother_tongue'              => $form['mother_tongue'],
                'belongs_to_ip'              => $form['belongs_to_ip'],
                'is_4ps_beneficiary'         => $form['is_4ps_beneficiary'],
                'contact_number'             => $form['contact_number'],

                'cur_house_no'               => $form['cur_house_no'],
                'cur_street'                 => $form['cur_street'],
                'cur_barangay'               => $form['cur_barangay'],
                'cur_city'                   => $form['cur_city'],
                'cur_province'               => $form['cur_province'],
                'cur_country'                => $form['cur_country'],
                'cur_zip_code'               => $form['cur_zip_code'],

                'perm_house_no'              => $form['perm_house_no'],
                'perm_street'                => $form['perm_street'],
                'perm_barangay'              => $form['perm_barangay'],
                'perm_city'                  => $form['perm_city'],
                'perm_province'              => $form['perm_province'],
                'perm_country'               => $form['perm_country'],
                'perm_zip_code'              => $form['perm_zip_code'],

                'father_last_name'           => $form['father_last_name'],
                'father_first_name'          => $form['father_first_name'],
                'father_middle_name'         => $form['father_middle_name'],
                'father_contact_number'      => $form['father_contact_number'],
                'mother_last_name'           => $form['mother_last_name'],
                'mother_first_name'          => $form['mother_first_name'],
                'mother_middle_name'         => $form['mother_middle_name'],
                'mother_contact_number'      => $form['mother_contact_number'],
                'guardian_last_name'         => $form['guardian_last_name'],
                'guardian_first_name'        => $form['guardian_first_name'],
                'guardian_middle_name'       => $form['guardian_middle_name'],
                'guardian_contact_number'    => $form['guardian_contact_number'],
                'has_special_needs'          => $form['has_special_needs'],
                'special_needs'              => $form['special_needs'] ?? null,

                'last_grade_level_completed' => $form['last_grade_level_completed'],
                'last_school_attended'       => $form['last_school_attended'],
                'last_school_year_completed' => $form['last_school_year_completed'],
                'school_id'
            ]);
        });
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
            'primary_track'              => 'required|exists:tracks,id',
            'secondary_track'            => 'nullable|exists:program,id',
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
        }

        return $form;
    }
}
