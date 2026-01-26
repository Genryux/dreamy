@extends('layouts.auth')

@section('title', 'Mobile App Required')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
        <div class="max-w-lg w-full">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <!-- Icon -->
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fi fi-rr-mobile-button text-3xl text-blue-600 flex justify-center items-center"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2">
                        Mobile App Required
                    </h1>
                    <p class="text-gray-600">
                        Hello, <span class="font-medium">{{ auth()->user()->first_name }}</span>
                    </p>
                </div>

                <!-- Message -->
                <div class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You're logged in as a <strong>Student</strong>. Please note that the web portal is restricted to applicants and administrative staff.
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        To access your student dashboard, invoices, and other student features, please download and log in to the <strong>Dreamy Mobile App</strong>.
                    </p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mt-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fi fi-rr-info text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Student Access
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>The student experience is optimized for mobile devices.</p>
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
                             <i class="fi fi-rr-sign-out-alt mr-2"></i>
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
