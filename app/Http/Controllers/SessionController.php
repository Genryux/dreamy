<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{

    public function create() {
        
        return view('auth.session.create');

    }

    public function store() {

        $attributes = request()->validate([
            
            'email' => ['required', 'email'],
            'password' => ['required']

        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'password' => 'Those credentials don\'t match'
            ]);
        }

        request()->session()->regenerate();
        
        // Get the authenticated user
        $user = Auth::user();
        
        // Check roles and redirect accordingly
        if ($user->hasRole('teacher')) {
            return redirect()->route('admin');
        } elseif ($user->hasRole('applicant')) {
            return redirect()->route('admission');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student');
        } elseif ($user->hasRole('registrar')) {
            return redirect()->route('admin'); // Assuming registrar uses admin dashboard
        }
        
        // Default redirect if no specific role is matched
        return redirect('/');

    }

    public function destroy() {

        Auth::logout();

        return redirect('/');

    }

}
