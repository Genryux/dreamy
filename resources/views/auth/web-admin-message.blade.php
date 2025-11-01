@extends('layouts.auth')

@section('title', 'Admin Access Required')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <!-- Icon -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold text-gray-900 mb-2">
                    Desktop App Required
                </h1>
                <p class="text-gray-600">
                    Hello, <span class="font-medium">{{ auth()->user()->name }}</span>
                </p>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <p class="text-gray-700 leading-relaxed mb-4">
                    You're logged in as an administrator, but administrative features are only available through the <strong>Desktop Application</strong>.
                </p>
                <p class="text-sm text-gray-500">
                    Please download and install the Dreamy School Management desktop app to access administrative features.
                </p>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-gray-400 mt-6">
            Logged in as: {{ auth()->user()->email }}
        </p>
    </div>
</div>
@endsection
