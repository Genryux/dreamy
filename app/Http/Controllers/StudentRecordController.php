<?php

namespace App\Http\Controllers;

use App\Exceptions\StudentRecordException;
use App\Models\Applicants;
use App\Models\StudentRecords;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $applicant = Applicants::find($request->id);

        //dd($applicant->applicationForm->get(), $form->first_name);
        try {

            DB::transaction(function () use ($applicant) {
                $form = $applicant->applicationForm->first();
                $user = $applicant->user;


                $student = Students::firstOrCreate([
                    'user_id' => $user->id,
                    'lrn' => $form->lrn,
                    'grade_level' => $form->grade_level,
                    'enrollment_date' => $form->created_by,
                    'status' => 'Officially Enrolled'
                ]);

                $user->syncRoles('student');
                $studentId = $student->id;

                StudentRecords::firstOrCreate([
                    'students_id'             => $studentId,
                    'user_id'                 => $user->id,
                    'first_name'              => $form->first_name,
                    'last_name'               => $form->last_name,
                    'middle_name'             => $form->middle_name,
                    'extension_name'          => $form->extension_name,
                    'birthdate'               => $form->birthdate,
                    'age'                     => $form->age,
                    'place_of_birth'          => $form->place_of_birth,
                    'email'                   => $applicant->user->email,
                    'current_address'         => $form->currentAddress(),
                    'permanent_address'       => $form->permanentAddress(),
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
            });

            return response()->json(['message' => 'Student record created.']);
        } catch (StudentRecordException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentRecords $studentRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentRecords $studentRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentRecords $studentRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRecords $studentRecord)
    {
        //
    }
}
