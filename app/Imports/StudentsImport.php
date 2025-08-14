<?php

namespace App\Imports;

use App\Models\Students;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{

    use Importable;
    use \Maatwebsite\Excel\Concerns\SkipsFailures;
    use \Maatwebsite\Excel\Concerns\SkipsErrors;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    // validate required columns per row
    public function rules(): array
    {
        return [
            'lrn'            => ['required'],
            'email_address'  => ['required', 'email'],
            // Allow either full_name OR first_name + last_name
            'full_name'      => ['required_without_all:first_name,last_name'],
            'first_name'     => ['required_without:full_name'],
            'last_name'      => ['required_without:full_name'],
            'grade_level'    => ['nullable', 'integer', 'between:1,12'],
            'age'            => ['nullable', 'integer', 'between:3,100'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'full_name.required_without_all' => 'Provide full_name or first_name + last_name.',
            'first_name.required_without'    => 'Provide first_name if full_name is missing.',
            'last_name.required_without'     => 'Provide last_name if full_name is missing.',
        ];
    }

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
                    'first_name'     => $row['first_name'],
                    'last_name'      => $row['last_name'],
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

    public function onFailure(Failure ...$failures)
    {
        // Row-level validation errors are logged (import continues for other rows)
        foreach ($failures as $f) {
            Log::warning('Student import validation failure', [
                'row' => $f->row(),
                'attribute' => $f->attribute(),
                'errors' => $f->errors(),
                'values' => $f->values(),
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
