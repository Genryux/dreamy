@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <div class="text-center">
            <!-- Desktop Icon -->
            <div class="mx-auto h-24 w-24 text-blue-500">
                <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Desktop App Required
            </h2>
            
            <p class="mt-2 text-sm text-gray-600">
                Hello {{ auth()->user()->name }}! You're logged in as an administrator, but administrative features are only available on the desktop application.
            </p>
        </div>
        
        <div class="mt-8 space-y-6">
            <!-- Information Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-blue-800">
                            Why do I need the desktop app?
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p class="mb-2">As an administrator, you have access to sensitive features that require enhanced security:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Student management and enrollment</li>
                                <li>Financial records and invoicing</li>
                                <li>User account management</li>
                                <li>System settings and configuration</li>
                                <li>Advanced reporting and analytics</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Instructions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">How to Access Administrative Features</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">1</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Download Desktop App</h4>
                            <p class="text-sm text-gray-600">Get the Dreamy School Management desktop application installer</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">2</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Install and Launch</h4>
                            <p class="text-sm text-gray-600">Run the installer and launch the desktop application</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">3</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Login with Same Credentials</h4>
                            <p class="text-sm text-gray-600">Use your current login credentials to access full administrative features</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alternative Options -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Alternative Options</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-gray-900 mb-2">For Students/Applicants</h4>
                        <p class="text-sm text-gray-600 mb-3">If you need to access student or admission features, you can use the web version.</p>
                        <a href="/admission" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                            </svg>
                            Go to Admission Portal
                        </a>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-gray-900 mb-2">Need Help?</h4>
                        <p class="text-sm text-gray-600 mb-3">Contact your system administrator for assistance with the desktop application.</p>
                        <button onclick="window.location.href='/logout'" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
