<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerms;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\PrivateQueuedNotification;
use App\Notifications\PrivateImmediateNotification;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AcademicTermController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            DB::transaction(function () use ($request) {
                $existingTerm = AcademicTerms::where('year', $request->year)
                    ->where('semester', $request->semester)
                    ->first();


                if ($existingTerm) {
                    return redirect()->back()->with('error', 'Academic term already exists.');
                }

                if ($request->is_active) {
                    $activeTerm = AcademicTerms::where('is_active', true)->first();
                    if ($activeTerm) {
                        $activeTerm->update(['is_active' => false]);
                    }
                }

                $validated = $request->validate([
                    'year' => 'required|string|max:255',
                    'semester' => 'required|string|max:255',
                    'start_date' => 'required|date|after_or_equal:today',
                    'end_date' => 'required|date|after:start_date',
                    'is_active' => 'required|boolean',
                    'status' => 'nullable|in:Upcoming,Ongoing,Closing',
                ]);

                // Check for overlapping academic terms
                $overlapping = AcademicTerms::where(function ($query) use ($validated) {
                    $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_date', '<=', $validated['start_date'])
                                ->where('end_date', '>=', $validated['end_date']);
                        });
                })
                    ->exists();

                if ($overlapping) {
                    return redirect()->back()
                        ->withErrors(['start_date' => 'This academic term overlaps with an existing term.'])
                        ->withInput();
                }

                if (empty($validated['status'])) {
                    $validated['status'] = $validated['is_active'] ? 'Ongoing' : 'Upcoming';
                }

                $newTerm = AcademicTerms::create($validated);

                // Log the activity
                activity('academic_term')
                    ->causedBy(auth()->user())
                    ->performedOn($newTerm)
                    ->withProperties([
                        'action' => 'created',
                        'term_details' => $validated,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ])
                    ->log('Academic term created');

                // Auto-seed enrollments if this is the new active term
                if ($newTerm->is_active) {
                    \Artisan::call('db:seed', ['--class' => 'StudentEnrollmentSeeder']);
                }
            });
            return redirect()->back()->with('success', 'Academic term created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Creating new term failed: ' . $e->getMessage());
        }
    }

    public function startNewTerm(Request $request)
    {
        \Log::info('=== START NEW TERM DEBUG ===');
        \Log::info('Request received:', $request->all());
        \Log::info('Session before validation:', session()->all());

        $validated = $request->validate([
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'nullable|in:Upcoming,Ongoing,Closing',
        ]);

        \Log::info('Validation passed:', $validated);

        try {

            $existingTerm = AcademicTerms::where('year', $request->year)
                ->where('semester', $request->semester)
                ->first();

            if ($existingTerm) {
                \Log::info('Existing term found, returning error');
                return redirect()->back()->with('error', 'Academic term already exists.');
            }

            \Log::info('No existing term found, proceeding with creation');

            DB::beginTransaction();

            //deactivate the current term
            $activeTerm = AcademicTerms::where('is_active', true)->first();
            if ($activeTerm) {
                \Log::info('Deactivating current term:', ['term_id' => $activeTerm->id]);
                $activeTerm->update(['is_active' => false]);
            }

            //start new term
            $newTerm = AcademicTerms::create(array_merge($validated, [
                'is_active' => true,
                'status' => $validated['status'] ?? 'Ongoing',
            ]));
            \Log::info('New term created:', ['term_id' => $newTerm->id]);

            // Determine if we should trigger automated tasks (promotion, invoices, notifications)
            // Only trigger when transitioning to a NEW SCHOOL YEAR (2nd semester → 1st semester)
            // Don't trigger when transitioning within SAME SCHOOL YEAR (1st → 2nd semester)
            $shouldTriggerAutomation = false;
            $transitionType = 'initial';
            
            if ($activeTerm) {
                \Log::info('Checking semester transition:', [
                    'from_year' => $activeTerm->year,
                    'from_semester' => $activeTerm->semester,
                    'to_year' => $newTerm->year,
                    'to_semester' => $newTerm->semester,
                ]);

                // Transitioning from 1st to 2nd semester of same year = Same school year
                if ($activeTerm->semester === '1st Semester' && 
                    $newTerm->semester === '2nd Semester' && 
                    $activeTerm->year === $newTerm->year) {
                    $shouldTriggerAutomation = false;
                    $transitionType = 'same_school_year';
                    \Log::info('Same school year transition (1st → 2nd semester) - automation skipped, enrollment records will be created');
                }
                // Transitioning from 2nd semester to 1st semester = New school year
                elseif ($activeTerm->semester === '2nd Semester' && $newTerm->semester === '1st Semester') {
                    $shouldTriggerAutomation = true;
                    $transitionType = 'new_school_year';
                    \Log::info('New school year detected - automation will be triggered');
                }
                // Any other case (e.g., same semester different year, or unusual transitions)
                else {
                    $shouldTriggerAutomation = true;
                    $transitionType = 'other';
                    \Log::info('Other transition type - automation will be triggered');
                }
            }

            //promote students from previous term to the new term (only if transitioning to new school year)
            $continuingStudents = collect();
            if ($shouldTriggerAutomation && $activeTerm) {
                $continuingStudents = collect($this->studentService->promoteStudents($activeTerm, $newTerm));
                \Log::info('Students promoted:', ['count' => $continuingStudents->count()]);
            } 
            // For same-year transitions (1st → 2nd semester), just create enrollment records without promotion
            elseif ($transitionType === 'same_school_year' && $activeTerm) {
                \Log::info('Creating enrollment records for same-year transition without promotion');
                $continuingStudents = $this->studentService->createSameYearEnrollments($activeTerm, $newTerm);
                \Log::info('Enrollment records created:', ['count' => $continuingStudents->count()]);
            }
            else {
                \Log::info('Student promotion skipped - ' . ($activeTerm ? 'within same school year' : 'no previous term'));
            }

            // Log the activity for starting new term
            activity('academic_term')
                ->causedBy(auth()->user())
                ->performedOn($newTerm)
                ->withProperties([
                    'action' => 'started_new_term',
                    'new_term_details' => array_merge($validated, ['is_active' => true]),
                    'previous_term_id' => $activeTerm ? $activeTerm->id : null,
                    'transition_type' => $transitionType,
                    'automation_triggered' => $shouldTriggerAutomation,
                    'students_promoted_count' => $continuingStudents->count(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('New academic term started' . ($shouldTriggerAutomation ? ' and students promoted' : ' (same school year - no promotion)'));

            // Clear section subjects and update student subjects status
            // This happens for ALL term transitions (both same-year and new-year)
            \Log::info('Clearing section subjects and updating student subjects status');
            
            // Update all student_subjects from 'enrolled' to 'taken' for the previous term
            if ($activeTerm) {
                $updatedCount = \DB::table('student_subjects')
                    ->where('academic_terms_id', $activeTerm->id)
                    ->where('status', 'enrolled')
                    ->update(['status' => 'taken']);
                
                \Log::info('Updated student_subjects status to taken:', ['count' => $updatedCount]);
            }
            
            // Clear all section_subjects (assigned subjects to sections)
            // Sections will need to be re-assigned subjects for the new term/semester
            $deletedSectionSubjects = \DB::table('section_subjects')->delete();
            \Log::info('Cleared section subjects:', ['count' => $deletedSectionSubjects]);

            // Clear student section assignments when transitioning to new school year
            // Students who were promoted need to be reassigned to appropriate sections for their new grade level
            if ($shouldTriggerAutomation && $transitionType === 'new_school_year') {
                $clearedSections = \DB::table('students')
                    ->whereNotNull('section_id')
                    ->update(['section_id' => null]);
                \Log::info('Cleared student section assignments for new school year:', ['count' => $clearedSections]);
            }

            // Generate invoices only if automation is triggered
            if ($shouldTriggerAutomation && $continuingStudents->isNotEmpty()) {
                //get all school fees
                $schoolFees = SchoolFee::all();
                \Log::info('School fees found:', ['count' => $schoolFees->count()]);

                $continuingStudents->chunk(100)->each(function ($studentsChunk) use ($schoolFees, $newTerm) {

                    foreach ($studentsChunk as $student) {

                        // Create new invoice
                        $invoice = $student->invoices()->create([
                            'academic_term_id' => $newTerm->id,
                            'status' => 'unpaid'
                        ]);

                        foreach ($schoolFees as $fee) {
                            $invoice->items()->create(
                                [
                                    'school_fee_id' => $fee->id,
                                    'academic_term_id' => $newTerm->id,
                                    'amount' => $fee->amount
                                ]
                            );
                        }
                    }
                });
            } else {
                \Log::info('Invoice generation skipped - automation not triggered or no continuing students');
            }

            DB::commit();
            \Log::info('Transaction committed successfully');

            \Log::info('Session before redirect:', session()->all());
            \Log::info('About to redirect with success message');

            return redirect()->back()->with('success', 'New academic term started successfully!');
        } catch (\Throwable $th) {
            \Log::error('Exception occurred:', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);

            DB::rollBack();
            \Log::info('About to redirect with error message');
            return redirect()->back()->with('error', "An unexpected error occurred while starting the new term.{$th}");
        }
    }

    public function switchTerm(Request $request)
    {
        $validated = $request->validate([
            'term_id' => 'required|exists:academic_terms,id',
        ]);

        DB::beginTransaction();
        try {
            $newTerm = AcademicTerms::findOrFail($validated['term_id']);

            if ($newTerm->is_active) {
                return redirect()->back()->with('error', 'Selected term is already active.');
            }

            // Deactivate current active term
            $activeTerm = AcademicTerms::where('is_active', true)->first();

            if ($activeTerm && $activeTerm->semester === '2nd Semester' && $newTerm->semester === '1st Semester') {
                $unevaluatedCount = $this->studentService->countUnevaluatedStudentsForTerm($activeTerm->id);

                if ($unevaluatedCount > 0) {
                    DB::rollBack();
                    return redirect()->back()->with(
                        'error',
                        "There are still {$unevaluatedCount} unevaluated students in the current term. Please review their promotion eligibility and finalize evaluations before switching to a new academic year."
                    );
                }
            }

            if ($activeTerm) {
                $activeTerm->update(['is_active' => false]);
            }

            // Activate new term
            $newTerm->update([
                'is_active' => true,
                'status' => 'Ongoing',
            ]);

            // Determine if we should trigger automated tasks (promotion, invoices, notifications)
            // Only trigger when transitioning to a NEW SCHOOL YEAR (2nd semester → 1st semester)
            // Don't trigger when transitioning within SAME SCHOOL YEAR (1st → 2nd semester)
            $shouldTriggerAutomation = false;
            $transitionType = 'initial';
            
            if ($activeTerm) {
                \Log::info('Checking semester transition:', [
                    'from_year' => $activeTerm->year,
                    'from_semester' => $activeTerm->semester,
                    'to_year' => $newTerm->year,
                    'to_semester' => $newTerm->semester,
                ]);

                // Transitioning from 1st to 2nd semester of same year = Same school year
                if ($activeTerm->semester === '1st Semester' && 
                    $newTerm->semester === '2nd Semester' && 
                    $activeTerm->year === $newTerm->year) {
                    $shouldTriggerAutomation = false;
                    $transitionType = 'same_school_year';
                    \Log::info('Same school year transition (1st → 2nd semester) - automation skipped, enrollment records will be created');
                }
                // Transitioning from 2nd semester to 1st semester = New school year
                elseif ($activeTerm->semester === '2nd Semester' && $newTerm->semester === '1st Semester') {
                    $shouldTriggerAutomation = true;
                    $transitionType = 'new_school_year';
                    \Log::info('New school year detected - automation will be triggered');
                }
                // Any other case (e.g., same semester different year, or unusual transitions)
                else {
                    $shouldTriggerAutomation = true;
                    $transitionType = 'other';
                    \Log::info('Other transition type - automation will be triggered');
                }
            }

            //promote students from previous term to the new term (only if transitioning to new school year)
            $continuingStudents = collect();
            if ($shouldTriggerAutomation && $activeTerm) {
                $continuingStudents = collect($this->studentService->promoteStudents($activeTerm, $newTerm));
                \Log::info('Students promoted:', ['count' => $continuingStudents->count()]);
            }
            // For same-year transitions (1st → 2nd semester), just create enrollment records without promotion
            elseif ($transitionType === 'same_school_year' && $activeTerm) {
                \Log::info('Creating enrollment records for same-year transition without promotion');
                $continuingStudents = $this->studentService->createSameYearEnrollments($activeTerm, $newTerm);
                \Log::info('Enrollment records created:', ['count' => $continuingStudents->count()]);
            }
            else {
                \Log::info('Student promotion skipped - ' . ($activeTerm ? 'within same school year' : 'no previous term'));
            }

            // Log the activity for switching term
            activity('academic_term')
                ->causedBy(auth()->user())
                ->performedOn($newTerm)
                ->withProperties([
                    'action' => 'switched_term',
                    'new_term_details' => $newTerm->toArray(),
                    'previous_term_id' => $activeTerm ? $activeTerm->id : null,
                    'transition_type' => $transitionType,
                    'automation_triggered' => $shouldTriggerAutomation,
                    'students_promoted_count' => $continuingStudents->count(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Switched to existing academic term' . ($shouldTriggerAutomation ? ' and students promoted' : ' (same school year - no promotion)'));

            // Clear section subjects and update student subjects status
            // This happens for ALL term transitions (both same-year and new-year)
            \Log::info('Clearing section subjects and updating student subjects status');
            
            // Update all student_subjects from 'enrolled' to 'taken' for the previous term
            if ($activeTerm) {
                $updatedCount = \DB::table('student_subjects')
                    ->where('academic_terms_id', $activeTerm->id)
                    ->where('status', 'enrolled')
                    ->update(['status' => 'taken']);
                
                \Log::info('Updated student_subjects status to taken:', ['count' => $updatedCount]);
            }
            
            // Clear all section_subjects (assigned subjects to sections)
            // Sections will need to be re-assigned subjects for the new term/semester
            $deletedSectionSubjects = \DB::table('section_subjects')->delete();
            \Log::info('Cleared section subjects:', ['count' => $deletedSectionSubjects]);

            // Clear student section assignments when transitioning to new school year
            // Students who were promoted need to be reassigned to appropriate sections for their new grade level
            if ($shouldTriggerAutomation && $transitionType === 'new_school_year') {
                $clearedSections = \DB::table('students')
                    ->whereNotNull('section_id')
                    ->update(['section_id' => null]);
                \Log::info('Cleared student section assignments for new school year:', ['count' => $clearedSections]);
            }

            // Generate invoices only if automation is triggered
            if ($shouldTriggerAutomation && $continuingStudents->isNotEmpty()) {
                //get all school fees
                $schoolFees = SchoolFee::all();

                $continuingStudents->chunk(100)->each(function ($studentsChunk) use ($schoolFees, $newTerm) {
                    foreach ($studentsChunk as $student) {
                        // Check if invoice already exists for this term to avoid duplicates
                        $existingInvoice = $student->invoices()->where('academic_term_id', $newTerm->id)->exists();

                        if (!$existingInvoice) {
                            // Create new invoice
                            $invoice = $student->invoices()->create([
                                'academic_term_id' => $newTerm->id,
                                'status' => 'unpaid'
                            ]);

                            foreach ($schoolFees as $fee) {
                                $invoice->items()->create(
                                    [
                                        'school_fee_id' => $fee->id,
                                        'academic_term_id' => $newTerm->id,
                                        'amount' => $fee->amount
                                    ]
                                );
                            }
                        }
                    }
                });
            } else {
                \Log::info('Invoice generation skipped - automation not triggered or no continuing students');
            }

            DB::commit();

            // Send notification to all admin users after switching from 1st to 2nd semester
            if ($transitionType === 'same_school_year' && $activeTerm) {
                $adminUsers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['super_admin', 'registrar', 'admin']);
                })->get();

                foreach ($adminUsers as $adminUser) {
                    $sharedId = 'term-switched-' . $newTerm->id . '-' . uniqid();
                    $adminUser->notify(new PrivateQueuedNotification(
                        'Academic Term Switched Successfully',
                        "Successfully switched to {$newTerm->full_name}. All section subject assignments have been cleared for the new semester. Students' subject enrollment status has been updated from 'Enrolled' to 'Taken' for proper academic record tracking.",
                        url('/dashboard'),
                        $sharedId
                    ));

                    $adminUser->notify(new PrivateImmediateNotification(
                        'Academic Term Switched Successfully',
                        "Successfully switched to {$newTerm->full_name}. All section subject assignments have been cleared for the new semester. Students' subject enrollment status has been updated from 'Enrolled' to 'Taken' for proper academic record tracking.",
                        url('/dashboard'),
                        $sharedId
                    ));
                }
            }

            // Send notification to all admin users after switching from 2nd semester to 1st semester (new school year)
            if ($transitionType === 'new_school_year' && $activeTerm) {
                $adminUsers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['super_admin', 'registrar', 'admin']);
                })->get();

                $promotedCount = $continuingStudents->count();

                foreach ($adminUsers as $adminUser) {
                    $sharedId = 'new-school-year-' . $newTerm->id . '-' . uniqid();
                    $adminUser->notify(new PrivateQueuedNotification(
                        'New School Year Started',
                        "Successfully switched to {$newTerm->full_name}. {$promotedCount} eligible students have been promoted to the next grade level and enrollment confirmations have been sent to their mobile app. All students have been unassigned from their previous sections for new section assignments. Invoices for the new term have been automatically generated for all continuing students.",
                        url('/dashboard'),
                        $sharedId
                    ));

                    $adminUser->notify(new PrivateImmediateNotification(
                        'New School Year Started',
                        "Successfully switched to {$newTerm->full_name}. {$promotedCount} eligible students have been promoted to the next grade level and enrollment confirmations have been sent to their mobile app. All students have been unassigned from their previous sections for new section assignments. Invoices for the new term have been automatically generated for all continuing students.",
                        url('/dashboard'),
                        $sharedId
                    ));
                }
            }

            // Send notification to all teachers after term switch
            if ($activeTerm) {
                $teachers = Teacher::with('user')->where('status', 'active')->get();

                $teacherTitle = '';
                $teacherMessage = '';

                if ($transitionType === 'same_school_year') {
                    $teacherTitle = 'New Semester Started';
                    $teacherMessage = "A new semester has started ({$newTerm->full_name}). All subject assignments you were teaching have been cleared to prepare for new assignments. Please wait for updated teaching schedules from the administration.";
                } elseif ($transitionType === 'new_school_year') {
                    $teacherTitle = 'New School Year Started';
                    $teacherMessage = "A new school year has started ({$newTerm->full_name}). All subject assignments you were teaching have been cleared to prepare for new assignments. Please wait for updated teaching schedules from the administration.";
                }

                if (!empty($teacherTitle)) {
                    foreach ($teachers as $teacher) {
                        if ($teacher->user) {
                            $sharedId = 'teacher-term-switch-' . $newTerm->id . '-' . uniqid();
                            $teacher->user->notify(new PrivateQueuedNotification(
                                $teacherTitle,
                                $teacherMessage,
                                url('/teacher/dashboard'),
                                $sharedId
                            ));

                            $teacher->user->notify(new PrivateImmediateNotification(
                                $teacherTitle,
                                $teacherMessage,
                                url('/teacher/dashboard'),
                                $sharedId
                            ));
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Switched to academic term successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error switching term: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to switch term: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicTerms $academicTerms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicTerms $academicTerms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $academicTerm = AcademicTerms::findOrFail($id);

            // Check if another term with same year and semester exists (excluding current term)
            $existingTerm = AcademicTerms::where('year', $request->year)
                ->where('semester', $request->semester)
                ->where('id', '!=', $id)
                ->first();

            if ($existingTerm) {
                return redirect()->back()->with('error', 'Academic term with this year and semester already exists.');
            }

            // If setting as active, deactivate other terms
            if ($request->is_active == '1') {
                AcademicTerms::where('is_active', true)
                    ->where('id', '!=', $id)
                    ->update(['is_active' => false]);
            }

            $validated = $request->validate([
                'year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'required|in:0,1',
                'status' => 'required|in:Upcoming,Ongoing,Closing',
            ]);

            // Store original values for comparison
            $originalValues = $academicTerm->toArray();

            $academicTerm->update($validated);

            // Log the activity
            activity('academic_term')
                ->causedBy(auth()->user())
                ->performedOn($academicTerm)
                ->withProperties([
                    'action' => 'updated',
                    'original_values' => $originalValues,
                    'new_values' => $validated,
                    'changes' => array_diff_assoc($validated, $originalValues),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Academic term updated');

            // Send notification to admins when status changes
            $originalStatus = $originalValues['status'] ?? null;
            $newStatus = $validated['status'];

            if ($originalStatus !== $newStatus && in_array($newStatus, ['Ongoing', 'Closing'])) {
                $adminUsers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['super_admin', 'registrar', 'admin']);
                })->get();

                $notificationTitle = '';
                $notificationMessage = '';

                if ($newStatus === 'Ongoing') {
                    $notificationTitle = 'Academic Term Status: Ongoing';
                    $notificationMessage = "The academic term {$academicTerm->full_name} is now ongoing. Student promotion eligibility evaluation is currently restricted. Teachers cannot evaluate student subjects during this period.";
                } elseif ($newStatus === 'Closing') {
                    $notificationTitle = 'Academic Term Status: Closing';
                    $notificationMessage = "The academic term {$academicTerm->full_name} is now closing. You can now evaluate students' promotion eligibility. Teachers are now able to submit final evaluations for student subjects.";
                }

                foreach ($adminUsers as $adminUser) {
                    $sharedId = 'term-status-' . $academicTerm->id . '-' . uniqid();
                    $adminUser->notify(new PrivateQueuedNotification(
                        $notificationTitle,
                        $notificationMessage,
                        url('/dashboard'),
                        $sharedId
                    ));

                    $adminUser->notify(new PrivateImmediateNotification(
                        $notificationTitle,
                        $notificationMessage,
                        url('/dashboard'),
                        $sharedId
                    ));
                }

                // Send notification to teachers when status changes to Closing
                if ($newStatus === 'Closing') {
                    $teachers = Teacher::with('user')->where('status', 'active')->get();

                    foreach ($teachers as $teacher) {
                        if ($teacher->user) {
                            $sharedId = 'teacher-term-closing-' . $academicTerm->id . '-' . uniqid();
                            $teacher->user->notify(new PrivateQueuedNotification(
                                'Term Closing: Student Evaluation Now Available',
                                "The academic term {$academicTerm->full_name} is now set to closing. You can now submit final evaluations for student subjects in your classes.",
                                url('/teacher/dashboard'),
                                $sharedId
                            ));

                            $teacher->user->notify(new PrivateImmediateNotification(
                                'Term Closing: Student Evaluation Now Available',
                                "The academic term {$academicTerm->full_name} is now set to closing. You can now submit final evaluations for student subjects in your classes.",
                                url('/teacher/dashboard'),
                                $sharedId
                            ));
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Academic term updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating academic term: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicTerms $academicTerms)
    {
        //
    }
}
