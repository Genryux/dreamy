<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PrivateNotificationExampleController extends Controller
{
    /**
     * Send a private notification to a specific user
     */
    public function sendPrivateNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:immediate,queued,both',
            'url' => 'nullable|string'
        ]);

        $user = User::findOrFail($request->user_id);
        $sharedId = 'private-' . time() . '-' . uniqid();

        switch ($request->type) {
            case 'immediate':
                // Send only immediate notification (no database storage)
                $user->notify(new PrivateImmediateNotification(
                    $request->title,
                    $request->message,
                    $request->url,
                    $sharedId
                ));
                break;

            case 'queued':
                // Send only queued notification (database + broadcast)
                $user->notify(new PrivateQueuedNotification(
                    $request->title,
                    $request->message,
                    $request->url,
                    $sharedId
                ));
                break;

            case 'both':
                // Send both immediate and queued (for mobile app synchronization)
                $user->notify(new PrivateQueuedNotification(
                    $request->title,
                    $request->message,
                    $request->url,
                    $sharedId
                ));

                $user->notify(new PrivateImmediateNotification(
                    $request->title,
                    $request->message,
                    $request->url,
                    $sharedId
                ));
                break;
        }

        return response()->json([
            'success' => true,
            'message' => "Private {$request->type} notification sent to {$user->email}",
            'shared_id' => $sharedId
        ]);
    }

    /**
     * Example: Send invoice reminder to specific student
     */
    public function sendInvoiceReminder(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        $student = User::findOrFail($request->student_id);
        $sharedId = 'invoice-reminder-' . time() . '-' . uniqid();

        // Send both immediate and queued for mobile app
        $student->notify(new PrivateQueuedNotification(
            "Invoice Reminder",
            "Your invoice #{$request->invoice_number} of ₱" . number_format($request->amount, 2) . " is due. Please settle your account to avoid any inconvenience.",
            url("/student/invoices/{$request->invoice_number}"),
            $sharedId
        ));

        $student->notify(new PrivateImmediateNotification(
            "Invoice Reminder",
            "Your invoice #{$request->invoice_number} of ₱" . number_format($request->amount, 2) . " is due. Please settle your account to avoid any inconvenience.",
            null, // No URL for mobile
            $sharedId
        ));

        return response()->json([
            'success' => true,
            'message' => "Invoice reminder sent to {$student->email}",
            'shared_id' => $sharedId
        ]);
    }

    /**
     * Example: Send enrollment confirmation to specific student
     */
    public function sendEnrollmentConfirmation(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'section' => 'required|string',
            'academic_year' => 'required|string'
        ]);

        $student = User::findOrFail($request->student_id);
        $sharedId = 'enrollment-confirm-' . time() . '-' . uniqid();

        // Send both immediate and queued for mobile app
        $student->notify(new PrivateQueuedNotification(
            "Enrollment Confirmed",
            "Congratulations! Your enrollment for {$request->academic_year} has been confirmed. You are assigned to {$request->section}.",
            url("/student/enrollment"),
            $sharedId
        ));

        $student->notify(new PrivateImmediateNotification(
            "Enrollment Confirmed",
            "Congratulations! Your enrollment for {$request->academic_year} has been confirmed. You are assigned to {$request->section}.",
            null, // No URL for mobile
            $sharedId
        ));

        return response()->json([
            'success' => true,
            'message' => "Enrollment confirmation sent to {$student->email}",
            'shared_id' => $sharedId
        ]);
    }

    /**
     * Example: Send grade notification to specific student
     */
    public function sendGradeNotification(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'grade' => 'required|string',
            'quarter' => 'required|string'
        ]);

        $student = User::findOrFail($request->student_id);

        // Only send queued notification (grades should be persistent)
        $student->notify(new PrivateQueuedNotification(
            "Grade Posted",
            "Your grade for {$request->subject} ({$request->quarter}) has been posted: {$request->grade}",
            url("/student/grades")
        ));

        return response()->json([
            'success' => true,
            'message' => "Grade notification sent to {$student->email}"
        ]);
    }

    /**
     * Bulk send private notifications to multiple users
     */
    public function sendBulkPrivateNotifications(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:immediate,queued,both',
            'url' => 'nullable|string'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $results = [];

        foreach ($users as $user) {
            $sharedId = 'bulk-private-' . time() . '-' . uniqid();

            switch ($request->type) {
                case 'immediate':
                    $user->notify(new PrivateImmediateNotification(
                        $request->title,
                        $request->message,
                        $request->url,
                        $sharedId
                    ));
                    break;

                case 'queued':
                    $user->notify(new PrivateQueuedNotification(
                        $request->title,
                        $request->message,
                        $request->url,
                        $sharedId
                    ));
                    break;

                case 'both':
                    $user->notify(new PrivateQueuedNotification(
                        $request->title,
                        $request->message,
                        $request->url,
                        $sharedId
                    ));

                    $user->notify(new PrivateImmediateNotification(
                        $request->title,
                        $request->message,
                        $request->url,
                        $sharedId
                    ));
                    break;
            }

            $results[] = [
                'user_id' => $user->id,
                'email' => $user->email,
                'shared_id' => $sharedId
            ];
        }

        return response()->json([
            'success' => true,
            'message' => "Private {$request->type} notifications sent to " . count($users) . " users",
            'results' => $results
        ]);
    }
}
