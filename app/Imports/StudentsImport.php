<?php

namespace App\Imports;

use App\Models\Documents;
use App\Models\Student;
use App\Models\User;
use App\Models\AcademicTerms;
use App\Models\StudentEnrollment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithSkipDuplicates, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $defaultPassword;

    public function __construct()
    {
        $this->defaultPassword = Hash::make('default_password');
    }


    public function headingRow(): int
    {
        return 6;
    }

    // validate required columns per row
    public function rules(): array
    {
        return [
            '*.lrn'            => 'required|numeric',
            '*.last_name'      => 'required|string',
            '*.first_name'     => 'required|string',
            '*.grade_level'    => 'required|string',
            '*.program'        => 'required|string',
            '*.contact_number' => 'nullable|string',
            '*.email_address'  => 'nullable|email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.lrn.numeric' => 'LRN must be a number.',
            '*.email_address.email' => 'Email address must be valid.',
        ];
    }

    public function model(array $row)
    {

        $required_docs = Documents::all();

        $user = User::firstOrCreate(
            ['email' => $row['email_address']],
            [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email_address'],
                'password' => $this->defaultPassword,
            ]
        );

        if (! $user->hasRole('student')) {
            $user->assignRole('student');
        }

        $students = $user->student()->updateOrCreate(
            ['lrn'            => $row['lrn']],
            [
                'first_name'     => $row['first_name'],
                'last_name'      => $row['last_name'],
                'grade_level'    => $row['grade_level'],
                'program'        => $row['program'],
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
                'age'                     => null,
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
                'program'                 => $students->program,
                'current_school'          => null,
                'previous_school'         => null,
                'school_contact_info'     => null,

                'has_special_needs'       => null,
                'belongs_to_ip'           => null,
                'is_4ps_beneficiary'      => null,
            ]
        );


        // assign documents to student
        foreach ($required_docs as $doc) {
            $students->assignedDocuments()->create([
                'documents_id'  => $doc->id,
                'status'        => 'not-submitted', // default
                'submit-before' =>  null,
            ]);
        }

        $students->submissions()->update([
            'owner_id'   => $students->id,
            'owner_type' => Student::class,
        ]);

        // Auto-enroll imported students in the active academic term
        if (config('app.use_term_enrollments')) {
            $activeTerm = AcademicTerms::where('is_active', true)->first();
            
            if ($activeTerm) {
                StudentEnrollment::firstOrCreate(
                    [
                        'student_id' => $students->id,
                        'academic_term_id' => $activeTerm->id,
                    ],
                    [
                        'status' => 'enrolled',
                        'enrolled_at' => now(),
                    ]
                );
            }
        }

        return $students;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
