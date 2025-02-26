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
                'password' => 'Those credentials doesn\'t match'
            ]);
        }

        request()->session()->regenerate();
        
        return redirect('/');

    }

    public function destroy() {

        Auth::logout();

        return redirect('/');

    }

}
