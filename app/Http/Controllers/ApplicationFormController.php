<?php

namespace App\Http\Controllers;

use App\Events\ApplicationFormSubmitted;
use App\Events\RecentApplicationTableUpdated;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user-applicant.application-form');
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

        // event(new ApplicationFormSubmitted($form));
        event(new RecentApplicationTableUpdated($form));

        if ($applicant) {
            $applicant->update([
                'application_status' => 'pending'
            ]);
        }

        return redirect('admission')->with('success', 'Application submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationForm $applicationForm)
    {
        //
        $applicationForm->all();
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
