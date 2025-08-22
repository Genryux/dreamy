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
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowExtractor;
use Maatwebsite\Excel\Validators\ValidationException as ValidatorsValidationException;

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
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Read just the heading row (array per sheet)
            $headingsArray = (new HeadingRowImport(6))->toArray($request->file('file'));
            // Assuming you only need the first sheet
            $headings = $headingsArray[0][0] ?? [];


            $required = [
                'lrn',
                'last_name',
                'first_name',
                'grade_level',
                'program',
                'contact_number',
                'email_address'
            ];

            foreach ($required as $col) {
                if (! in_array($col, $headings)) {
                    return response()->json(
                        [
                            'error' =>
                            "The uploaded file does not match the required template. Missing required column: {$col}"
                        ],
                        422
                    );
                }
            }

            // Check succeeding rows after row 6
            $rows = Excel::toArray(new \stdClass, $request->file('file'))[0];
            $dataRows = array_slice($rows, 6); // rows after heading row

            // Filter out rows that are completely empty
            $nonEmptyRows = array_filter($dataRows, function ($row) {
                return array_filter($row); // remove empty values, see if anything left
            });

            if (count($nonEmptyRows) === 0) {
                return response()->json(
                    [
                        'success' =>
                        "Import completed successfully, but no student data was found."
                    ],
                    422
                );
            }

            // Use the job instead of Excel::queueImport
            Excel::import(new StudentsImport, $request->file('file'));

            return response()->json(['success' => 'Import completed successfully']);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => "Some data fields in your uploaded file are not valid."
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Something went wrong during import. Please try again."
            ], 500);
        }
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
                        'program'         => $form->primary_track,
                        'contact_number'  => $form->contact_number,
                        'email_address'   => $applicant->user->email,
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
                    'acad_term_applied'       => $form->acad_term_applied,
                    'semester_applied'        => $form->semester_applied,
                    'admission_date'          => $form->admission_date,

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

        // $email = $studentRecord->student;

        // $student = $studentRecord->students;

        // $record = $student->record;

        // dd($student, $record);
        // dd($studentRecord->student(), $email);

        // $record = $studentRecordId->all();

        // dd($record, $studentRecordId)
        return view('user-admin.enrolled-students.show', compact('studentRecord'));
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
