<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Log the activity
        activity('authentication')
            ->causedBy(User::where('email', $request->email)->first())
            ->withProperties([
                'action' => 'password_reset_requested',
                'email' => $request->email,
                'status' => $status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Password reset link requested');

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        // CRITICAL: Only invalidate tokens after successful password reset
        if ($status === Password::PASSWORD_RESET) {
            \DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
        }

        // Log the activity
        $user = User::where('email', $request->email)->first();
        if ($user) {
            activity('authentication')
                ->causedBy($user)
                ->withProperties([
                    'action' => 'password_reset_completed',
                    'email' => $request->email,
                    'status' => $status,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Password reset completed');
        }

        if ($status === Password::PASSWORD_RESET) {
            // Log the successful password reset
            activity('authentication')
                ->causedBy($user)
                ->withProperties([
                    'action' => 'password_reset_completed',
                    'email' => $request->email,
                    'status' => $status,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Password reset completed successfully');
            
            return redirect()->route('password.reset.success');
        } else {
            return back()->withErrors(['email' => [__($status)]]);
        }
    }

    /**
     * Show password reset success page
     */
    public function showSuccessPage()
    {
        return view('auth.password-reset-success');
    }

    /**
     * Clean up expired password reset tokens
     * This method can be called periodically to clean up old tokens
     */
    public function cleanupExpiredTokens()
    {
        $deleted = \DB::table('password_reset_tokens')
            ->where('created_at', '<', now()->subMinutes(60))
            ->delete();
            
        return $deleted;
    }
}