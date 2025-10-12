<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Program;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Services\AcademicTermService;
use Illuminate\Support\Facades\DB;

class InvoiceService
{

    public function __construct(
        protected AcademicTermService $academicTermService
    ) {}

    // Assign an invoice to a student after promoting from being applicant
    public function assignInvoiceAfterPromotion(int $student_id)
    {

        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        if (!$activeTerm) {
            throw new \InvalidArgumentException('No active academic term found. Please activate an academic term first.');
        }

        // Find the student
        $student = Student::find($student_id);

        if (!$student) {
            throw new \InvalidArgumentException('No student found.');
        }

        $program = Program::where('code', $student->program)->first();

        if (!$program) {
            $program = null; //program id field in school fee can be nullable
        }

        // filter school fees according to the program and grade level
        $school_fees = SchoolFee::where('grade_level', $student->grade_level);
        
        if ($program) {
            $school_fees->where('program_id', $program->id);
        }
        
        $school_fees = $school_fees->get();

        if ($school_fees->isEmpty()) {
            // fallback to school fees for the grade level only
            $school_fees = SchoolFee::where('grade_level', $student->grade_level)->get();
        }

        return DB::transaction(function () use ($student, $activeTerm, $school_fees) {
            // create an invoice
            $invoice = Invoice::withTrashed()->create([
                'student_id' => $student->id,
                'academic_term_id' => $activeTerm->id,
                'status' => 'unpaid'
            ]);

            // loop over school fees and create an invoice item for the created invoice
            foreach ($school_fees as $fee) {

                $invoice->items()->create([
                    'school_fee_id' => $fee->id,
                    'academic_term_id' => $activeTerm->id,
                    'amount' => $fee->amount
                ]);
            }

            return $invoice;

        });
    }
}
