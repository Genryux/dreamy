<?php

namespace App\Http\Controllers;

use App\Exceptions\StudentRecordException;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Applicants;
use App\Models\StudentRecords;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);


        $path = $request->file('file')->store('imports');

        Excel::queueImport(new StudentsImport, $path, 'local');

        return back()->with('info', 'Your import is being processed in the background and may take a few moments. You can continue working and check back later for the results.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $applicant = Applicants::where('applicants.id', $request->id)->first();

        //dd($applicant->applicationForm);
        try {

            DB::transaction(function () use ($applicant) {
                $form = $applicant->applicationForm;
                $user = $applicant->user;


                $student = Students::firstOrCreate(
                    [
                        'user_id'         => $user->id,
                    ],
                    [

                        'lrn'             => $form->lrn,
                        'first_name'      => $user->first_name,
                        'last_name'       => $user->last_name,
                        'grade_level'     => $form->grade_level,
                        'age'             => $form->age,
                        'contact_number'  => $form->contact_number,
                        'email_addres'    => $applicant->user->email,
                        'enrollment_date' => $form->created_by,
                        'status'          => 'Officially Enrolled'
                    ]
                );

                $user->syncRoles('student');
                $studentId = $student->id;

                StudentRecords::firstOrCreate([
                    'students_id'             => $studentId,
                    'first_name'              => $form->first_name,
                    'last_name'               => $form->last_name,
                    'middle_name'             => $form->middle_name,
                    'extension_name'          => $form->extension_name,
                    'birthdate'               => $form->birthdate,
                    'gender'                  => $form->gender,
                    'age'                     => $form->age,
                    'place_of_birth'          => $form->place_of_birth,
                    'contact_number'          => $form->contact_number,
                    'email'                   => $applicant->user->email,
                    'current_address'         => $form->currentAddress(),
                    'permanent_address'       => $form->permanentAddress(),
                    'father_name'             => $form->fatherFullName(),
                    'father_contact_number'   => $form->father_contact_number,
                    'mother_name'             => $form->motherFullName(),
                    'mother_contact_number'   => $form->mother_contact_number,
                    'guardian_name'           => $form->guardianFullName(),
                    'guardian_contact_number' => $form->guardian_contact_number,
                    'grade_level'             => $form->grade_level,
                    'program'                 => $form->primary_track,
                    'current_school'          => 'Dreamy School Philippines',
                    'previous_school'         => $form->last_school_attended,
                    'has_special_needs'       => $form->has_special_needs,
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
