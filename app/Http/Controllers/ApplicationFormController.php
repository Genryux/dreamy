<?php

namespace App\Http\Controllers;

use App\Events\ApplicationFormSubmitted;
use App\Events\RecentApplicationTableUpdated;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Console\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationFormController extends Controller
{
    public function pending() {

        //$pending_applications_count = Applicant::where('application_status', 'pending')->count();

        //$pending_applications = ApplicationForm::latest()->limit(10)->get();

        $pending_applicants = Applicant::where('application_status', 'pending')->get();

        // foreach ($var as $va) {

        //     dd($va->applicationForm->full_name);

        // }

        return view('user-admin.pending-application', [
            'pending_applicants' => $pending_applicants
        ]);

    }

    public function selected() {

    }

    public function rejected() {

    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $pending_applications = Applicant::where('application_status', 'pending')->count();
        $applications = ApplicationForm::latest()->limit(10)->get();
    
        return view('user-admin.dashboard', [
            'applications' => $applications,
            'pending_applications' => $pending_applications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user-applicant.application-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //dd($request->birthdate);

        $request->validate([

            'lrn' => ['required', 'digits:12','unique:application_forms,lrn'],
            'full_name' => ['required', 'string'],
            'age' => ['required', 'integer'],
            'birthdate' => ['required', 'date'],
            'desired_program' => ['required', 'string'],
            'grade_level' => ['required']

        ]);

        $applicant = Applicant::where('user_id', Auth::user()->id)->first();


        $form = ApplicationForm::create([

            'applicant_id' => $applicant->id,
            'lrn' => $request->lrn,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'birthdate' => $request->birthdate,
            'desired_program' => $request->desired_program,
            'grade_level' => $request->grade_level

        ]);
        
        if ($applicant) {
            $applicant->update([
                'application_status' => 'pending'
            ]);
        }

        $pending_applications = Applicant::where('application_status', 'pending')->count();

        // event(new ApplicationFormSubmitted($form));
        event(new RecentApplicationTableUpdated($form, $pending_applications));


        return redirect('admission')->with('success', 'Application submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationForm $applicationForm, Request $request)
    {

        $form = ApplicationForm::find($request->id);
        
        return view('user-admin.pending-details');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationForm $applicationForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplicationForm $applicationForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationForm $applicationForm)
    {
        //
    }
}
