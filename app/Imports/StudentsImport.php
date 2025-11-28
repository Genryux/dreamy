<?php

namespace App\Imports;

use App\Mail\StudentAccountCreatedMail;
use App\Models\Documents;
use App\Models\Student;
use App\Models\User;
use App\Models\AcademicTerms;
use App\Models\Program;
use App\Models\StudentEnrollment;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithSkipDuplicates, WithValidation, WithMapping
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $defaultPassword;

    public function __construct()
    {
        // $this->defaultPassword = Hash::make('default_password');
    }


    public function headingRow(): int
    {
        return 6;
    }

    /**
     * Map the data to ensure proper data types before validation
     */
    public function map($row): array
    {
        return [
            'lrn' => $row['lrn'], // Keep as numeric
            'first_name' => (string) $row['first_name'],
            'last_name' => (string) $row['last_name'],
            'grade_level' => (string) $row['grade_level'],
            'program' => (string) $row['program'],
            'contact_number' => (string) $row['contact_number'],
            'email_address' => (string) $row['email_address'],
        ];
    }

    // validate required columns per row
    public function rules(): array
    {
        return [
            '*.lrn'            => 'required|numeric',
            '*.last_name'      => 'required|string',
            '*.first_name'     => 'required|string',
            '*.grade_level'    => 'required|string',
            '*.program'        => 'nullable|string',
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

        // find program_id with program code
        $program = Program::where('code', (string) $row['program'])->first();

        if (!$program) {
            $program = null;
        }

        // find track_id with program_id (only if program exists)
        $track = null;
        if ($program) {
            $track = Track::find($program->id);
        }

        $plainPassword = $this->generateStrongPassword(8);

        $user = User::firstOrCreate(
            ['email' => $row['email_address']],
            [
                'first_name' => (string) $row['first_name'],
                'last_name' => (string) $row['last_name'],
                'email' => (string) $row['email_address'],
                'password' => Hash::make($plainPassword),
            ]
        );

        if (! $user->hasRole('student')) {
            $user->assignRole('student');
        }

        // Send email notification to student after account creation (only for newly created users)
        if (
            $user->wasRecentlyCreated &&
            !empty($row['email_address']) &&
            filter_var($row['email_address'], FILTER_VALIDATE_EMAIL) &&
            !empty($user->first_name)
        ) {
            try {
                Mail::to($user->email)->queue(new StudentAccountCreatedMail($user, $plainPassword));
            } catch (\Exception $e) {
                Log::error('Failed to send student account creation email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $students = $user->student()->updateOrCreate(
            ['lrn'            => $row['lrn']],
            [
                'track_id'       => $track->id ?? null,
                'program_id'     => $program->id ?? null,
                'grade_level'    => (string) $row['grade_level'],
                'section_id'     => null,
                'enrollment_date' => null,
                'status'         => 'Officially Enrolled'
            ]
        );

        $students->record()->firstOrCreate(
            [
                'middle_name'      => null,
                'extension_name'   => null,
                'birthdate'        => null,
                'gender'           => null,
                'age'              => null,
                'place_of_birth'   => null,
                'mother_tongue'    => null,

                'contact_number'   => (string) $row['contact_number'],
                'current_address'  => null,
                'permanent_address' => null,

                'house_no'         => null,
                'street'           => null,
                'barangay'         => null,
                'city'             => null,
                'province'         => null,
                'country'          => null,
                'zip_code'         => null,

                'father_name'      => null,
                'father_contact_number' => null,
                'mother_name'      => null,
                'mother_contact_number' => null,
                'guardian_name'         => null,
                'guardian_contact_number' => null,

                'last_school_attended' => null,
                'last_grade_level_completed' => null,
                'school_id'         => null,
                'acad_term_applied' => null,
                'semester_applied'  => null,
                'admission_date'    => null,

                'has_special_needs' => null,
                'special_needs'     => null,
                'belongs_to_ip'     => null,
                'is_4ps_beneficiary' => null
            ]
        );


        // assign documents to student
        // foreach ($required_docs as $doc) {
        //     $students->assignedDocuments()->create([
        //         'documents_id'  => $doc->id,
        //         'status'        => 'Pending', // default
        //         'submit_before' =>  null,
        //     ]);
        // }

        // $students->submissions()->update([
        //     'owner_id'   => $students->id,
        //     'owner_type' => Student::class,
        // ]);

        // Auto-enroll imported students in the active academic term
        if (config('app.use_term_enrollments')) {
            $activeTerm = AcademicTerms::where('is_active', true)->first();

            if ($activeTerm) {
                $students->enrollments()->firstOrCreate(
                    [
                        'student_id' => $students->id,
                        'academic_term_id' => $activeTerm->id,
                    ],
                    [
                        'status' => 'enrolled',
                        'program_id' => null, // Can be set later
                        'section_id' => null, // Can be set later
                        'enrolled_at' => Carbon::now()
                    ]
                );
            }
        }

        return $students;
    }

    protected function generateStrongPassword($length = 10)
    {
        $upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower   = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $all = $upper . $lower . $numbers . $symbols;

        // Guarantee at least one of each type
        $password = substr(str_shuffle($upper), 0, 1) .
            substr(str_shuffle($lower), 0, 1) .
            substr(str_shuffle($numbers), 0, 1) .
            substr(str_shuffle($symbols), 0, 1);

        // Fill the rest randomly
        $remaining = $length - strlen($password);
        $password .= substr(str_shuffle(str_repeat($all, $remaining)), 0, $remaining);

        return str_shuffle($password);
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
