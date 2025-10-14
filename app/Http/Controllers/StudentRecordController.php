<?php

namespace App\Http\Controllers;

use App\Exceptions\StudentRecordException;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Applicants;
use App\Models\DocumentSubmissions;
use App\Models\Invoice;
use App\Models\StudentRecord;
use App\Models\Student;
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
use App\Services\AcademicTermService;
use App\Services\StudentService;
use Carbon\Carbon;
use App\Services\InvoiceService;

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

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        if (!$applicant->applicationForm) {
            return response()->json(['error' => 'Application form not found for this applicant'], 404);
        }

        try {

            $student = DB::transaction(function () use ($applicant) {

                $applicant->update(['application_status' => 'Officially Enrolled']);

                return $this->student_service->enrollStudent($applicant);
            });

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
    public function show(StudentRecord $studentRecord)
    {

        // $email = $studentRecord->student;

        // $student = $studentRecord->students;

        // $record = $student->record;

        $student = $studentRecord->student->load('user');

        // // $student = Student::find(95);

        // // $s = StudentRecord::find(95);

        // dd($students);

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

        // dd($record, $studentRecordId)
        return view('user-admin.enrolled-students.show', compact('studentRecord', 'assignedDocuments'));
    }

    /**
     * Render a clean COE preview for embedding/printing
     */
    public function coePreview(StudentRecord $studentRecord)
    {
        $student = $studentRecord->student;
        $school = SchoolSetting::query()->first();
        // Use the same view as the PDF to keep preview and download consistent
        return view('pdf.coe', compact('studentRecord', 'school'));
    }

    /**
     * Download/stream COE as a real PDF using Dompdf
     */
    public function coePdf(StudentRecord $studentRecord)
    {
        $school = SchoolSetting::query()->first();
        $pdf = Pdf::loadView('pdf.coe', [
            'studentRecord' => $studentRecord,
            'school' => $school,
        ])->setPaper('letter')->setOptions([
            'isRemoteEnabled' => true,
        ]);
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
    public function update(Request $request, StudentRecord $studentRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRecord $studentRecord)
    {
        //
    }
}
