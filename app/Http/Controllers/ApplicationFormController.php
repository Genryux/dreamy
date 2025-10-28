<?php

namespace App\Http\Controllers;

use App\Events\ApplicationFormSubmitted;
use App\Events\RecentApplicationTableUpdated;
use App\Mail\ApplicantProgressMail;
use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\ApplicationForm;
use App\Models\Interview;
use App\Models\Program;
use App\Models\User;
use App\Notifications\QueuedNotification;
use App\Notifications\ImmediateNotification;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use App\Services\AcademicTermService;
use App\Services\ApplicationFormService;
use App\Services\DashboardDataService;
use App\Services\EnrollmentPeriodService;
use App\Services\StudentService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Console\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class ApplicationFormController extends Controller
{
    public function __construct(
        protected AcademicTermService $academicTermService,
        protected DashboardDataService $dashboardDataService,
        protected EnrollmentPeriodService $enrollmentPeriodService,
        protected ApplicationFormService $applicationFormService,
        protected UserService $userService,
        protected StudentService $studentService
    ) {}

    // Ajax tables

    public function getRecentApplications(Request $request)
    {
        $activeTerm = AcademicTerms::where('is_active', true)->first();

        try {
            $query = null;

            if ($activeTerm) {
                $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($activeTerm->id);

                if ($activeEnrollmentPeriod) {
                    $query = Applicants::with(['applicationForm', 'program', 'enrollmentPeriod'])
                        ->where('application_status', 'Pending')
                        ->where('enrollment_period_id', $activeEnrollmentPeriod->id);
                } else {
                    // No active enrollment period - return empty results
                    $query = Applicants::with(['applicationForm', 'program', 'enrollmentPeriod'])
                        ->where('application_status', 'Pending')
                        ->where('id', -1); // This will return no results
                }
            } else {
                // No active academic term - return empty results
                $query = Applicants::with(['applicationForm', 'program', 'enrollmentPeriod'])
                    ->where('application_status', 'Pending')
                    ->where('id', -1); // This will return no results
            }


            // // Filter by current academic term if feature is enabled
            // if (config('app.use_term_enrollments')) {
            //     if ($activeTerm) {
            //         $query->whereHas('applicationForm', function ($q) use ($activeTerm) {
            //             $q->where('academic_terms_id', $activeTerm->id);
            //         });
            //     }
            // }

            // Get total count of pending applications
            $totalRecords = $query->count();
            $filtered = $totalRecords;

            $start = $request->input('start', 0);
            $length = $request->input('length', 10); // Default to 10 if not specified

            $data = $query
                ->orderBy('created_at', 'desc') // Order by most recent first
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    return [
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->program ? $item->program->code : 'N/A',
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
            $query = Applicants::withStatus('Pending')->with(['applicationForm', 'program']);

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
                        ->orWhereHas('program', function ($programQuery) use ($search) {
                            $programQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('program', function ($formQuery) use ($program) {
                    $formQuery->where('code', $program);
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
                        $query->orderBy('program_id', $orderDir);
                        break;
                    case 'grade_level':
                        $query->leftJoin('application_forms', 'applicants.id', '=', 'application_forms.applicants_id')
                            ->orderBy('application_forms.grade_level', $orderDir);
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
                ->get()
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->program ? $item->program->code : 'N/A',
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
            $query = Applicants::withStatus('Accepted')->with(['applicationForm', 'program']);

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
                        ->orWhereHas('program', function ($programQuery) use ($search) {
                            $programQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('program', function ($formQuery) use ($program) {
                    $formQuery->where('code', $program);
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
                $query->where(function ($q) use ($status) {
                    $q->where('application_status', $status)
                        ->orWhereHas('interview', function ($interviewQuery) use ($status) {
                            $interviewQuery->where('status', $status);
                        });
                });
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
                            ->orderBy('applicants.program_id', $orderDir)
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
                ->get(['id', 'first_name', 'last_name', 'program_id', 'application_status', 'created_at', 'applicant_id', 'accepted_at'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->program->code ?? 'N/A',
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
            $query = Applicants::withStatus('Pending-Documents')->with(['assignedDocuments', 'applicationForm', 'program']);

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
                        ->orWhereHas('program', function ($programQuery) use ($search) {
                            $programQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('program', function ($formQuery) use ($program) {
                    $formQuery->where('code', $program);
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
                $query->where(function ($q) use ($status) {
                    $q->where('application_status', $status)
                        ->orWhereHas('interview', function ($interviewQuery) use ($status) {
                            $interviewQuery->where('status', $status);
                        });
                });
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
                            ->orderBy('applicants.program_id', $orderDir)
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
                ->get(['id', 'first_name', 'last_name', 'program_id', 'application_status', 'created_at', 'applicant_id', 'accepted_at'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->program->code ?? 'N/A',
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
                // Get enrollment summary if no active enrollment period
                $enrollmentSummary = null;
                if (!$data['activeEnrollmentPeriod']) {
                    $enrollmentSummary = $this->dashboardDataService->getEnrollmentSummary();
                }

                return view('user-admin.dashboard', [
                    'applications' => $recentApplications = $data['recentApplications'] ?? collect(),
                    'pendingApplicationsCount' => $totalPendingApplications = $data['totalPendingApplications'] ?? 0,
                    'selectedApplicationsCount' => $totalAcceptedApplications = $data['totalAcceptedApplications'] ?? 0,
                    'pendingDocumentsApplicationsCount' => $totalPendingDocumentsApplications = $data['totalPendingDocumentsApplications'] ?? 0,
                    'enrolledApplicationsCount' => $totalEnrolledApplications = $data['totalEnrolledApplications'] ?? 0,
                    'applicationCount' => $totalApplications = $data['totalApplications'] ?? 0,
                    'currentAcadTerm' => $currentAcadTerm = $data['currentAcadTerm'] ?? null,
                    'activeEnrollmentPeriod' => $activeEnrollmentPeriod = $data['activeEnrollmentPeriod'] ?? null,
                    'enrollmentSummary' => $enrollmentSummary,
                    'countStudentStatuses' => $this->studentService->countStudentStatuses()
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
        $activeTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        $enrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($activeTerm->id);

        if (!$activeTerm || !$enrollmentPeriod || $enrollmentPeriod->status === 'Paused') {
            return redirect()->back();
        }

        $user = $this->userService->fetchAuthenticatedUser();

        // Check if user has already submitted an application form
        $applicant = Applicants::where('user_id', $user->id)->first();

        if ($applicant && $applicant->applicationForm) {
            // User has already submitted an application form
            return redirect()->route('admission.dashboard')->with('error', 'You have already submitted an application form. Please check your application status in the dashboard.');
        }

        $tracks = \App\Models\Track::where('status', 'active')->get();
        $programs = \App\Models\Program::where('status', 'active')->get();

        return view('user-applicant.application-form', compact('user', 'tracks', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = Auth::user();

        $validated = $request->validate([

            'preferred_sched'            => 'required|string',
            'is_returning'               => 'required|boolean',
            'lrn'                        => 'nullable|digits:12|unique:application_forms,lrn',
            'grade_level'                => 'required|string',
            'primary_track'              => 'required|exists:tracks,id',
            'secondary_track'            => 'nullable|exists:programs,id',
            'last_name'                  => 'required|string',
            'first_name'                 => 'required|string',
            'middle_name'                => 'nullable|string',
            'extension_name'             => 'nullable|string',
            'birthdate'                  => 'required|date|before:today',
            'age'                        => 'required|integer',
            'gender'                     => 'required|string',
            'contact_number'             => 'required|string',
            'place_of_birth'             => 'required|string',
            'mother_tongue'              => 'nullable|string',
            'belongs_to_ip'              => 'nullable|boolean',
            'is_4ps_beneficiary'         => 'nullable|boolean',

            'cur_house_no'               => 'required|string',
            'cur_street'                 => 'nullable|string',
            'cur_barangay'               => 'required|string',
            'cur_city'                   => 'required|string',
            'cur_province'               => 'required|string',
            'cur_country'                => 'required|string',
            'cur_zip_code'               => 'required|numeric',

            'perm_house_no'              => 'required|string',
            'perm_street'                => 'nullable|string',
            'perm_barangay'              => 'required|string',
            'perm_city'                  => 'required|string',
            'perm_province'              => 'required|string',
            'perm_country'               => 'required|string',
            'perm_zip_code'              => 'required|numeric',

            'father_last_name'           => 'required|string',
            'father_first_name'          => 'required|string',
            'father_middle_name'         => 'nullable|string',
            'father_contact_number'      => 'nullable|string',
            'mother_last_name'           => 'required|string',
            'mother_first_name'          => 'required|string',
            'mother_middle_name'         => 'nullable|string',
            'mother_contact_number'      => 'nullable|string',
            'guardian_last_name'         => 'required|string',
            'guardian_first_name'        => 'required|string',
            'guardian_middle_name'       => 'nullable|string',
            'guardian_contact_number'    => 'required|string',
            'has_special_needs'          => 'nullable|boolean',
            'special_needs'              => 'nullable|array',

            'last_grade_level_completed' => 'nullable|integer',
            'last_school_attended'       => 'nullable|string',
            'last_school_year_completed' => 'nullable|date|before:now',
            'school_id'                  => 'nullable|string',
        ]);

        $applicant = Applicants::where('user_id', $user->id)->first();
        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($this->academicTermService->fetchCurrentAcademicTerm()->id);

        try {

            DB::transaction(function () use ($applicant, $validated, $user, $activeEnrollmentPeriod) {

                $form = $this->applicationFormService->createApplication($applicant, $validated);

                // Log the activity
                activity('application')
                    ->causedBy($user)
                    ->performedOn($applicant)
                    ->withProperties([
                        'action' => 'submitted',
                        'applicant_id' => $applicant->applicant_id,
                        'applicant_name' => $applicant->first_name . ' ' . $applicant->last_name,
                        'program_id' => $applicant->program_id,
                        'grade_level' => $form->grade_level,
                        'enrollment_period_id' => $activeEnrollmentPeriod->id,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ])
                    ->log('Application submitted');

                // Get total applications count for the current academic term
                $totalApplications = Applicants::where('application_status', 'Pending')
                    ->where('enrollment_period_id', $activeEnrollmentPeriod->id)->count();

                // Dispatch event for real-time dashboard updates
                event(new RecentApplicationTableUpdated($form, $totalApplications));

                // Send to admin roles (registrar, super_admin)
                $admins = User::role(['registrar', 'super_admin'])->get();
                Notification::send($admins, new QueuedNotification(
                    "New Application Submission Received",
                    "A user just submitted an application. Please review the submission at your earliest convenience.",
                    url('/applications/pending')
                ));

                // Send broadcast for real-time updates (separate broadcasts, no N+1)
                Notification::route('broadcast', 'admins')
                    ->notify(new ImmediateNotification(
                        "New Application Submission Received",
                        "A user just submitted an application. Please review the submission at your earliest convenience.",
                        url('/applications/pending')
                    ));

            });

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully!'
            ]);
        } catch (InvalidArgumentException $e) {

            return response()->json([
                'success' => false,
                'message' => "Failed to submit the form: {$e}"
            ]);
        } catch (\Throwable $th) {

            Log::error('Application form submission failed', ['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
            throw new \Exception($th);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again later.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function pendingDetails(Applicants $applicant, Request $request)
    {

        $applicant = Applicants::with('applicationForm')->find($applicant->id);

        return view('user-admin.applications.pending-applications.show', compact('applicant'));
    }


    /**
     * Get rejected applications for DataTables
     */
    public function getRejectedApplications(Request $request)
    {
        try {
            $query = Applicants::where('application_status', 'Rejected')->with(['applicationForm', 'program']);

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
                        ->orWhereHas('program', function ($programQuery) use ($search) {
                            $programQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('applicationForm', function ($formQuery) use ($search) {
                            $formQuery->where('grade_level', 'like', "%{$search}%");
                        });
                });
            }

            // Program filter
            if ($program = $request->input('program_filter')) {
                $query->whereHas('program', function ($formQuery) use ($program) {
                    $formQuery->where('code', $program);
                });
            }

            // Grade filter
            if ($grade = $request->input('grade_filter')) {
                $query->whereHas('applicationForm', function ($formQuery) use ($grade) {
                    $formQuery->where('grade_level', $grade);
                });
            }

            // Get total count before applying sorting and pagination
            $totalRecords = Applicants::where('application_status', 'Rejected')->count();
            $filtered = $query->count();

            // Sorting
            $columns = ['index', 'applicant_id', 'full_name', 'program', 'grade_level', 'status'];
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
                            ->orderBy('applicants.program_id', $orderDir)
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
                ->get(['id', 'first_name', 'last_name', 'program_id', 'application_status', 'created_at', 'applicant_id', 'rejected_at'])
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'applicant_id' => $item->applicant_id ?? 'N/A',
                        'full_name' => $item->last_name . ', ' . $item->first_name,
                        'program' => $item->program->code ?? 'N/A',
                        'grade_level' => $item->applicationForm->grade_level ?? 'N/A',
                        'status' => $item->application_status,
                        'rejected_at' => $item->rejected_at ? \Carbon\Carbon::parse($item->rejected_at)->timezone('Asia/Manila')->format('M d, Y - g:i A') : '-',
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
            \Log::error('getRejectedApplications error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load rejected applications data: ' . $e->getMessage()
            ], 500);
        }
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

            // Log the activity
            activity('application')
                ->causedBy($user)
                ->performedOn($applicant)
                ->withProperties([
                    'action' => 'rejected',
                    'applicant_id' => $applicant->applicant_id,
                    'applicant_name' => $applicant->first_name . ' ' . $applicant->last_name,
                    'rejection_reason' => $request->reason,
                    'rejection_remarks' => $request->remarks,
                    'rejected_by' => "{$user->first_name} - {$role}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Application rejected');

            $recipientEmail = $applicant->user->email;
            $loginUrl = config('app.url') . '/portal/login';

            if ($recipientEmail) {
                $title = 'Application Update — Dreamy School Enrollment';
                $body = "Unfortunately, your application was not accepted. Check your account for details and guidance on any further options.";
                Mail::to($recipientEmail)->queue(new ApplicantProgressMail(
                    applicantName: $applicant->first_name ?? 'Applicant',
                    title: $title,
                    bodyText: $body,
                    loginUrl: $loginUrl
                ));
            }


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

    /**
     * Get application statistics for dashboard
     */
    public function getApplicationStatistics()
    {
        try {
            // Get base query with academic term filtering if enabled
            $baseQuery = Applicants::query();

            if (config('app.use_term_enrollments')) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $baseQuery->whereHas('applicationForm', function ($q) use ($activeTerm) {
                        $q->where('academic_terms_id', $activeTerm->id);
                    });
                }
            }

            // Define the statuses to include in the total count
            $includedStatuses = ['Pending', 'Accepted', 'Pending-Documents', 'Rejected', 'Completed-Failed', 'Officially Enrolled'];

            // Get counts for each status
            $statistics = [
                'total' => (clone $baseQuery)->whereIn('application_status', $includedStatuses)->count(),
                'pending' => (clone $baseQuery)->where('application_status', 'Pending')->count(),
                'accepted' => (clone $baseQuery)->where('application_status', 'Accepted')->count(),
                'pending_documents' => (clone $baseQuery)->where('application_status', 'Pending-Documents')->count(),
                'rejected' => (clone $baseQuery)->where('application_status', 'Rejected')->count(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            \Log::error('getApplicationStatistics error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load application statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get application summary statistics for applicant dashboard
     */
    public function getApplicationSummary()
    {
        try {
            // Get base query with academic term filtering if enabled
            $baseQuery = Applicants::query();

            if (config('app.use_term_enrollments')) {
                $activeTerm = AcademicTerms::where('is_active', true)->first();
                if ($activeTerm) {
                    $baseQuery->whereHas('applicationForm', function ($q) use ($activeTerm) {
                        $q->where('academic_terms_id', $activeTerm->id);
                    });
                }
            }

            // Get total registrations (all applications regardless of status)
            $totalRegistrations = $baseQuery->count();

            // Get successful applicants (ever accepted applications - including those who moved to next stages)
            $successfulApplicants = (clone $baseQuery)->whereIn('application_status', ['Accepted', 'Pending-Documents', 'Officially Enrolled'])->count();

            // Calculate acceptance rate
            $acceptanceRate = $totalRegistrations > 0 ? round(($successfulApplicants / $totalRegistrations) * 100) : 0;

            $summary = [
                'total_registrations' => $totalRegistrations,
                'successful_applicants' => $successfulApplicants,
                'acceptance_rate' => $acceptanceRate,
            ];

            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            \Log::error('getApplicationSummary error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load application summary: ' . $e->getMessage()
            ], 500);
        }
    }
}
