<?php

namespace App\Http\Controllers;

use App\Events\RecentApplicationTableUpdated;
use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\ApplicationForm;
use App\Models\EnrollmentPeriod;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    public function index() {

        $currentAcadTerm = AcademicTerms::where('is_active', true)->first();

        $activeEnrollmentPeriod = EnrollmentPeriod::whereIn('status', ['Ongoing','Paused'])->first();

        //dd($activeEnrollmentPeriod->applications);

        $pending_applications = Applicant::countByStatus('Pending')->count();
        $selected_applications = Applicant::countByStatus('Selected')->count();
        
        $applicationCount = Applicant::countAllStatus(['Pending', 'Selected', 'Pending Documents'])->count();
        $applications = Applicant::where('application_status', 'Pending')->latest()->limit(10)->get();

        return view('user-admin.dashboard', [
            'applications' => $applications,
            'pending_applications' => $pending_applications,
            'selected_applications' => $selected_applications,
            'applicationCount' => $applicationCount,
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod
        ]);
    }

    public function getEnrollmentStat() {

    }
}
