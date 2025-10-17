<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\StudentEnrollment;
use App\Models\AcademicTerms;
use App\Services\StudentDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for authenticated student
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Get recent news and announcements for students
        $news = News::published()
            ->forStudents()
            ->news()
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'content', 'published_at', 'is_announcement']);

        $announcements = News::published()
            ->forStudents()
            ->announcements()
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'content', 'published_at', 'is_announcement']);

        // Get current enrollment status
        $currentEnrollment = null;
        $activeTerm = AcademicTerms::where('is_active', true)->first();
        
        if ($activeTerm && config('app.use_term_enrollments')) {
            $currentEnrollment = StudentEnrollment::with(['academicTerm'])
                ->where('student_id', $user->student->id)
                ->where('academic_term_id', $activeTerm->id)
                ->first();
        }

        // Get student record for detailed information
        $studentRecord = $user->student->record;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'pin_enabled' => $user->pin_enabled,
                ],
                'student' => [
                    'name' => $user->student->getFullNameAttribute(),
                    'lrn' => $user->student->lrn,
                    'grade_level' => $user->student->grade_level,
                    'program' => $user->student->program?->name ?? 'N/A',
                    'age' => $studentRecord?->age,
                    'gender' => $studentRecord?->gender,
                    'contact_number' => $studentRecord?->contact_number,
                    'email_address' => $user->email,
                    'enrollment_date' => $this->getEnrollmentDate($currentEnrollment),
                    'status' => $user->student->status,
                    // Additional student record fields
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'middle_name' => $studentRecord?->middle_name,
                    'birthdate' => $studentRecord?->birthdate,
                    'place_of_birth' => $studentRecord?->place_of_birth,
                    'current_address' => $studentRecord?->current_address,
                    'permanent_address' => $studentRecord?->permanent_address,
                    'father_name' => $studentRecord?->father_name,
                    'father_contact_number' => $studentRecord?->father_contact_number,
                    'mother_name' => $studentRecord?->mother_name,
                    'mother_contact_number' => $studentRecord?->mother_contact_number,
                    'guardian_name' => $studentRecord?->guardian_name,
                    'guardian_contact_number' => $studentRecord?->guardian_contact_number,
                ],
                'enrollment' => [
                    'id' => $currentEnrollment?->id,
                    'status' => $currentEnrollment ? $currentEnrollment->status : 'not_enrolled',
                    'term' => $currentEnrollment ? $currentEnrollment->academicTerm->getFullNameAttribute() : null,
                    'confirmed_at' => $currentEnrollment?->confirmed_at?->format('M j, Y'),
                ],
                'news' => $news->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => \Str::limit(strip_tags($item->content), 100),
                        'published_at' => $item->published_at->format('M j, Y'),
                        'type' => 'news'
                    ];
                }),
                'announcements' => $announcements->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => \Str::limit(strip_tags($item->content), 100),
                        'published_at' => $item->published_at->format('M j, Y'),
                        'type' => 'announcement'
                    ];
                }),
                'recent_activity' => $this->getRecentActivity($user->student),
            ]
        ]);
    }

    /**
     * Get all news and announcements for students
     */
    public function newsAndAnnouncements(Request $request)
    {
        $type = $request->get('type', 'all'); // 'all', 'news', 'announcements'
        $limit = $request->get('limit', 20);

        $query = News::published()->forStudents()->orderBy('published_at', 'desc');

        if ($type === 'news') {
            $query->news();
        } elseif ($type === 'announcements') {
            $query->announcements();
        }

        $items = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->content,
                    'published_at' => $item->published_at->format('M j, Y g:i A'),
                    'type' => $item->is_announcement ? 'announcement' : 'news',
                ];
            })
        ]);
    }

    /**
     * Get specific news/announcement details
     */
    public function newsDetails($id)
    {
        $news = News::published()
            ->forStudents()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'published_at' => $news->published_at->format('M j, Y g:i A'),
                'type' => $news->is_announcement ? 'announcement' : 'news',
            ]
        ]);
    }

    /**
     * Get recent activity for the student
     */
    private function getRecentActivity($student)
    {
        $activities = [];

        // Recent enrollment confirmation
        if (config('app.use_term_enrollments')) {
            $recentEnrollment = StudentEnrollment::where('student_id', $student->id)
                ->whereNotNull('confirmed_at')
                ->orderBy('confirmed_at', 'desc')
                ->first();

            if ($recentEnrollment && $recentEnrollment->confirmed_at->isAfter(now()->subDays(30))) {
                $activities[] = [
                    'type' => 'enrollment_confirmed',
                    'message' => 'Enrollment confirmed for ' . $recentEnrollment->academicTerm->getFullNameAttribute(),
                    'date' => $recentEnrollment->confirmed_at->format('M j, Y'),
                ];
            }
        }

        // Recent document submissions (if available)
        $recentSubmission = $student->submissions()
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentSubmission && $recentSubmission->created_at->isAfter(now()->subDays(30))) {
            $activities[] = [
                'type' => 'document_submitted',
                'message' => 'Document submitted: ' . $recentSubmission->file_name,
                'date' => $recentSubmission->created_at->format('M j, Y'),
            ];
        }

        return collect($activities)->sortByDesc('date')->values()->take(3);
    }

    /**
     * Get enrollment date with proper fallback logic
     */
    private function getEnrollmentDate($currentEnrollment)
    {
        if (!$currentEnrollment) {
            return 'Not available';
        }

        try {
            // Priority 1: confirmed_at (when student confirmed enrollment)
            if ($currentEnrollment->confirmed_at) {
                return $currentEnrollment->confirmed_at->format('M j, Y');
            }

            // Priority 2: enrolled_at (when student was enrolled)
            if ($currentEnrollment->enrolled_at) {
                return $currentEnrollment->enrolled_at->format('M j, Y');
            }

            // Priority 3: created_at (when enrollment record was created)
            if ($currentEnrollment->created_at) {
                return $currentEnrollment->created_at->format('M j, Y');
            }

            return 'Not available';
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Enrollment date formatting error: ' . $e->getMessage(), [
                'enrollment_id' => $currentEnrollment->id ?? 'unknown',
                'confirmed_at' => $currentEnrollment->confirmed_at ?? 'null',
                'enrolled_at' => $currentEnrollment->enrolled_at ?? 'null',
                'created_at' => $currentEnrollment->created_at ?? 'null',
            ]);
            
            return 'Not available';
        }
    }
}
