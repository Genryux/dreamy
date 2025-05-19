<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerms;
use Illuminate\Http\Request;


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
                $activeTerm->update(['is_active' => false]);
            }

            $validated = $request->validate([
                'year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'required|boolean'
            ]);

            AcademicTerms::create($validated);
            
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
    public function update(Request $request, AcademicTerms $academicTerms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicTerms $academicTerms)
    {
        //
    }
}
