<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{
    public function create() {

        return view('auth.register.create');

    }

    public function store() {

        $attributes = request()->validate([
            
            'name' => ['required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(),'confirmed']

        ]);
        
        $user = User::create($attributes);

        $user->assignRole('applicant');
        
        Auth::login($user);

        return redirect('/');

    }
}
