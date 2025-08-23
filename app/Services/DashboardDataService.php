<?php

namespace App\Services;

use App\Models\Documents;
use App\Models\DocumentSubmissions;
use Illuminate\Support\Facades\Auth;

class DashboardDataService
{
    public function __construct(
        protected AcademicTermService $academicTermService,
        protected EnrollmentPeriodService $enrollmentPeriodService,
        protected ApplicationFormService $applicationFormService,
        protected ApplicantService $applicant
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

        $applicant = $this->applicant->fetchAuthenticatedApplicant();

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

        $assignedDocuments = $applicant->assignedDocuments;

        $documents = Documents::all();

        if (!$assignedDocuments) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
                'applicant' => $applicant,
                'assignedDocuments' => null
            ];
        }

        // dd($applicant->submissions-get);

        //$documentSubmissions = DocumentSubmissions::where('applicants_id', $applicant->id)->get()->keyBy('documents_id');



        return [
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
            'applicant' => $applicant,
            'assignedDocuments' => $assignedDocuments,
            'documents' => $documents
        ];
    }
}
