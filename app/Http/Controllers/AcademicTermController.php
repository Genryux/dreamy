<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerms;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AcademicTermController extends Controller
{
    public function __construct(
        protected StudentService $studentService
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $existingTerm = AcademicTerms::where('year', $request->year)
                ->where('semester', $request->semester)
                ->first();


            if ($existingTerm) {
                return redirect()->back()->with('error', 'Academic term already exists.');
            }

            if ($request->is_active) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $activeTerm->update(['is_active' => false]);
                }
            }

            $validated = $request->validate([
                'year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'required|boolean'
            ]);

            $newTerm = AcademicTerms::create($validated);

            // Auto-seed enrollments if this is the new active term
            if ($newTerm->is_active) {
                \Artisan::call('db:seed', ['--class' => 'StudentEnrollmentSeeder']);
            }

            return redirect()->back()->with('success', 'Academic term created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error checking existing terms: ' . $e->getMessage());
        }
    }

    public function startNewTerm(Request $request)
    {
        \Log::info('=== START NEW TERM DEBUG ===');
        \Log::info('Request received:', $request->all());
        \Log::info('Session before validation:', session()->all());

        $validated = $request->validate([
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        \Log::info('Validation passed:', $validated);

        try {

            $existingTerm = AcademicTerms::where('year', $request->year)
                ->where('semester', $request->semester)
                ->first();

            if ($existingTerm) {
                \Log::info('Existing term found, returning error');
                return redirect()->back()->with('error', 'Academic term already exists.');
            }

            \Log::info('No existing term found, proceeding with creation');

            DB::beginTransaction();

            //deactivate the current term
            $activeTerm = AcademicTerms::where('is_active', true)->first();
            if ($activeTerm) {
                \Log::info('Deactivating current term:', ['term_id' => $activeTerm->id]);
                $activeTerm->update(['is_active' => false]);
            }

            //start new term
            $newTerm = AcademicTerms::create(array_merge($validated, ['is_active' => true]));
            \Log::info('New term created:', ['term_id' => $newTerm->id]);

            //promote students to next term
            $continuingStudents = collect($this->studentService->promoteStudents($newTerm));
            \Log::info('Students promoted:', ['count' => $continuingStudents->count()]);
            
            //get all school fees
            $schoolFees = SchoolFee::all();
            \Log::info('School fees found:', ['count' => $schoolFees->count()]);

            $continuingStudents->chunk(100)->each(function ($studentsChunk) use ($schoolFees, $newTerm) {

                foreach ($studentsChunk as $student) {

                    // Create new invoice
                    $invoice = $student->invoices()->create([
                        'academic_term_id' => $newTerm->id,
                        'status' => 'unpaid'
                    ]);

                    foreach ($schoolFees as $fee) {
                        $invoice->items()->create(
                            [
                                'school_fee_id' => $fee->id,
                                'academic_term_id' => $newTerm->id,
                                'amount' => $fee->amount
                            ]
                        );
                    }
                }
            });

            DB::commit();
            \Log::info('Transaction committed successfully');

            \Log::info('Session before redirect:', session()->all());
            \Log::info('About to redirect with success message');
            
            return redirect()->back()->with('success', 'New academic term started successfully!');
        } catch (\Throwable $th) {
            \Log::error('Exception occurred:', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);
            
            DB::rollBack();
            \Log::info('About to redirect with error message');
            return redirect()->back()->with('error', "An unexpected error occurred while starting the new term.{$th}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicTerms $academicTerms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicTerms $academicTerms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $academicTerm = AcademicTerms::findOrFail($id);

            // Check if another term with same year and semester exists (excluding current term)
            $existingTerm = AcademicTerms::where('year', $request->year)
                ->where('semester', $request->semester)
                ->where('id', '!=', $id)
                ->first();

            if ($existingTerm) {
                return redirect()->back()->with('error', 'Academic term with this year and semester already exists.');
            }

            // If setting as active, deactivate other terms
            if ($request->is_active == '1') {
                AcademicTerms::where('is_active', true)
                    ->where('id', '!=', $id)
                    ->update(['is_active' => false]);
            }

            $validated = $request->validate([
                'year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'required|in:0,1'
            ]);

            $academicTerm->update($validated);

            return redirect()->back()->with('success', 'Academic term updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating academic term: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicTerms $academicTerms)
    {
        //
    }
}
