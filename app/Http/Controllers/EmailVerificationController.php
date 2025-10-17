<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!URL::hasValidSignature($request)) {
            return redirect()->route('verification.failed')
                ->with('error', 'The verification link is invalid or has expired.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('verification.already-verified')
                ->with('message', 'Your email address has already been verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Log successful verification
        \Log::info('Email verification successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return redirect()->route('verification.success')
            ->with('message', 'Your email address has been successfully verified! You can now access your account.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.already-verified')
                ->with('message', 'Your email address has already been verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification link has been sent to your email address.');
    }

    /**
     * Show the email verification notice.
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Show verification success page.
     */
    public function success()
    {
        return view('auth.verification-success');
    }

    /**
     * Show verification failed page.
     */
    public function failed()
    {
        return view('auth.verification-failed');
    }

    /**
     * Show already verified page.
     */
    public function alreadyVerified()
    {
        return view('auth.verification-already-verified');
    }
}