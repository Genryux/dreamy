<?php 

namespace App\Services;

use App\Models\Applicant;
use App\Models\EnrollmentPeriod;        
use App\Models\AcademicTerms;

class DashboardDataService
{
    public function getAdminDashboardData()
    {
        $currentAcadTerm = AcademicTerms::where('is_active', true)->first();

        if ($currentAcadTerm) {
            $activeEnrollmentPeriod = EnrollmentPeriod::where('active', true)
                ->where('academic_terms_id', $currentAcadTerm->id)
                ->first();

            $pendingApplicationsCount = Applicant::withStatus('Pending')->count();
            $selectedApplicationsCount = Applicant::withStatus('Selected')->count();
            $applicationCount = Applicant::withAnyStatus(['Pending', 'Selected', 'Pending Documents'])->count();
            $recentApplications = Applicant::where('application_status', 'Pending')
                ->latest()
                ->limit(10)
                ->get();

            return [
                'recentApplications' => $recentApplications,
                'pendingApplicationsCount' => $pendingApplicationsCount,
                'selectedApplicationsCount' => $selectedApplicationsCount,
                'applicationCount' => $applicationCount,
                'currentAcadTerm' => $currentAcadTerm,
                'activeEnrollmentPeriod' => $activeEnrollmentPeriod
            ];
        }

        return null;
    }
}