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
    // $academicTermId: Optional - if provided, links invoice to this term (for future enrollment periods)
    //                   If null, falls back to current active term
    public function assignInvoiceAfterPromotion(int $student_id, ?int $academicTermId = null)
    {
        // Use provided academic term ID, or fallback to current active term
        if ($academicTermId) {
            $targetTerm = \App\Models\AcademicTerms::find($academicTermId);
        }
        
        // Fallback to current active term if no valid term provided
        if (!isset($targetTerm) || !$targetTerm) {
            $targetTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        }

        if (!$targetTerm) {
            throw new \InvalidArgumentException('No active academic term found. Please activate an academic term first.');
        }

        // Find the student
        $student = Student::find($student_id);

        if (!$student) {
            throw new \InvalidArgumentException('No student found.');
        }

        // filter school fees according to the program and grade level
        // A fee applies to a student if:
        // 1. program_id is NULL (applies to all programs) OR program_id matches student's program
        // 2. grade_level is NULL (applies to all grade levels) OR grade_level matches student's grade level
        $school_fees = SchoolFee::where(function ($query) use ($student) {
                $query->whereNull('program_id')
                      ->orWhere('program_id', $student->program_id);
            })
            ->where(function ($query) use ($student) {
                $query->whereNull('grade_level')
                      ->orWhere('grade_level', $student->grade_level);
            })
            ->get();

        return DB::transaction(function () use ($student, $targetTerm, $school_fees) {
            // create an invoice
            $invoice = Invoice::withTrashed()->create([
                'student_id' => $student->id,
                'academic_term_id' => $targetTerm->id,
                'status' => 'unpaid'
            ]);

            // loop over school fees and create an invoice item for the created invoice
            foreach ($school_fees as $fee) {

                $invoice->items()->create([
                    'school_fee_id' => $fee->id,
                    'academic_term_id' => $targetTerm->id,
                    'amount' => $fee->amount
                ]);
            }

            return $invoice;

        });
    }
}
