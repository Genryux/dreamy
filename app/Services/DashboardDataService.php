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
                'totalApplications' => null,
                'totalPendingApplications' => null,
                'totalAcceptedApplications' => null,
                'totalPendingDocumentApplications' => null,
                'currentAcadTerm' => null,
                'activeEnrollmentPeriod' => null
            ];
        }

        $activeEnrollmentPeriod = $this->enrollmentPeriodService->getActiveEnrollmentPeriod($currentAcadTerm->id);

        if (!$activeEnrollmentPeriod) {

            return [
                'currentAcadTerm' => $currentAcadTerm,
                'totalApplications' => null,
                'totalPendingApplications' => null,
                'totalAcceptedApplications' => null,
                'totalPendingDocumentApplications' => null,
                'activeEnrollmentPeriod' => null
            ];
        }

        $totalPendingApplications = $this->applicationFormService->fetchApplicationWithStatus('Pending')->count();
        $totalAcceptedApplications = $this->applicationFormService->fetchApplicationWithStatus('Accepted')->count();
        $totalPendingDocumentsApplications = $this->applicationFormService->fetchApplicationWithStatus('Pending-Documents')->count();
        $totalEnrolledApplications = $this->applicationFormService->fetchApplicationWithStatus('Officially Enrolled')->count();
        $totalApplications = $this->applicationFormService->fetchApplicationWithAnyStatus(['Pending', 'Accepted', 'Pending Documents'])->count();
        $recentApplications = $this->applicationFormService->fetchRecentPendingApplications(10);

        return [
            'recentApplications' => $recentApplications,
            'totalPendingApplications' => $totalPendingApplications,
            'totalAcceptedApplications' => $totalAcceptedApplications,
            'totalApplications' => $totalApplications,
            'currentAcadTerm' => $currentAcadTerm,
            'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
            'totalEnrolledApplications' => $totalEnrolledApplications,
            'totalPendingDocumentsApplications' => $totalPendingDocumentsApplications
        ];
    }

    public function getAdmissionDashboardData()
    {
        $cacheKey = 'admission_dashboard_' . auth()->id();
        
        return \Cache::remember($cacheKey, 10, function () { // 5 minutes cache
            // Eager load applicant with all necessary relationships to prevent N+1 queries
            $applicant = $this->applicant->fetchAuthenticatedApplicant();

            if (!$applicant) {
                return ['applicant' => null];
            }

            // Eager load all relationships in one query
            $applicant->load([
                'interview',
                'applicationForm',
                'assignedDocuments.documents',
                'assignedDocuments.submissions'
            ]);

            $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();

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

            // Get assigned documents (already eager loaded)
            $assignedDocuments = $applicant->assignedDocuments()->get();

            // Only load documents that are actually assigned to this applicant
            // This prevents loading ALL documents unnecessarily
            $assignedDocumentIds = $assignedDocuments->pluck('documents_id')->unique();
            $documents = Documents::whereIn('id', $assignedDocumentIds)->get();

            return [
                'currentAcadTerm' => $currentAcadTerm ?? null,
                'activeEnrollmentPeriod' => $activeEnrollmentPeriod,
                'applicant' => $applicant,
                'assignedDocuments' => $assignedDocuments,
                'documents' => $documents
            ];
        });
    }
}
