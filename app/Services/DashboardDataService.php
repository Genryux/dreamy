<?php

namespace App\Services;

use App\Models\Documents;
use Illuminate\Support\Facades\Auth;

class DashboardDataService
{
    public function __construct(
        protected AcademicTermService $academicTermService,
        protected EnrollmentPeriodService $enrollmentPeriodService,
        protected ApplicationFormService $applicationFormService,
        protected Documents $documents
    ) {}

    public function getAdminDashboardData()
    {
        $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        if (!$currentAcadTerm) {

            return [
                'recentApplications' => null,
                'pendingApplicationsCount' => null,
                'selectedApplicationsCount' => null,
                'applicationCount' => null,
                'currentAcadTerm' => null,
                'activeEnrollmentPeriod' => null
            ];
        }

        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($currentAcadTerm->id);

        if (!$activeEnrollmentPeriod) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'recentApplications' => null,
                'pendingApplicationsCount' => null,
                'selectedApplicationsCount' => null,
                'applicationCount' => null,
                'activeEnrollmentPeriod' => null
            ];
        }

        $pendingApplicationsCount = $this->applicationFormService->fetchApplicationWithStatus('Pending')->count();
        $selectedApplicationsCount = $this->applicationFormService->fetchApplicationWithStatus('Selected')->count();
        $applicationCount = $this->applicationFormService->fetchApplicationWithAnyStatus(['Pending', 'Selected', 'Pending Documents'])->count();
        $recentApplications = $this->applicationFormService->fetchRecentPendingApplications(10);

        return [
            'recentApplications' => $recentApplications,
            'pendingApplicationsCount' => $pendingApplicationsCount,
            'selectedApplicationsCount' => $selectedApplicationsCount,
            'applicationCount' => $applicationCount,
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod
        ];
    }

    public function getAdmissionDashboardData()
    {

        $applicant = Auth::user()->applicant;

        if ($applicant) {
            $applicant->load('interview');
        }

        $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();

        if (!$applicant) {

            return [
                'applicant' => null
            ];
        }

        if (!$currentAcadTerm) {

            return [
                'currentAcadTerm' => null,
                'activeEnrollmentPeriod' => null,
                'applicant' => $applicant
            ];
        }

        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($currentAcadTerm->id);

        if (!$activeEnrollmentPeriod) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'activeEnrollmentPeriod' => null,
                'applicant' => $applicant
            ];
        }

        $documents = $this->documents->all();

        if (!$documents) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
                'applicant' => $applicant,
                'documents' => null
            ];
        }

        return [
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
            'applicant' => $applicant,
            'documents' => $documents
        ];
    }
}
