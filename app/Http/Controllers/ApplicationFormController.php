<?php

namespace App\Http\Controllers;

use App\Events\ApplicationFormSubmitted;
use App\Events\RecentApplicationTableUpdated;
use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\User;
use App\Services\AcademicTermService;
use App\Services\ApplicationFormService;
use App\Services\DashboardDataService;
use App\Services\EnrollmentPeriodService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Console\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationFormController extends Controller
{
    public function __construct(
        protected AcademicTermService $academicTermService,
        protected DashboardDataService $dashboardDataService,
        protected EnrollmentPeriodService $enrollmentPeriodService,
        protected ApplicationFormService $applicationFormService
    )
    {}

    public function pending() {

        //$pending_applications_count = Applicant::where('apsplication_status', 'pending')->count();

        //$pending_applicant = ApplicationForm::latest()->get();

        $pending_applicants = Applicant::withStatus('Pending')->get();

        // dd($pending_applicants[0]->id);

        
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

        $data = $this->dashboardDataService->getAdminDashboardData();
        
        if ($data) {

            return view('user-admin.dashboard', [
            'applications' =>$recentApplications = $data['recentApplications'] ?? collect(),
            'pendingApplicationsCount' => $pendingApplicationsCount = $data['pendingApplicationsCount'] ?? 0,
            'selectedApplicationsCount' => $selectedApplicationsCount = $data['selectedApplicationsCount'] ?? 0,
            'applicationCount' => $applicationCount = $data['applicationCount'] ?? 0,
            'currentAcadTerm' => $currentAcadTerm = $data['currentAcadTerm'] ?? null,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod = $data['activeEnrollmentPeriod'] ?? null
            ]);
        }

        return null;
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

        $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($currentAcadTerm->id);

        $validated = $this->applicationFormService->validateData($request);


        try {
            
            $applicant = Applicant::where('user_id', Auth::user()->id)->first();

            $form = $this->applicationFormService->processApplicationForm([
                'applicant_id' => $applicant ? $applicant->id : null,
                'lrn' => $validated->lrn,
                'full_name' => $validated->full_name,
                'age' => $validated->age,
                'birthdate' => $validated->birthdate,
                'desired_program' => $validated->desired_program,
                'grade_level' => $validated->grade_level,
                'academic_term_id' => $currentAcadTerm ? $currentAcadTerm->id : null,
                'enrollment_period_id' => $activeEnrollmentPeriod ? $activeEnrollmentPeriod->id : null
            ]);
            
            if ($applicant) {
                $applicant->update([
                    'application_status' => 'Pending'
                ]);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'An error occurred while submitting your application. Please try again later.');
        }

        $total_applications = $this->applicationFormService->fetchApplicationWithAnyStatus(['Pending', 'Selected', 'Pending Documents'])->count();

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

        //dd($form->applicant_id);
        
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
