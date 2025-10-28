<?php

namespace App\Http\Controllers;

use App\Exceptions\StudentRecordException;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Mail\ApplicantProgressMail;
use App\Models\Applicants;
use App\Models\DocumentSubmissions;
use App\Models\Invoice;
use App\Models\Program;
use App\Models\StudentRecord;
use App\Models\Student;
use App\Services\AcademicTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowExtractor;
use Maatwebsite\Excel\Validators\ValidationException as ValidatorsValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Services\StudentService;
use Carbon\Carbon;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Mail;

class StudentRecordController extends Controller
{
    public function __construct(
        protected AcademicTermService $academic_term_service,
        protected StudentService $student_service,
        protected InvoiceService $invoiceService
    ) {}
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
        // Check if there's an active academic term
        $academicTermService = app(AcademicTermService::class);
        $currentTerm = $academicTermService->fetchCurrentAcademicTerm();

        if (!$currentTerm) {
            return redirect()->back()->with('error', 'No active academic term found. Please activate an academic term before exporting students.');
        }

        try {
            $filename = 'officially_enrolled_students_' . $currentTerm->year . '_' . $currentTerm->semester . '.xlsx';

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'exported_students',
                    'academic_term' => $currentTerm->year . ' ' . $currentTerm->semester,
                    'filename' => $filename,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Students data exported to Excel');

            return Excel::download(new StudentsExport, $filename);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong while exporting');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Check if there's an active academic term
        $academicTermService = app(AcademicTermService::class);
        $currentTerm = $academicTermService->fetchCurrentAcademicTerm();

        if (!$currentTerm) {
            return response()->json(['error' => 'No active academic term found. Please activate an academic term before importing students.']);
        }

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

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'imported_students',
                    'academic_term' => $currentTerm->year . ' ' . $currentTerm->semester,
                    'filename' => $request->file('file')->getClientOriginalName(),
                    'file_size' => $request->file('file')->getSize(),
                    'file_type' => $request->file('file')->getMimeType(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Students data imported from Excel');

            return response()->json(['success' => 'Import completed successfully']);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => "Some data fields in your uploaded file are not valid. {$e}"
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Something went wrong during import. Please try again. {$e}",
                'code' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $applicant = Applicants::where('applicants.id', $request->id)->first();

        $auto_assign = $request->input(['auto_assign']);

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        if (!$applicant->applicationForm) {
            return response()->json(['error' => 'Application form not found for this applicant'], 404);
        }

        try {

            $student = DB::transaction(function () use ($applicant, $auto_assign) {

                $applicant->update(['application_status' => 'Officially Enrolled']);

                $enrolledStudent = $this->student_service->enrollStudent($applicant);

                // Log the activity
                activity('application')
                    ->causedBy(auth()->user())
                    ->performedOn($applicant)
                    ->withProperties([
                        'action' => 'enrolled_student',
                        'applicant_id' => $applicant->applicant_id,
                        'applicant_name' => $applicant->first_name . ' ' . $applicant->last_name,
                        'student_id' => $enrolledStudent->id,
                        'program_id' => $applicant->program_id,
                        'grade_level' => $applicant->applicationForm->grade_level ?? 'Unknown',
                        'auto_assign_fees' => $auto_assign === '1',
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ])
                    ->log('Student officially enrolled');

                return $enrolledStudent;
            });

            $recipientEmail = $applicant->user->email;
            $loginUrl = config('app.url') . '/portal/login';

            // Send exam result email regardless of school fees assignment
            if ($recipientEmail) {
                $title = 'Official Enrollment Confirmation — Dreamy School Enrollment';
                $body = "Congratulations! You are now officially enrolled. Please log in to your portal to see important instructions and next steps.";
                Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                    applicantName: $applicant->first_name ?? 'Applicant',
                    title: $title,
                    bodyText: $body,
                    loginUrl: $loginUrl
                ));
            }

            if ($auto_assign === '1') {

                try {
                    $invoice = $this->invoiceService->assignInvoiceAfterPromotion($student->id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Student enrollment has been completed successfully, and the relevant school fees have been set.',
                        'student_id' => $student->id,
                        'invoice_id' => $invoice->id
                    ]);
                } catch (\InvalidArgumentException $e) {
                    // Invoice assignment failed, but enrollment was successful
                    return response()->json([
                        'success' => true,
                        'message' => 'Enrollment successful, but the school fees could not be assigned. Please assign manually.',
                        'student_id' => $student->id,
                        'warning' => $e->getMessage()
                    ]);
                } catch (\Exception $e) {
                    // Log invoice assignment error but don't fail the enrollment
                    Log::warning('Invoice assignment failed after successful enrollment', [
                        'student_id' => $student->id,
                        'error' => $e->getMessage()
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Enrollment successful, but the school fees could not be assigned. Please assign manually.',
                        'student_id' => $student->id
                    ]);
                }
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Student enrollment has been completed successfully.',
                    'student_id' => $student->id,
                ]);
            }
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422); // Unprocessable Entity
        } catch (\Exception $e) {
            Log::error('Student enrollment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => "An unexpected error occurred. Please try again. $e"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {

        $assignedDocuments = $student->assignedDocuments()
            ->with(['documents', 'submissions'])
            ->get();

        // dd($assignedDocuments);

        // Preload submissions for this student
        $submissions = DocumentSubmissions::where('owner_id', $student->id)
            ->where('owner_type', Student::class)
            ->get()
            ->groupBy('documents_id');

        // // Map assigned docs to include latest submission
        $assignedDocuments->each(function ($doc) use ($submissions) {
            $doc->latest_submission = $submissions->get($doc->documents_id)
                ? $submissions->get($doc->documents_id)->sortByDesc('submitted_at')->first()
                : null;
        });

        $programs = Program::all();

        if (!$programs) {
            $programs = null;
        }

        $sections = Section::where('year_level', $student->grade_level)->where('program_id', $student->program_id)->get();

        if (!$sections) {
            $sections = null;
        }
        
        // Get the current active academic term for the student
        $currentEnrollment = $student->getCurrentAcademicTerm();
        
        if ($currentEnrollment) {
            $acadTerm = $currentEnrollment->academicTerm;
        } else {
            // Fallback to latest enrollment if no active term
            $latestEnrollment = $student->getLatestAcademicTerm();
            $acadTerm = $latestEnrollment ? $latestEnrollment->academicTerm : 'No Academic Term';
        }

        // dd($record, $studentRecordId)
        return view('user-admin.enrolled-students.show', compact('student', 'assignedDocuments', 'programs', 'sections', 'acadTerm'));
    }

    /**
     * Render a clean COE preview for embedding/printing
     */
    public function coePreview(StudentRecord $studentRecord)
    {
        $student = $studentRecord->student;
        $school = SchoolSetting::query()->first();
        
        // Get academic term fallback
        $currentEnrollment = $student->getCurrentAcademicTerm();
        if ($currentEnrollment) {
            $acadTerm = $currentEnrollment->academicTerm;
        } else {
            $latestEnrollment = $student->getLatestAcademicTerm();
            $acadTerm = $latestEnrollment ? $latestEnrollment->academicTerm : null;
        }
        
        // Use the same view as the PDF to keep preview and download consistent
        return view('pdf.coe', compact('studentRecord', 'school', 'acadTerm'));
    }

    /**
     * Download/stream COE as a real PDF using Dompdf
     */
    public function coePdf(StudentRecord $studentRecord)
    {
        $student = $studentRecord->student;
        $school = SchoolSetting::query()->first();
        
        // Get academic term fallback
        $currentEnrollment = $student->getCurrentAcademicTerm();
        if ($currentEnrollment) {
            $acadTerm = $currentEnrollment->academicTerm;
        } else {
            $latestEnrollment = $student->getLatestAcademicTerm();
            $acadTerm = $latestEnrollment ? $latestEnrollment->academicTerm : null;
        }
        
        $pdf = Pdf::loadView('pdf.coe', [
            'studentRecord' => $studentRecord,
            'school' => $school,
            'acadTerm' => $acadTerm,
        ])->setPaper('letter')->setOptions([
            'isRemoteEnabled' => true,
        ]);

        // Log the activity
        activity('student_management')
            ->causedBy(auth()->user())
            ->performedOn($studentRecord->student)
            ->withProperties([
                'action' => 'generated_coe',
                'student_id' => $studentRecord->student->id,
                'student_name' => $studentRecord->student->user->first_name . ' ' . $studentRecord->student->user->last_name,
                'coe_id' => $studentRecord->id,
                'download_type' => request()->boolean('inline') ? 'preview' : 'download',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Certificate of Enrollment (COE) generated');

        if (request()->boolean('inline')) {
            // Stream inline for preview
            return $pdf->stream('COE-' . $studentRecord->id . '.pdf');
        }
        // Force download for button
        return $pdf->download('COE-' . $studentRecord->id . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentRecord $studentRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePersonalInfo(Request $request, Student $student)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'extension_name' => 'nullable|string|max:255',
                'birthdate' => 'required|date',
                'place_of_birth' => 'nullable|string|max:255',
            ]);

            DB::transaction(function () use ($validated, $student) {
                // Update user information
                $student->user->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                ]);

                // Update student record information
                $student->record->update([
                    'middle_name' => $validated['middle_name'],
                    'extension_name' => $validated['extension_name'],
                    'birthdate' => $validated['birthdate'],
                    'place_of_birth' => $validated['place_of_birth'],
                ]);
            });

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->withProperties([
                    'action' => 'updated_personal_info',
                    'student_id' => $student->id,
                    'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'updated_fields' => array_keys($validated),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Student personal information updated');

            return response()->json([
                'success' => true,
                'message' => 'Personal information updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update personal information', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating personal information'
            ], 500);
        }
    }

    public function updateAcademicInfo(Request $request, Student $student)
    {
        try {
            $validated = $request->validate([
                'lrn' => 'required|string|max:255',
                'grade_level' => 'required|string|in:Grade 11,Grade 12',
                'program_id' => 'required|exists:programs,id',
                'section' => 'nullable|string|max:255',
                'acad_term_applied' => 'nullable|string|max:255',
                'semester_applied' => 'nullable|string|in:1st Semester,2nd Semester',
            ]);

            DB::transaction(function () use ($validated, $student) {
                // Update student information
                $student->update([
                    'lrn' => $validated['lrn'],
                    'grade_level' => $validated['grade_level'],
                    'program_id' => $validated['program_id'],
                    'section_id' => $validated['section'],
                ]);

                $student->enrollments()->update([
                    'section_id' => $validated['section']
                ]);

                // Update student record information
                $student->record->update([
                    'acad_term_applied' => $validated['acad_term_applied'],
                    'semester_applied' => $validated['semester_applied'],
                ]);
            });

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->withProperties([
                    'action' => 'updated_academic_info',
                    'student_id' => $student->id,
                    'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'updated_fields' => array_keys($validated),
                    'new_grade_level' => $validated['grade_level'],
                    'new_program_id' => $validated['program_id'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Student academic information updated');

            return response()->json([
                'success' => true,
                'message' => 'Academic information updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update academic information', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating academic information'
            ], 500);
        }
    }

    public function updateAddressInfo(Request $request, Student $student)
    {
        try {
            $validated = $request->validate([
                'house_no' => 'nullable|string|max:255',
                'street' => 'nullable|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'country' => 'nullable|string|max:255',
                'zip_code' => 'nullable|string|max:255',
            ]);

            $student->record->update($validated);

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->withProperties([
                    'action' => 'updated_address_info',
                    'student_id' => $student->id,
                    'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'updated_fields' => array_keys($validated),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Student address information updated');

            return response()->json([
                'success' => true,
                'message' => 'Address information updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update address information', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating address information'
            ], 500);
        }
    }

    public function updateEmergencyInfo(Request $request, Student $student)
    {
        try {
            $validated = $request->validate([
                'contact_number' => 'required|string|max:255',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact_number' => 'required|string|max:255',
            ]);

            $student->record->update($validated);

            // Log the activity
            activity('student_management')
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->withProperties([
                    'action' => 'updated_emergency_info',
                    'student_id' => $student->id,
                    'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'updated_fields' => array_keys($validated),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Student emergency contact information updated');

            return response()->json([
                'success' => true,
                'message' => 'Emergency contact information updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update emergency contact information', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating emergency contact information'
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRecord $studentRecord)
    {
        //
    }
}
