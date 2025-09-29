<?php

namespace App\Http\Controllers;

use App\Events\ApplicationFormSubmitted;
use App\Events\RecentApplicationTableUpdated;
use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\ApplicationForm;
use App\Models\Interview;
use App\Models\User;
use App\Notifications\GenericNotification;
use App\Services\AcademicTermService;
use App\Services\ApplicationFormService;
use App\Services\DashboardDataService;
use App\Services\EnrollmentPeriodService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Console\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class ApplicationFormController extends Controller
{
    public function __construct(
        protected AcademicTermService $academicTermService,
        protected DashboardDataService $dashboardDataService,
        protected EnrollmentPeriodService $enrollmentPeriodService,
        protected ApplicationFormService $applicationFormService,
        protected UserService $userService
    ) {}

    public function pending()
    {

        //$pending_applications_count = Applicant::where('apsplication_status', 'pending')->count();

        //$pending_applicant = ApplicationForm::latest()->get();

        $query = Applicants::withStatus('Pending');

        // Filter by current academic term if feature is enabled
        if (config('app.use_term_enrollments')) {
            $activeTerm = AcademicTerms::where('is_active', true)->first();
            if ($activeTerm) {
                $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
                    $q->where('academic_terms_id', $activeTerm->id);
                });
            }
        }

        $pending_applicants = $query->get();

        // dd($pending_applicants[0]->id);


        return view('user-admin.pending.pending-application', [
            'pending_applicants' => $pending_applicants
        ]);
    }

    public function selected()
    {
        $applicants = Applicants::with('interview')->get();

        //$ongoingInterviews = $this->applicationFormService->fetchApplicationWithStatus('Ongoing')->get();
        //dd($ongoingInterviews);

        $query = Applicants::where('application_status', 'Selected');

        // Filter by current academic term if feature is enabled
        if (config('app.use_term_enrollments')) {
            $activeTerm = AcademicTerms::where('is_active', true)->first();
            if ($activeTerm) {
                $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
                    $q->where('academic_terms_id', $activeTerm->id);
                });
            }
        }

        $selected_applicants = $query->get();
        // $scheduled_applicants = Applicants::with('interview')->where('application_status', 'Scheduled')->get();
        // $interview_details = $scheduled_applicants->interview;

        //dd($interview_details);

        return view('user-admin.selected.selected-application', [
            'selected_applicants' => $selected_applicants,
            'applicants' => $applicants
        ]);
    }

    public function pendingDocuments(Request $request)
    {

        $pending_documents = Applicants::where('application_status', 'Pending-Documents')->get();

        // dd($pending_documents);


        //  dd($pending_documents[0]->id);

        return view('user-admin.pending-documents.pending-documents', [
            'pending_documents' => $pending_documents
        ]);
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $data = $this->dashboardDataService->getAdminDashboardData();

        if ($data) {

            return view('user-admin.dashboard', [
                'applications' => $recentApplications = $data['recentApplications'] ?? collect(),
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
        $user = $this->userService->fetchAuthenticatedUser();
        // dd($user->first_name);
        return view('user-applicant.application-form', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //dd($request->all());

        $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($currentAcadTerm->id);
        $validated = $this->applicationFormService->validateData($request->all());
        $now = Carbon::now();


        $applicant = Applicants::where('user_id', Auth::user()->id)->first();

        try {

            DB::transaction(function () use ($applicant, $validated, $currentAcadTerm, $now, $activeEnrollmentPeriod) {
                $form = $this->applicationFormService->saveApplication(
                    [
                        'applicants_id'              => $applicant->id,
                        'academic_terms_id'          => $currentAcadTerm->id,
                        'enrollment_period_id'       => $activeEnrollmentPeriod->id,

                        'preferred_sched'            => $validated['preferred_sched'],
                        'is_returning'               => $validated['is_returning'],
                        'lrn'                        => $validated['lrn'],
                        'grade_level'                => $validated['grade_level'],
                        'primary_track'              => $validated['primary_track'],
                        'secondary_track'            => $validated['secondary_track'],
                        'acad_term_applied'          => $currentAcadTerm->year,
                        'semester_applied'           => $currentAcadTerm->semester,
                        'admission_date'             => $now,

                        'last_name'                  => $validated['last_name'],
                        'first_name'                 => $validated['first_name'],
                        'middle_name'                => $validated['middle_name'],
                        'extension_name'             => $validated['extension_name'],
                        'gender'                     => $validated['gender'],
                        'birthdate'                  => $validated['birthdate'],
                        'age'                        => $validated['age'],
                        'place_of_birth'             => $validated['place_of_birth'],
                        'mother_tongue'              => $validated['mother_tongue'],
                        'belongs_to_ip'              => $validated['belongs_to_ip'],
                        'is_4ps_beneficiary'         => $validated['is_4ps_beneficiary'],
                        'contact_number'             => $validated['contact_number'],

                        'cur_house_no'               => $validated['cur_house_no'],
                        'cur_street'                 => $validated['cur_street'],
                        'cur_barangay'               => $validated['cur_barangay'],
                        'cur_city'                   => $validated['cur_city'],
                        'cur_province'               => $validated['cur_province'],
                        'cur_country'                => $validated['cur_country'],
                        'cur_zip_code'               => $validated['cur_zip_code'],

                        'perm_house_no'              => $validated['perm_house_no'],
                        'perm_street'                => $validated['perm_street'],
                        'perm_barangay'              => $validated['perm_barangay'],
                        'perm_city'                  => $validated['perm_city'],
                        'perm_province'              => $validated['perm_province'],
                        'perm_country'               => $validated['perm_country'],
                        'perm_zip_code'              => $validated['perm_zip_code'],

                        'father_last_name'           => $validated['father_last_name'],
                        'father_first_name'          => $validated['father_first_name'],
                        'father_middle_name'         => $validated['father_middle_name'],
                        'father_contact_number'      => $validated['father_contact_number'],
                        'mother_last_name'           => $validated['mother_last_name'],
                        'mother_first_name'          => $validated['mother_first_name'],
                        'mother_middle_name'         => $validated['mother_middle_name'],
                        'mother_contact_number'      => $validated['mother_contact_number'],
                        'guardian_last_name'         => $validated['guardian_last_name'],
                        'guardian_first_name'        => $validated['guardian_first_name'],
                        'guardian_middle_name'       => $validated['guardian_middle_name'],
                        'guardian_contact_number'    => $validated['guardian_contact_number'],
                        'has_special_needs'          => $validated['has_special_needs'],
                        'special_needs'              => $validated['special_needs'] ?? null,

                        'last_grade_level_completed' => $validated['last_grade_level_completed'],
                        'last_school_attended'       => $validated['last_school_attended'],
                        'last_school_year_completed' => $validated['last_school_year_completed'],
                        'school_id'                  => $validated['school_id'],

                    ]
                );

                $total_applications = $this->applicationFormService->fetchApplicationWithAnyStatus(['Pending', 'Selected', 'Pending Documents'])->count();

                // event(new ApplicationFormSubmitted($form));
                event(new RecentApplicationTableUpdated($form, $total_applications));


                if ($applicant) {
                    $applicant->update([
                        'application_status' => 'Pending'
                    ]);
                }
            });

            // Send notifications after transaction is committed
            // Use Laravel's bulk notification to avoid N+1 problem
            
            // Send to admin roles (registrar, super_admin)
            $admins = User::role(['registrar', 'super_admin'])->get();
            Notification::send($admins, new GenericNotification(
                "Application form",
                "A user just submitted an application. Please review the submission at your earliest convenience.",
                url('/pending-applications')
            ));

            // Send to teacher roles (head_teacher, teacher)
            $teachers = User::role(['head_teacher', 'teacher'])->get();
            Notification::send($teachers, new GenericNotification(
                "New Application Submitted",
                "A new student application has been submitted and may require academic review.",
                url('/pending-applications')
            ));

            // Send broadcast for real-time updates (separate broadcasts, no N+1)
            Notification::route('broadcast', 'admins')
                ->notify(new GenericNotification(
                    "Application form",
                    "A user just submitted an application. Please review the submission at your earliest convenience.",
                    url('/pending-applications')
                ));

            Notification::route('broadcast', 'teachers')
                ->notify(new GenericNotification(
                    "New Application Submitted",
                    "A new student application has been submitted and may require academic review.",
                    url('/pending-applications')
                ));


            return redirect('admission')->with('success', 'Application submitted successfully!');
        } catch (\Throwable $th) {

            Log::error('Application form submission failed', ['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
            throw new \Exception($th);
            return redirect()->back()->with('error', 'An error occurred while submitting your application. Please try again later.');
        }
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
