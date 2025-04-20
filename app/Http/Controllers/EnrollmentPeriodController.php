<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentPeriod;
use Illuminate\Http\Request;

class EnrollmentPeriodController extends Controller
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
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnrollmentPeriod $enrollmentPeriod)
    {
        //
    }
}
