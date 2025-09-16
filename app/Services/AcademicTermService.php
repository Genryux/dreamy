<?php

namespace App\Services;

use App\Models\AcademicTerms;
use App\Models\StudentEnrollment;
use App\Models\Invoice;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\DB;

class AcademicTermService
{
    /**
     * Get the current academic term data.
     *
     * @return array
     */
    public function getCurrentAcademicTermData(): array
    {
        // Fetch the current academic term data
        $academicTerm = $this->fetchCurrentAcademicTerm();

        if (!$academicTerm) {
            return [
                'id' => null,
                'name' => null,
                'year' => null,
                'semester' => null,
                'start_date' => null,
                'end_date' => null,
                'is_active' => false,
                'full_name' => null,
            ];
        }

        return [
            'id' => $academicTerm->id,
            'name' => $academicTerm->getFullNameAttribute(),
            'year' => $academicTerm->year,
            'semester' => $academicTerm->semester,
            'start_date' => $academicTerm->start_date,
            'end_date' => $academicTerm->end_date,
            'is_active' => $academicTerm->is_active,
            'full_name' => $academicTerm->getFullNameAttribute(),
        ];
    }

    /**
     * Fetch the current academic term from the database.
     *
     * @return AcademicTerms|null
     */
    public function fetchCurrentAcademicTerm(): ?AcademicTerms
    {
        return AcademicTerms::where('is_active', true)->first();
    }

    /**
     * Get all academic terms ordered by year and semester.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAcademicTerms()
    {
        return AcademicTerms::orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
    }

    /**
     * Get academic term by ID.
     *
     * @param int $id
     * @return AcademicTerms|null
     */
    public function getAcademicTermById(int $id): ?AcademicTerms
    {
        return AcademicTerms::find($id);
    }

    /**
     * Check if there's an active academic term.
     *
     * @return bool
     */
    public function hasActiveTerm(): bool
    {
        return AcademicTerms::where('is_active', true)->exists();
    }

    /**
     * Get academic term statistics for the current term.
     *
     * @return array
     */
    public function getCurrentTermStats(): array
    {
        $activeTerm = $this->fetchCurrentAcademicTerm();
        
        if (!$activeTerm) {
            return [
                'total_enrollments' => 0,
                'confirmed_enrollments' => 0,
                'pending_enrollments' => 0,
                'total_invoices' => 0,
                'total_applications' => 0,
            ];
        }

        $enrollments = StudentEnrollment::where('academic_term_id', $activeTerm->id);
        $invoices = Invoice::where('academic_term_id', $activeTerm->id);
        $applications = ApplicationForm::where('academic_terms_id', $activeTerm->id);

        return [
            'total_enrollments' => $enrollments->count(),
            'confirmed_enrollments' => $enrollments->where('status', 'confirmed')->count(),
            'pending_enrollments' => $enrollments->where('status', 'pending_confirmation')->count(),
            'total_invoices' => $invoices->count(),
            'total_applications' => $applications->count(),
        ];
    }

    /**
     * Validate if a student is enrolled in the current term.
     *
     * @param int $studentId
     * @return bool
     */
    public function isStudentEnrolledInCurrentTerm(int $studentId): bool
    {
        $activeTerm = $this->fetchCurrentAcademicTerm();
        
        if (!$activeTerm) {
            return false;
        }

        return StudentEnrollment::where('student_id', $studentId)
            ->where('academic_term_id', $activeTerm->id)
            ->exists();
    }

    /**
     * Get student's enrollment for the current term.
     *
     * @param int $studentId
     * @return StudentEnrollment|null
     */
    public function getStudentCurrentEnrollment(int $studentId): ?StudentEnrollment
    {
        $activeTerm = $this->fetchCurrentAcademicTerm();
        
        if (!$activeTerm) {
            return null;
        }

        return StudentEnrollment::with(['academicTerm', 'section', 'program'])
            ->where('student_id', $studentId)
            ->where('academic_term_id', $activeTerm->id)
            ->first();
    }

    /**
     * Create a new academic term.
     *
     * @param array $data
     * @return AcademicTerms
     */
    public function createAcademicTerm(array $data): AcademicTerms
    {
        // Deactivate all other terms if this one is set as active
        if ($data['is_active'] ?? false) {
            AcademicTerms::where('is_active', true)->update(['is_active' => false]);
        }

        return AcademicTerms::create($data);
    }

    /**
     * Update an academic term.
     *
     * @param int $id
     * @param array $data
     * @return AcademicTerms|null
     */
    public function updateAcademicTerm(int $id, array $data): ?AcademicTerms
    {
        $term = AcademicTerms::find($id);
        
        if (!$term) {
            return null;
        }

        // If setting this term as active, deactivate others
        if ($data['is_active'] ?? false) {
            AcademicTerms::where('is_active', true)->update(['is_active' => false]);
        }

        $term->update($data);
        return $term->fresh();
    }

    /**
     * Get academic terms with their enrollment counts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAcademicTermsWithStats()
    {
        return AcademicTerms::withCount([
            'enrollments',
            'enrollments as confirmed_enrollments_count' => function ($query) {
                $query->where('status', 'confirmed');
            },
            'applicationForms'
        ])->orderBy('year', 'desc')
          ->orderBy('semester', 'desc')
          ->get();
    }
}
