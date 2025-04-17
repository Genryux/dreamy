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

        //$pending_applicant = ApplicationForm::latest()->get();

        $pending_applicants = Applicant::where('application_status', 'Pending')->get();

        
        return view('user-admin.pending.pending-application', [
            'pending_applicants' => $pending_applicants
        ]);

    }

    public function selected() {

        $selected_applicants = Applicant::where('application_status', 'Selected')->get();

        return view('user-admin.selected.selected-application', [
            'selected_applicants' => $selected_applicants
        ]);

    }

    public function rejected() {

    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $pending_applications = Applicant::countByStatus('Pending')->count();
        $selected_applications = Applicant::countByStatus('Selected')->count();
        
        $applicationCount = Applicant::countAllStatus(['Pending', 'Selected', 'Pending Documents'])->count();
        $applications = Applicant::where('application_status', 'Pending')->latest()->limit(10)->get();
    
        return view('user-admin.dashboard', [
            'applications' => $applications,
            'pending_applications' => $pending_applications,
            'selected_applications' => $selected_applications,
            'applicationCount' => $applicationCount
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
                'application_status' => 'Pending'
            ]);
        }

        $total_applications = Applicant::countApplications()->count();

        // event(new ApplicationFormSubmitted($form));
        event(new RecentApplicationTableUpdated($form, $total_applications));


        return redirect('admission')->with('success', 'Application submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationForm $applicationForm, Request $request)
    {

        $form = ApplicationForm::find($request->id);
        
        return view('user-admin.pending.pending-details', ['form' => $form]);

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
