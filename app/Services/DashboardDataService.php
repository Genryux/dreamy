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
        $totalApplications = $this->applicationFormService->fetchApplicationWithAnyStatus(['Pending', 'Accepted', 'Pending Documents', 'Rejected','Completed-Failed', 'Officially Enrolled'])->count();
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

    /**
     * Get enrollment summary for a completed enrollment period
     */
    public function getEnrollmentSummary($enrollmentPeriodId = null)
    {
        $currentAcadTerm = $this->academicTermService->fetchCurrentAcademicTerm();
        
        if (!$currentAcadTerm) {
            return null;
        }

        // If no specific enrollment period ID provided, get the most recent completed one
        if (!$enrollmentPeriodId) {
            $enrollmentPeriod = \App\Models\EnrollmentPeriod::where('academic_terms_id', $currentAcadTerm->id)
                ->where('status', 'Closed')
                ->orderBy('application_end_date', 'desc')
                ->first();
        } else {
            $enrollmentPeriod = \App\Models\EnrollmentPeriod::find($enrollmentPeriodId);
        }

        if (!$enrollmentPeriod) {
            return null;
        }

        // Get all applications for this enrollment period
        $applications = \App\Models\Applicants::where('enrollment_period_id', $enrollmentPeriod->id)->get();
        
        // Calculate statistics based on enrollment flow
        $totalApplications = $applications->count();
        
        // Count applications that were ever accepted (including those who moved to next stages)
        $everAcceptedApplications = $applications->whereIn('application_status', ['Accepted', 'Pending-Documents', 'Officially Enrolled'])->count();
        
        // Current status counts
        $currentlyAccepted = $applications->where('application_status', 'Accepted')->count();
        $officiallyEnrolled = $applications->where('application_status', 'Officially Enrolled')->count();
        $rejectedApplications = $applications->where('application_status', 'Rejected')->count();
        $pendingDocuments = $applications->where('application_status', 'Pending-Documents')->count();
        $completedFailed = $applications->where('application_status', 'Completed-Failed')->count();
        $pendingApplications = $applications->where('application_status', 'Pending')->count();
        
        // Calculate acceptance rate based on applications that were ever accepted
        $acceptanceRate = $totalApplications > 0 ? round(($everAcceptedApplications / $totalApplications) * 100, 1) : 0;
        
        // Calculate enrollment success rate (from ever accepted to enrolled)
        $enrollmentSuccessRate = $everAcceptedApplications > 0 ? round(($officiallyEnrolled / $everAcceptedApplications) * 100, 1) : 0;
        
        // Calculate overall success rate (from application to enrollment)
        $overallSuccessRate = $totalApplications > 0 ? round(($officiallyEnrolled / $totalApplications) * 100, 1) : 0;

        // Get program breakdown
        $programBreakdown = $applications->groupBy('program_id')->map(function ($group, $programId) {
            $program = \App\Models\Program::find($programId);
            return [
                'program_name' => $program ? $program->name : 'Unknown Program',
                'program_code' => $program ? $program->code : 'N/A',
                'total_applications' => $group->count(),
                'ever_accepted' => $group->whereIn('application_status', ['Accepted', 'Pending-Documents', 'Officially Enrolled'])->count(),
                'currently_accepted' => $group->where('application_status', 'Accepted')->count(),
                'enrolled' => $group->where('application_status', 'Officially Enrolled')->count(),
            ];
        });

        return [
            'enrollment_period' => $enrollmentPeriod,
            'total_applications' => $totalApplications,
            'ever_accepted_applications' => $everAcceptedApplications,
            'currently_accepted_applications' => $currentlyAccepted,
            'officially_enrolled' => $officiallyEnrolled,
            'rejected_applications' => $rejectedApplications,
            'pending_documents' => $pendingDocuments,
            'completed_failed' => $completedFailed,
            'pending_applications' => $pendingApplications,
            'acceptance_rate' => $acceptanceRate,
            'enrollment_success_rate' => $enrollmentSuccessRate,
            'overall_success_rate' => $overallSuccessRate,
            'program_breakdown' => $programBreakdown,
            'period_duration' => \Carbon\Carbon::parse($enrollmentPeriod->application_start_date)->diffInDays(\Carbon\Carbon::parse($enrollmentPeriod->application_end_date)),
            'max_applicants' => $enrollmentPeriod->max_applicants,
            'capacity_utilization' => $enrollmentPeriod->max_applicants > 0 ? round(($totalApplications / $enrollmentPeriod->max_applicants) * 100, 1) : 0
        ];
    }
}
