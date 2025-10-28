@extends('layouts.app', ['title' => 'Verification Failed'])

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-red-600">
                <i class="fi fi-rr-cross-circle text-white text-2xl flex justify-center items-center"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Verification Failed
            </h2>
        </div>

        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-cross-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg rounded-xl sm:px-10">
            <div class="text-center">
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-red-600 mb-6">
                    <i class="fi fi-rr-exclamation text-white text-3xl flex justify-center items-center"></i>
                </div>
                
                <h3 class="text-lg font-medium text-white mb-2">
                    Invalid or Expired Link
                </h3>
                
                <p class="text-sm text-gray-300 mb-8">
                    The verification link you clicked is invalid or has expired. This can happen if:
                </p>

                <div class="bg-yellow-500/20 border border-yellow-400/30 rounded-md p-4 mb-6 text-left">
                    <ul class="text-sm text-yellow-200 space-y-2">
                        <li class="flex items-start">
                            <i class="fi fi-rr-clock mr-2 mt-0.5 text-yellow-400"></i>
                            The link has expired (links expire after 24 hours)
                        </li>
                        <li class="flex items-start">
                            <i class="fi fi-rr-link mr-2 mt-0.5 text-yellow-400"></i>
                            The link was already used or modified
                        </li>
                        <li class="flex items-start">
                            <i class="fi fi-rr-shield-exclamation mr-2 mt-0.5 text-yellow-400"></i>
                            The link was copied incorrectly
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-4 md:py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#1A3165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#199BCF] transition duration-150">
                        <i class="fi fi-rr-sign-in mr-2"></i>
                        Sign In to Resend Verification
                    </a>

                    <div class="text-center">
                        <a href="{{ route('register') }}" 
                           class="text-sm text-gray-300 hover:text-white transition duration-150">
                            <i class="fi fi-rr-user-plus mr-1"></i>
                            Create a New Account
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-400">
                Need help? Contact our support team at 
                <a href="mailto:support@dreamyschool.com" class="text-[#199BCF] hover:text-[#C8A165]">
                    support@dreamyschool.com
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
