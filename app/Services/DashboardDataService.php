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

            if ($activeEnrollmentPeriod) {

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

            return [
                'currentAcadTerm' => $currentAcadTerm,
            ];


        }

        return null;
    }

    public function getAdmissionDashboardData()
    {
        // $activeEnrollmentPeriod = EnrollmentPeriod::whereIn('status', ['Ongoing', 'Paused'])->first();

        // if ($activeEnrollmentPeriod) {
        //     return [
        //         'activeEnrollmentPeriod' => $activeEnrollmentPeriod
        //     ];
        // }

        // return null;
        $currentAcadTerm = AcademicTerms::where('is_active', true)->first();

        if (!$currentAcadTerm) {

            return null;

        }

        $activeEnrollmentPeriod = EnrollmentPeriod::where('active', true)
        ->where('academic_terms_id', $currentAcadTerm->id)
        ->first();

        if (!$activeEnrollmentPeriod) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'activeEnrollmentPeriod' => null
            ];

        }

        return [
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod
        ];

    }





}