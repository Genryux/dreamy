<?php

namespace App\Services;

use App\Models\EnrollmentPeriod;
use App\Models\Student;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StudentService
{

    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}

    public function enrollStudent($applicant)
    {
        // Get the intended academic term from the applicant's enrollment period
        // This ensures students are enrolled in the term they applied for (current or future)
        $enrollmentPeriod = $applicant->enrollmentPeriod;
        
        if (!$enrollmentPeriod) {
            throw new \InvalidArgumentException('No enrollment period found for applicant.');
        }

        $intendedTerm = $enrollmentPeriod->academicTerms;

        if (!$intendedTerm) {
            // Fallback to current active term if enrollment period has no academic term
            $intendedTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        }

        if (!$intendedTerm) {
            throw new \InvalidArgumentException('No academic term found. Please ensure the enrollment period is linked to an academic term.');
        }

        $form = $applicant->applicationForm;

        if (!$form) {
            throw new \InvalidArgumentException('No application form found.');
        }

        $user = $applicant->user;

        if (!$user) {
            throw new \InvalidArgumentException('No user found.');
        }


        return DB::transaction(function () use ($form, $user, $applicant, $intendedTerm) {
            $student = Student::firstOrCreate(
                [
                    'user_id'         => $user->id,
                ],
                [
                    'enrollment_period_id' => $applicant->enrollment_period_id,
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
                    'academic_term_id' => $intendedTerm->id,
                ],
                [
                    'status' => 'enrolled',
                    'program_id' => $applicant->program_id,
                    'section_id' => $student->section_id,
                    'enrolled_at' => Carbon::now()
                ]
            );

            return $student;
        });
    }

    public function promoteStudents($previousTerm, $newTerm)
    {
        $continuingStudents = collect();

        // Create an enrollment period exclusively for continuing/old students
        $continuingEnrollmentPeriod = $this->createContinuingStudentEnrollmentPeriod($newTerm);

        // Filter students who were enrolled in the PREVIOUS term (the one being ended)
        // These are the students who need to be promoted to the new term
        $studentQuery = Student::with(['enrollments' => function($q) use ($previousTerm) {
            $q->where('academic_term_id', $previousTerm->id);
        }])
        ->whereHas('enrollments', function ($q) use ($previousTerm) {
            $q->where('academic_term_id', $previousTerm->id);
        });

        $studentQuery->chunk(500, function ($students) use (&$continuingStudents, $newTerm, $continuingEnrollmentPeriod) {

            foreach ($students as $student) {

                if ($student->status === 'Graduated') {
                    continue;
                } else if ($student->grade_level === 'Grade 11' && $student->academic_status === 'Passed') {
                    $student->update([
                        'grade_level' => 'Grade 12',
                        'academic_status' => null // cleared for the next term
                    ]);

                    $this->updateOrCreateEnrollment($student, $newTerm, $continuingEnrollmentPeriod);
                    $continuingStudents->push($student);
                } else if ($student->grade_level === 'Grade 11' && $student->academic_status === 'Failed') {
                    $student->update([
                        'academic_status' => null
                    ]);

                    $this->updateOrCreateEnrollment($student, $newTerm, $continuingEnrollmentPeriod);
                    $continuingStudents->push($student);
                } else if ($student->grade_level === 'Grade 12' && $student->academic_status === 'Failed') {
                    $student->update([
                        'academic_status' => null
                    ]);

                    $this->updateOrCreateEnrollment($student, $newTerm, $continuingEnrollmentPeriod);
                    $continuingStudents->push($student);
                } else if ($student->grade_level === 'Grade 12' && $student->academic_status === 'Completed') {
                    $student->update([
                        'status' => 'Graduated',
                        'section_id' => null
                    ]);

                    $student->enrollments()->update([
                        'status' => null,
                    ]);
                } else if ($student->grade_level === 'Grade 12' && $student->academic_status === null) {
                    $student->update([
                        'status' => 'Graduated',
                        'section_id' => null
                    ]);

                    $student->enrollments()->update([
                        'status' => null,
                    ]);
                } else if ($student->grade_level === 'Grade 11' && $student->academic_status === null) {
                    $student->update([
                        'grade_level' => 'Grade 12',
                        'academic_status' => null // cleared for the next term
                    ]);

                    $this->updateOrCreateEnrollment($student, $newTerm, $continuingEnrollmentPeriod);
                    $continuingStudents->push($student);
                }
            }
        });

        if ($continuingStudents->isEmpty()) {
            \Log::info('No students found or eligible for promotion');
            return $continuingStudents; // Return empty collection instead of JSON response
        }

        return $continuingStudents;
    }

    /**
     * Create an enrollment period for continuing/old students
     * This period is used to control when old students can confirm their re-enrollment
     */
    private function createContinuingStudentEnrollmentPeriod($newTerm)
    {
        // Check if an enrollment period for old students already exists for this term
        $existingPeriod = EnrollmentPeriod::where('academic_terms_id', $newTerm->id)
            ->where('period_for', 'old')
            ->first();

        if ($existingPeriod) {
            // Reactivate if it exists but is inactive
            if (!$existingPeriod->active) {
                $existingPeriod->update([
                    'active' => true,
                    'status' => 'Ongoing'
                ]);
            }
            return $existingPeriod;
        }

        // Create a new enrollment period for continuing students
        return EnrollmentPeriod::create([
            'academic_terms_id' => $newTerm->id,
            'name' => "Re-enrollment for Continuing Students",
            'application_start_date' => Carbon::now(),
            'application_end_date' => Carbon::now()->addDays(30), // Default 30 days, admin can adjust
            'max_applicants' => null, // No limit for re-enrollment
            'status' => 'Ongoing',
            'active' => true,
            'period_type' => 'regular',
            'period_for' => 'old', // This marks it as for continuing students
            'early_discount_percentage' => 0,
        ]);
    }

    private function updateOrCreateEnrollment($student, $academicTerm, $enrollmentPeriod = null)
    {
        $latestEnrollment = $student->enrollments()->latest()->first();

        $enrollmentData = [
            'academic_term_id' => $academicTerm->id,
            'enrollment_period_id' => $enrollmentPeriod?->id,
            'status' => 'pending_confirmation',
            'enrolled_at' => null,
        ];

        if ($latestEnrollment) {
            $latestEnrollment->update($enrollmentData);
        } else {
            $student->enrollments()->create($enrollmentData);
        }

        $user = $student->user;

        $sharedNotificationId = 'enrollment-confirmation-' . time() . '-' . uniqid();

        $user->notify(new PrivateQueuedNotification(
            "Enrollment Confirmation!",
            "The new academic term has officially begun. Click this notification or head to your Dashboard to confirm your enrollment.",
            null,
            $sharedNotificationId
        ));

        Notification::route('broadcast', 'user.' . $user->id)
            ->notify(new PrivateImmediateNotification(
                "Enrollment Confirmation!",
                "The new academic term has officially begun. Click this notification or head to your Dashboard to confirm your enrollment.",
                null,
                $sharedNotificationId,
                'user.' . $student->id
            ));
    }

    public function countStudentStatuses()
    {
        return [
            'to_promote' => Student::where('grade_level', 'Grade 11')
                ->where(function ($query) {
                    $query->whereIn('academic_status', ['Passed'])
                        ->orWhereNull('academic_status');
                })->count(),

            'to_retain' => Student::whereIn('grade_level', ['Grade 11', 'Grade 12'])
                ->where('academic_status', 'Failed')->count(),

            'to_graduate' => Student::where('grade_level', 'Grade 12')
                ->where(function ($query) {
                    $query->whereIn('academic_status', ['Completed'])
                        ->orWhereNull('academic_status');
                })->count(),

            'not_evaluated' => Student::whereIn('grade_level', ['Grade 11', 'Grade 12'])
                ->whereNull('academic_status')->count(),
        ];
    }
}
