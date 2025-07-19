<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;

class ApplicantsController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Applicants $applicants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Applicants $applicants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Applicants $applicants)
    {

        //dd($request->all(), $applicants->application_status);

        $request->validate([
            'action' => 'required|string|in:enroll-student',
        ]);

        match ($request->action) {
            'enroll-student' => $applicants->update(['application_status' => 'Officially Enrolled']),
            default => abort(400, 'Invalid action'),
        };

        return redirect()->back();


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Applicants $applicants)
    {
        //
    }
}
