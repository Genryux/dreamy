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
use App\Notifications\QueuedNotification;
use App\Notifications\ImmediateNotification;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
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

    // Ajax tables

    public function getRecentApplications(Request $request)
    {
        try {
            $query = Applicants::with(['applicationForm'])
                ->where('application_status', 'pending');

            // Filter by current academic term if feature is enabled
            if (config('app.use_term_enrollments')) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
                        $q->where('academic_terms_id', $activeTerm->id);
                    });
                }
            }

            // Get total count of pending applications
            $totalRecords = $query->count();
            $filtered = $totalRecords;

            $start = $request->input('start', 0);
            $length = $request->input('length', 10); // Default to 10 if not specified

            $data = $query
                ->orderBy('created_at', 'desc') // Order by most recent first
                ->offset($start)
                ->limit($length)
                ->get(['id', 'first_name', 'last_name', 'created_at', 'applicant_id'])
                ->map(function ($item) {
                    return [
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->applicationForm->primary_track ?? 'N/A',
                        'grade_level' => $item->applicationForm->grade_level ?? 'N/A',
                        'submitted_at' => \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Manila')->format('M d, Y g:i A'),
                        'id' => $item->id // For actions
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filtered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('getRecentApplications error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load applications data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPendingApplications(Request $request)
    {
        try {
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
            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('applicant_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('primary_track', 'like', "%{$search}%")
                                ->orWhere('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($program) {
                    $formQuery->where('primary_track', $program);
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($grade) {
                    $formQuery->where('grade_level', $grade);
                });
            }

            // Get total count before applying sorting and pagination
            $totalRecords = Applicants::where('application_status', 'pending')->count();
            $filtered = $query->count();

            // Sorting
            $columns = ['applicant_id', 'full_name', 'program', 'grade_level', 'submitted_at'];
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'asc');

            if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
                $sortColumn = $columns[$orderColumnIndex];

                switch ($sortColumn) {
                    case 'applicant_id':
                        $query->orderBy('applicant_id', $orderDir);
                        break;
                    case 'full_name':
                        $query->orderBy('last_name', $orderDir)
                            ->orderBy('first_name', $orderDir);
                        break;
                    case 'program':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.primary_track', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'grade_level':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.grade_level', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'submitted_at':
                        $query->orderBy('created_at', $orderDir);
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);

            $data = $query
                ->offset($start)
                ->limit($length)
                ->get(['id', 'first_name', 'last_name', 'created_at', 'applicant_id'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->applicationForm->primary_track ?? 'N/A',
                        'grade_level' => $item->applicationForm->grade_level ?? 'N/A',
                        'submitted_at' => \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Manila')->format('M d, Y g:i A'),
                        'id' => $item->id // For actions
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filtered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('getPendingApplications error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load applications data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get approved applications for DataTables
     */
    public function getAcceptedApplications(Request $request)
    {
        try {
            $query = Applicants::withStatus('Accepted');

            // Filter by current academic term if feature is enabled
            if (config('app.use_term_enrollments')) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
                        $q->where('academic_terms_id', $activeTerm->id);
                    });
                }
            }

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('applicant_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('primary_track', 'like', "%{$search}%")
                                ->orWhere('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($program) {
                    $formQuery->where('primary_track', $program);
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($grade) {
                    $formQuery->where('grade_level', $grade);
                });
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                $query->where('application_status', $status);
            }

            // Get total count before applying sorting and pagination
            $totalRecords = Applicants::where('application_status', 'Accepted')->count();
            $filtered = $query->count();

            // Sorting
            $columns = ['index', 'applicant_id', 'full_name', 'program', 'grade_level', 'status', 'accepted_at'];
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'desc');

            if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
                $sortColumn = $columns[$orderColumnIndex];

                switch ($sortColumn) {
                    case 'index':
                        $query->orderBy('created_at', $orderDir);
                        break;
                    case 'applicant_id':
                        $query->orderBy('applicant_id', $orderDir);
                        break;
                    case 'full_name':
                        $query->orderBy('last_name', $orderDir)
                            ->orderBy('first_name', $orderDir);
                        break;
                    case 'program':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.primary_track', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'grade_level':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.grade_level', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'status':
                        $query->orderBy('application_status', $orderDir);
                        break;
                    case 'accepted_at':
                        $query->orderBy('accepted_at', $orderDir);
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);

            $data = $query
                ->offset($start)
                ->limit($length)
                ->get(['id', 'first_name', 'last_name', 'application_status', 'created_at', 'applicant_id', 'accepted_at'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->applicationForm->primary_track ?? 'N/A',
                        'grade_level' => $item->applicationForm->grade_level ?? 'N/A',
                        'status' => $item->interview->status ?? $item->application_status ?? '-',
                        'accepted_at' => $item->accepted_at ? \Carbon\Carbon::parse($item->accepted_at)->timezone('Asia/Manila')->format('M d, Y - g:i A') : '-',
                        'id' => $item->id // For actions
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filtered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('getAcceptedApplications error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load accepted applications data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending documents applications for DataTables
     */
    public function getPendingDocumentsApplications(Request $request)
    {
        try {
            $query = Applicants::withStatus('Pending-Documents')->with(['assignedDocuments', 'applicationForm']);

            // Filter by current academic term if feature is enabled
            if (config('app.use_term_enrollments')) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
                        $q->where('academic_terms_id', $activeTerm->id);
                    });
                }
            }

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('applicant_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('primary_track', 'like', "%{$search}%")
                                ->orWhere('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($program) {
                    $formQuery->where('primary_track', $program);
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($grade) {
                    $formQuery->where('grade_level', $grade);
                });
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                $query->where('application_status', $status);
            }

            // Get total count before applying sorting and pagination
            $totalRecords = Applicants::where('application_status', 'Accepted')->count();
            $filtered = $query->count();

            // Sorting
            $columns = ['index', 'applicant_id', 'full_name', 'program', 'grade_level', 'status', 'accepted_at'];
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'desc');

            if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
                $sortColumn = $columns[$orderColumnIndex];

                switch ($sortColumn) {
                    case 'index':
                        $query->orderBy('created_at', $orderDir);
                        break;
                    case 'applicant_id':
                        $query->orderBy('applicant_id', $orderDir);
                        break;
                    case 'full_name':
                        $query->orderBy('last_name', $orderDir)
                            ->orderBy('first_name', $orderDir);
                        break;
                    case 'program':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.primary_track', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'grade_level':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.grade_level', $orderDir)
                            ->select('applicants.*');
                        break;
                    case 'status':
                        $query->orderBy('application_status', $orderDir);
                        break;
                    case 'accepted_at':
                        $query->orderBy('accepted_at', $orderDir);
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);

            $data = $query
                ->offset($start)
                ->limit($length)
                ->get(['id', 'first_name', 'last_name', 'application_status', 'created_at', 'applicant_id', 'accepted_at'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->applicationForm->primary_track ?? 'N/A',
                        'grade_level' => $item->applicationForm->grade_level ?? 'N/A',
                        'status' => $item->document_status ?? '-',
                        'accepted_at' => $item->accepted_at ? \Carbon\Carbon::parse($item->accepted_at)->timezone('Asia/Manila')->format('M d, Y - g:i A') : '-',
                        'id' => $item->id // For actions
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filtered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('getAcceptedApplications error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load accepted applications data: ' . $e->getMessage()
            ], 500);
        }
    }




    public function pending()
    {

        //$pending_applications_count = Applicant::where('apsplication_status', 'pending')->count();

        //$pending_applicant = ApplicationForm::latest()->get();


        $pending_applicants = $query->get();

        // dd($pending_applicants[0]->id);


        return view('user-admin.applications.index', [
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

    /**
     * Get recent applications data for DataTables (AJAX).
     */


    public function index()
    {
        // Check if this is a dashboard request (no specific route)
        if (!request()->routeIs('applications.*')) {
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

        // Handle route-based tab detection for applications
        // if (request()->routeIs('applications.pending')) {
        //     $query = Applicants::withStatus('Pending');

        //     // Filter by current academic term if feature is enabled
        //     if (config('app.use_term_enrollments')) {
        //         $activeTerm = AcademicTerms::where('is_active', true)->first();
        //         if ($activeTerm) {
        //             $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
        //                 $q->where('academic_terms_id', $activeTerm->id);
        //             });
        //         }
        //     }

        //     $pending_applicants = $query->get();

        //     return view('user-admin.applications.index', [
        //         'pending_applicants' => $pending_applicants
        //     ]);
        // } else if (request()->routeIs('applications.approved')) {
        //     $applicants = Applicants::with('interview')->get();

        //     $query = Applicants::where('application_status', 'Selected');

        //     // Filter by current academic term if feature is enabled
        //     if (config('app.use_term_enrollments')) {
        //         $activeTerm = AcademicTerms::where('is_active', true)->first();
        //         if ($activeTerm) {
        //             $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
        //                 $q->where('academic_terms_id', $activeTerm->id);
        //             });
        //         }
        //     }

        //     $selected_applicants = $query->get();

        //     return view('user-admin.applications.index', [
        //         'selected_applicants' => $selected_applicants,
        //         'applicants' => $applicants
        //     ]);
        // } else if (request()->routeIs('applications.pending-documents')) {
        //     $pending_documents = Applicants::where('application_status', 'Pending-Documents')->get();

        //     return view('user-admin.applications.index', [
        //         'pending_documents' => $pending_documents
        //     ]);
        // } else if (request()->routeIs('applications.rejected')) {
        //     $rejected_applicants = Applicants::where('application_status', 'Rejected')->get();

        //     return view('user-admin.applications.index', [
        //         'rejected_applicants' => $rejected_applicants
        //     ]);
        // }

        // Default fallback
        return view('user-admin.applications.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = $this->userService->fetchAuthenticatedUser();
        $tracks = \App\Models\Track::where('status', 'active')->get();
        $programs = \App\Models\Program::where('status', 'active')->get();
        
        return view('user-applicant.application-form', compact('user', 'tracks', 'programs'));
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

                // Update applicant status first
                if ($applicant) {
                    $applicant->update([
                        'application_status' => 'Pending'
                    ]);

                    // Fire event with the applicant model (which has the applicant_id and relationships)
                    event(new RecentApplicationTableUpdated($applicant, $total_applications));
                }
            });

            // Send notifications after transaction is committed
            // Use Laravel's bulk notification to avoid N+1 problem

            // Send to admin roles (registrar, super_admin)
            $admins = User::role(['registrar', 'super_admin'])->get();

            Notification::send($admins, new QueuedNotification(
                "Application form",
                "A user just submitted an application. Please review the submission at your earliest convenience.",
                url('/pending-applications')
            ));

            // Send broadcast for real-time updates (separate broadcasts, no N+1)
            Notification::route('broadcast', 'admins')
                ->notify(new ImmediateNotification(
                    "Application form",
                    "A user just submitted an application. Please review the submission at your earliest convenience.",
                    url('/pending-applications')
                ));

            $user = $applicant->user;

            $user->notify(new PrivateQueuedNotification(
                "Application Submitted Successfully",
                "Your application has been submitted successfully and is now being reviewed.",
                null, // No URL needed for mobile
                null
            ));

            // REAL-TIME notification for mobile app - NOT QUEUED
            Notification::route('broadcast', 'user.' . $user->id)
                ->notify(new PrivateImmediateNotification(
                    "Application Submitted Successfully",
                    "Your application has been submitted successfully and is now being reviewed.",
                    null, // No URL needed for mobile
                    null
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
    public function showApplicationDetails(Applicants $applicant, Request $request)
    {

        $applicant = Applicants::with('applicationForm')->find($applicant->id);

        //dd($form->applicant_id);

        return view('user-admin.pending.pending-details', compact('applicant'));
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








    /**
     * Get rejected applications for DataTables
     */
    public function getRejectedApplications(Request $request)
    {
        $query = Applicants::where('application_status', 'Rejected');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('full_name', function ($applicant) {
                return $applicant->getFullNameAttribute();
            })
            ->addColumn('program', function ($applicant) {
                return $applicant->applicationForm->program->name ?? 'N/A';
            })
            ->addColumn('grade_level', function ($applicant) {
                return $applicant->applicationForm->grade_level ?? 'N/A';
            })
            ->addColumn('status', function ($applicant) {
                return $applicant->application_status;
            })
            ->rawColumns(['full_name', 'program', 'grade_level', 'status'])
            ->make(true);
    }

    /**
     * Reject an application
     */
    public function reject(Applicants $applicant, Request $request)
    {
        $user = Auth::user();
        $role = $user?->getRoleNames()->first() ?? 'No Role';

        try {
            $request->validate([
                'reason' => 'required|string|max:255',
                'remarks' => 'nullable|string|max:1000',
            ]);

            $applicant->update([
                'application_status' => 'Rejected',
                'rejected_by'       => "{$user->first_name} - {$role}",
                'rejection_reason' => $request->reason,
                'rejection_remarks' => $request->remarks,
                'rejected_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject application. Please try again.' . $e->getMessage()
            ], 500);
        }
    }
}
