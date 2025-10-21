<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use App\Models\Teacher;
use App\Services\DashboardDataService;
use Illuminate\Http\Request;

class AdmissionDashboardController extends Controller
{
    protected $dashboardDataService;

    public function __construct(DashboardDataService $dashboardDataService)
    {

        $this->dashboardDataService = $dashboardDataService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->dashboardDataService->getAdmissionDashboardData();

        // 'currentAcadTerm' => $currentAcadTerm,
        // 'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
        // 'applicant' => $applicant,
        // 'assignedDocuments' => $assignedDocuments,
        // 'documents' => $documents

        // $applicant->load([
        //     'interview',
        //     'applicationForm',
        //     'assignedDocuments.documents',
        //     'assignedDocuments.submissions'
        // ]);

        //dd($data['applicant']->interview());

        $applicant = $data['applicant'] ?? null;

        if (!$applicant) {
            return null;
        }

        $application_status = $applicant->application_status;

        switch ($application_status) {
            case null:
                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;

            case 'Pending':
                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;
            case 'Accepted':

                $teacher = Teacher::find($applicant->interview->teacher_id);

                if (isset($teacher->last_name)) {
                    $teacherLastName = $teacher->last_name;
                } else {
                    $teacherLastName = 'Not Assigned';
                }

                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'teacherLastName' => $teacherLastName,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;

            case 'Rejected':
                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;

            case 'Pending-Documents':
                $interview_status = optional($applicant->interview)->status;

                if ($interview_status === 'Exam-Failed') {
                    return view('user-applicant.dashboard', [
                        'applicant' => $applicant,
                        'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                        'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                    ]);
                }

                if ($interview_status === 'Exam-Passed') {
                    return view('user-applicant.dashboard', [
                        'applicant' => $applicant,
                        'assignedDocuments' => $data['assignedDocuments'] ?? null,
                        'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                        'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                    ]);
                }

                if ($interview_status === 'Exam-Completed') {
                    // Get the current applicant's assigned documents
                    $assignedDocuments = $applicant->assignedDocuments;
                    $totalAssignedDocuments = $assignedDocuments->count();
                    $verifiedCount = $assignedDocuments->where('status', 'Verified')->count();

                    return view('user-applicant.dashboard', [
                        'applicant' => $applicant,
                        'assignedDocuments' => $assignedDocuments,
                        'verifiedCount' => $verifiedCount,
                        'totalAssignedDocuments' => $totalAssignedDocuments,
                        'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                        'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                    ]);
                }


                break;

            case 'Officially Enrolled':
                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;

            default:
                return view('user-applicant.dashboard', [
                    'applicant' => $applicant,
                    'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                    'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                ]);
                break;
        }



        // $application_status = $applicant->application_status;
        // $interview_status = optional($applicant->interview)->status;
        // $viewData = ['applicant' => $applicant];

        // if ($application_status === "Pending") {
        //     return view('user-applicant.dashboard', compact('application_form', 'applicant'));
        // } else if ($application_status === "Selected") {
        //     return view('user-applicant.dashboard', $viewData);
        // } else if ($application_status === "Pending-Documents") {

        //     if ($interview_status === 'Interview-Passed') {
        //         return view('user-applicant.dashboard', $viewData);
        //     }

        //     if ($interview_status === 'Interview-Completed') {
        //         return view('user-applicant.dashboard', [
        //             'applicant' => $data['applicant'] ?? null,
        //             'assignedDocuments' => $data['assignedDocuments'] ?? null,
        //             // 'submissions' => $data['submissions'] ?? null
        //         ]);
        //     }
        // } else if ($application_status === "Officially Enrolled") {
        //     return view('user-applicant.dashboard', $viewData);
        // }

        // return view('user-applicant.dashboard', [
        //     'applicant' => $data['applicant'] ?? null,
        //     'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
        //     'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
        // ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
