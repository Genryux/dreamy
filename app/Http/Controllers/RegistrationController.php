<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\User;
use App\Notifications\QueuedNotification;
use App\Notifications\ImmediateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{
    public function create()
    {

        return view('auth.register.create');
    }

    public function store(Request $request)
    {

        //dd($request);

        $user = $request->validate([

            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(), 'confirmed']

        ]);

        $user = DB::transaction(function () use ($user) {
            $user = User::create($user);

            $user->assignRole('applicant');

            Applicants::create([
                'enrollment_period_id' => null,
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'applicant_status' => null,
                'interview_status' => ''
            ]);

            return $user;
        });

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        // Log registration for security
        \Log::info('New user registration (email verification required)', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        // Temporarily log in the user so they can access the verification notice page
        Auth::login($user);

        // Send notifications after transaction is committed
        // Use Laravel's bulk notification to avoid N+1 problem
        
        // Send to admin roles (registrar, super_admin)
        $admins = User::role(['registrar', 'super_admin'])->get();
        Notification::send($admins, new QueuedNotification(
            "New User Registered",
            $user->first_name . " has just registered.",
            url('/admin/users')
        ));

        // Send broadcast for real-time updates (separate broadcasts, no N+1)
        Notification::route('broadcast', 'admins')
            ->notify(new ImmediateNotification(
                "New User Registered",
                $user->first_name . " has just registered.",
                url('/admin/users')
            ));

        // Redirect to email verification notice instead of auto-login
        return redirect()->route('verification.notice')
            ->with('message', 'Registration successful! Please check your email and click the verification link to activate your account.');
    }
}
