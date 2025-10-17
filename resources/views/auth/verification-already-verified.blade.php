@extends('layouts.app')

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-check-circle text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Already Verified
            </h2>
        </div>

        @if (session('message'))
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-info text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            {{ session('message') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165] mb-6">
                    <i class="fi fi-rr-envelope-open text-white text-3xl"></i>
                </div>
                
                <h3 class="text-lg font-medium text-white mb-2">
                    Email Already Verified
                </h3>
                
                <p class="text-sm text-gray-300 mb-8">
                    Your email address has already been verified. You can now access all features of your account.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('admission.dashboard') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        Go to Your Dashboard
                    </a>

                    <div class="text-center">
                        <a href="{{ route('register') }}" 
                           class="text-sm text-gray-300 hover:text-white transition duration-150">
                            <i class="fi fi-rr-user-plus mr-1"></i>
                            Create Another Account
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-400">
                Thank you for choosing Dreamy School Portal!
            </p>
        </div>
    </div>
</div>
@endsection
