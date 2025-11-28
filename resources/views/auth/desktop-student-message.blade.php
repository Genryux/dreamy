@extends('layouts.auth')

@section('title', 'Web Browser Required')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
        <div class="max-w-lg w-full">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <!-- Icon -->
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2">
                        Web Browser Required
                    </h1>
                    <p class="text-gray-600">
                        Hello, <span class="font-medium">{{ auth()->user()->name }}</span>
                    </p>
                </div>

                <!-- Message -->
                <div class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You're logged in as a <strong>{{ auth()->user()->hasRole('applicant') ? 'Applicant' : 'Student' }}</strong>, but admission and student features are only available through the
                        <strong>Web Browser</strong>.
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        Please open your web browser and visit the website to access admission forms, view your application status, submit documents, and other student features.
                    </p>
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    Why use the web browser?
                                </h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Easy access from any device</li>
                                        <li>No installation required</li>
                                        <li>Perfect for admission processes</li>
                                        <li>Access application forms and documents</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200 my-6"></div>

                <!-- Action Buttons -->
                <div class="space-y-3">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer Note -->
            <p class="text-center text-xs text-gray-400 mt-6">
                Logged in as: {{ auth()->user()->email }}
            </p>
        </div>
    </div>
@endsection

