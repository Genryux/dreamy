<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{
    public function create() {

        return view('auth.register.create');

    }

    public function store(Request $request) {

        //dd($request);

        $user = $request->validate([
            
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(),'confirmed']

        ]);
        
        $user = User::create($user);

        $user->assignRole('applicant');

        Applicants::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'applicant_status' => '',
            'interview_status' => ''
        ]);
        
        Auth::login($user);

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
