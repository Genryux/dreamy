<?php

namespace App\Http\Controllers;

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

        //dd($data['applicant']->interview());

        $applicant = $data['applicant'] ?? null;

        if (!$applicant) {
            return null;
        }

        $application_status = $applicant->application_status;
        $interview_status = optional($applicant->interview)->status;
        $viewData = ['applicant' => $applicant];

        if ($application_status === "Selected") {
            return view('user-applicant.dashboard', $viewData);
        } else if ($application_status === "Pending-Documents") {

            if ($interview_status === 'Interview-Passed') {
                return view('user-applicant.dashboard', $viewData);
            }

            if ($interview_status === 'Interview-Completed') {
                return view('user-applicant.dashboard', [
                    'applicant' => $data['applicant'] ?? null,
                    'documents' => $data['documents'] ?? null,
                    'submissions' => $data['submissions'] ?? null
                ]);
            }
        } else if ($application_status === "Officially Enrolled") {
            return view('user-applicant.dashboard', $viewData);
        }

        return view('user-applicant.dashboard', [
            'applicant' => $data['applicant'] ?? null,
            'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
            'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
        ]);
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
