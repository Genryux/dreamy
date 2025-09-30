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
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'applicant_status' => '',
                'interview_status' => ''
            ]);

            return $user;
        });

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

        // Send to teacher roles (head_teacher, teacher)
        $teachers = User::role(['head_teacher', 'teacher'])->get();
        Notification::send($teachers, new QueuedNotification(
            "New Student Registration",
            "A new student " . $user->first_name . ". This notification is only for teachers or head teacher",
            url('/enrolled-students')
        ));

        // Send to student roles (for mobile app)
        $students = User::role(['student'])->get();
        Notification::send($students, new QueuedNotification(
            "Welcome to the System",
            "Welcome " . $user->first_name . "! Your account has been created successfully.",
            null // No URL needed for mobile
        ));

        // Send broadcast for real-time updates (separate broadcasts, no N+1)
        Notification::route('broadcast', 'admins')
            ->notify(new ImmediateNotification(
                "New User Registered",
                $user->first_name . " has just registered.",
                url('/admin/users')
            ));

        Notification::route('broadcast', 'teachers')
            ->notify(new ImmediateNotification(
                "New Student Registration",
                "A new student " . $user->first_name . ". This notification is only for teachers or head teacher",
                url('/enrolled-students')
            ));

        Notification::route('broadcast', 'students')
            ->notify(new ImmediateNotification(
                "Welcome to the System",
                "Welcome " . $user->first_name . "! Your account has been created successfully.",
                null // No URL needed for mobile
            ));

        // Check roles and redirect accordingly
        if ($user->hasRole('teacher')) {
            return redirect()->route('admin');
        } elseif ($user->hasRole('applicant')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student');
        } elseif ($user->hasRole('registrar')) {
            return redirect()->route('admin'); // Assuming registrar uses admin dashboard
        }




        return redirect('/');
    }
}
