<?php

namespace App\Services;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentService
{

    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}

    public function enrollStudent($applicant)
    {
        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        if (!$activeTerm) {
            throw new \InvalidArgumentException('No active academic term found. Please activate an academic term first.');
        }

        $form = $applicant->applicationForm;

        if (!$form) {
            throw new \InvalidArgumentException('No application form found.');
        }

        $user = $applicant->user;

        if (!$user) {
            throw new \InvalidArgumentException('No user found.');
        }


        return DB::transaction(function () use ($form, $user, $applicant, $activeTerm) {
            $student = Student::firstOrCreate(
                [
                    'user_id'         => $user->id,
                ],
                [
                    'lrn'             => $form->lrn,
                    'track_id'        => $applicant->track_id,
                    'program_id'      => $applicant->program_id,
                    'grade_level'     => $form->grade_level,
                    'enrollment_date' => Carbon::now()->toDateString(),
                    'status'          => 'Officially Enrolled'
                ]
            );

            $user->syncRoles('student');

            $student->record()->firstOrCreate([
                'middle_name'             => $form->middle_name,
                'extension_name'          => $form->extension_name,
                'birthdate'               => $form->birthdate,
                'gender'                  => $form->gender,
                'age'                     => $form->age,
                'place_of_birth'          => $form->place_of_birth,
                'mother_tongue'           => $form->mother_tongue,

                'contact_number'          => $form->contact_number,
                'current_address'         => $form->currentAddress(),
                'permanent_address'       => $form->permanentAddress(),

                'house_no'                => $form->cur_house_no,
                'street'                  => $form->cur_street,
                'barangay'                => $form->cur_barangay,
                'city'                    => $form->cur_city,
                'province'                => $form->cur_province,
                'country'                 => $form->cur_country,
                'zip_code'                => $form->cur_zip_code,

                'father_name'             => $form->fatherFullName(),
                'father_contact_number'   => $form->father_contact_number,
                'mother_name'             => $form->motherFullName(),
                'mother_contact_number'   => $form->mother_contact_number,
                'guardian_name'           => $form->guardianFullName(),
                'guardian_contact_number' => $form->guardian_contact_number,

                'last_school_attended'    => $form->last_school_attended,
                'last_grade_level_completed' => $form->last_grade_level_completed,
                'school_id'                  => $form->school_id,
                'acad_term_applied'       => $form->acad_term_applied,
                'semester_applied'        => $form->semester_applied,
                'admission_date'          => $form->admission_date,

                'has_special_needs'       => $form->has_special_needs,
                'special_needs'           => $form->special_needs,
                'belongs_to_ip'           => $form->belongs_to_ip,
                'is_4ps_beneficiary'      => $form->is_4ps_beneficiary,
            ]);

            foreach ($applicant->assignedDocuments as $doc) {
                $student->assignedDocuments()->create([
                    'documents_id'   => $doc->documents_id,
                    'status'        => $doc->status,
                    'submit_before' => $doc->submit_before,
                ]);
            }

            $applicant->submissions()->update([
                'owner_id'   => $student->id,
                'owner_type' => Student::class,
            ]);

            $student->enrollments()->firstOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_term_id' => $activeTerm->id,
                ],
                [
                    'status' => 'enrolled',
                    'program_id' => null, // Can be set later
                    'section_id' => null, // Can be set later
                    'enrolled_at' => Carbon::now()
                ]
            );

            return $student;
        });
    }
}
