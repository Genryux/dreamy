<?php

namespace App\Http\Controllers;

use App\Events\RecentApplicationTableUpdated;
use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\EnrollmentPeriod;
use Illuminate\Http\Request;
use App\Services\DashboardDataService;

class AdminDashboardController extends Controller
{

    protected $dashboardDataService;

    public function __construct(DashboardDataService $dashboardDataService)
    {
        $this->dashboardDataService = $dashboardDataService;
    }


    public function index() {

        $data = $this->dashboardDataService->getAdminDashboardData();

        // Fallback values in case the data is not set
        
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

}
