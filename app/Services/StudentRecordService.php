<?php

namespace App\Services;

use App\Exceptions\StudentRecordException;
use App\Models\StudentRecords;

class StudentRecordService
{

    public function createStudentRecord($applicant)
    {

        try {
            $form = $applicant->applicationForm->get();

            StudentRecords::firstOrCreate([
                'first_name'              => $form->first_name,
                'last_name'               => $form->last_name,
                'middle_name'             => $form->middle_name,
                'extension_name'          => $form->extension_name,
                'birthdate'               => $form->birthdate,
                'age'                     => $form->age,
                'place_of_birth'          => $form->place_of_birth,
                'email'                   => $form->email,
                'current_address'         => $form->currentAddress(),
                'permanent_address'       => $form->permanentAddress(),
                'contact_number'          => $form->number,
                'father_name'             => $form->fatherFullName(),
                'father_contact_number'   => $form->father_contact_number,
                'mother_name'             => $form->motherFullName(),
                'mother_contact_number'   => $form->mother_contact_number,
                'guardian_name'           => $form->guardianFullName(),
                'guardian_contact_number' => $form->guardian_contact_number,
                'semester'                => $form->semester,
                'current_school'          => 'Dreamy School Philippines',
                'previous_school'         => $form->last_school_attended,
                'has_special_needs'       => $form->has_special_needs,
                'special_needs'           => $form->special_needs,
                'belongs_to_ip'           => $form->belongs_to_ip,
                'is_4ps_beneficiary'      => $form->is_4ps_beneficiary,
            ]);

            return response()->json(['message' => 'Student record created.']);

        } catch (\Throwable $th) {
            throw new StudentRecordException();
        }
    }
}
