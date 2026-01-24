<?php

namespace App\Http\Controllers;

use App\Mail\ApplicantArchivedMail;
use App\Models\Applicants;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;
use Illuminate\Support\Facades\Mail;

class ApplicantsController extends Controller
{
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Applicants $applicants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Applicants $applicants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Applicants $applicants)
    {

        //dd($request->all(), $applicants->application_status);

        $request->validate([
            'action' => 'required|string|in:enroll-student',
        ]);

        match ($request->action) {
            'enroll-student' => $applicants->update(['application_status' => 'Officially Enrolled']),
            default => abort(400, 'Invalid action'),
        };

        return redirect()->back();


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Applicants $applicants)
    {
        //
    }

    /**
     * Archive an applicant (manual archive)
     */
    public function archive(Request $request, Applicants $applicant)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
            'send_notification' => 'nullable|boolean',
        ]);

        // Archive the applicant
        $applicant->archive(
            archivedBy: auth()->user(),
            reason: $request->reason,
            type: 'manual'
        );

        // Log activity
        activity('application')
            ->causedBy(auth()->user())
            ->performedOn($applicant)
            ->withProperties([
                'action' => 'applicant_archived',
                'applicant_id' => $applicant->applicant_id,
                'applicant_name' => $applicant->first_name . ' ' . $applicant->last_name,
                'reason' => $request->reason,
                'archive_type' => 'manual',
                'application_status' => $applicant->application_status,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Applicant manually archived');

        // Send notification email to applicant if requested
        if ($request->send_notification && $applicant->user?->email) {
            Mail::to($applicant->user->email)->queue(
                new ApplicantArchivedMail($applicant, $request->reason)
            );
        }

        return redirect()->back()->with('status', 'Applicant has been archived successfully.');
    }

    /**
     * Restore an applicant from archive
     */
    public function restore(Request $request, Applicants $applicant)
    {
        // Restore the applicant
        $applicant->restore(auth()->user());

        // Log activity
        activity('application')
            ->causedBy(auth()->user())
            ->performedOn($applicant)
            ->withProperties([
                'action' => 'applicant_restored',
                'applicant_id' => $applicant->applicant_id,
                'applicant_name' => $applicant->first_name . ' ' . $applicant->last_name,
                'application_status' => $applicant->application_status,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Applicant restored from archive');

        return redirect()->back()->with('status', 'Applicant has been restored successfully.');
    }
}
