@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Web Browser Icon -->
            <div class="mx-auto h-24 w-24 text-green-500">
                <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                </svg>
            </div>
            
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Web Browser Required
            </h2>
            
            <p class="mt-2 text-sm text-gray-600">
                {{ $message ?? 'This feature is only available on the web version.' }}
            </p>
            
            <p class="mt-4 text-sm text-gray-500">
                {{ $suggestion ?? 'Please open your web browser and visit the website to access this feature.' }}
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            Why use the web version?
                        </h3>
                        <div class="mt-2 text-sm text-green-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Easy access from any device</li>
                                <li>No installation required</li>
                                <li>Always up-to-date</li>
                                <li>Perfect for admission processes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
