<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerms;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AcademicTermController extends Controller
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
