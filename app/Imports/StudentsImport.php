<?php

namespace App\Imports;

use App\Models\Students;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (empty($row['email_address']) || empty($row['lrn'])) {
            return null;
        }

        return DB::transaction(function () use ($row) {
            $user = User::firstOrCreate(
                ['email' => $row['email_address']],
                [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email_address'],
                    'password' => Hash::make('default_password'),
                ]
            );

            if (! $user->hasRole('student')) {
                $user->assignRole('student');
            }

            $students = $user->student()->firstOrCreate(
                ['lrn'            => $row['lrn']],
                [
                    'full_name'     => $row['first_name'] . ' ' . $row['last_name'],
                    'grade_level'    => $row['grade_level'],
                    'age'            => $row['age'],
                    'contact_number' => $row['contact_number'],
                    'email_address'  => $row['email_address'],
                    'grade_level'    => $row['grade_level'],
                    'status'         => 'Officially Enrolled'
                ]
            );

            $students->record()->firstOrCreate(
                [
                    'first_name'              => $row['first_name'],
                    'last_name'               => $row['last_name'],
                    'middle_name'             => null,
                    'birthdate'               => null,
                    'gender'                  => null,
                    'age'                     => $students->age,
                    'place_of_birth'          => null,

                    'email'                   => $students->email_address,
                    'contact_number'          => $students->contact_number,
                    'current_address'         => null,
                    'permanent_address'       => null,

                    'father_name'             => null,
                    'father_contact_number'   => null,
                    'mother_name'             => null,
                    'mother_contact_number'   => null,
                    'guardian_name'           => null,
                    'guardian_contact_number' => null,

                    'grade_level'             => $students->grade_level,
                    'program'                 => null,
                    'current_school'          => null,
                    'previous_school'         => null,
                    'school_contact_info'     => null,

                    'has_special_needs'       => null,
                    'belongs_to_ip'           => null,
                    'is_4ps_beneficiary'      => null,
                ]
            );

            return $students;
        });
    }
}
