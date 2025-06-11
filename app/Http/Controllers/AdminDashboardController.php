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

        if (!$data) {
            return redirect()->route('admin')->with('error', 'No active academic term found.');
        }

        $recentApplications = $data['recentApplications'];
        $pendingApplicationsCount = $data['pendingApplicationsCount'];
        $selectedApplicationsCount = $data['selectedApplicationsCount'];
        $applicationCount = $data['applicationCount'];
        $currentAcadTerm = $data['currentAcadTerm'];
        $activeEnrollmentPeriod = $data['activeEnrollmentPeriod'];

        return view('user-admin.dashboard', [
            'applications' => $recentApplications,
            'pendingApplicationsCount' => $pendingApplicationsCount,
            'selectedApplicationsCount' => $selectedApplicationsCount,
            'applicationCount' => $applicationCount,
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod
        ]);

    }

}
