@extends('layouts.app', ['title' => 'Verify Email'])

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-envelope text-white text-xl flex justify-center items-center"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Verify Your Email Address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                We've sent a verification link to your email address
            </p>
        </div>

        @if (session('message'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('message') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

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
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165] mb-4">
                    <i class="fi fi-rr-envelope-open text-white text-2xl flex justify-center items-center"></i>
                </div>
                
                <h3 class="text-lg font-medium text-white mb-2">
                    Check Your Email
                </h3>
                
                <p class="text-sm text-gray-300 mb-6">
                    We've sent a verification link to <strong class="text-white">{{ auth()->user()?->email ?? 'your email address' }}</strong>. 
                    Please check your email and click the verification link to activate your account.
                </p>

                <div class="bg-yellow-500/20 border border-yellow-400/30 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fi fi-rr-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-300">
                                Important
                            </h3>
                            <div class="mt-2 text-sm text-yellow-200">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>The verification link will expire in 24 hours</li>
                                    <li>Check your spam folder if you don't see the email</li>
                                    <li>You must verify your email to access your account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @auth
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center py-4 md:py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                                <i class="fi fi-rr-refresh mr-2 flex justify-center items-center"></i>
                                Resend Verification Email
                            </button>
                        </form>

                        <div class="text-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-sm text-gray-300 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-sign-out mr-1"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <a href="{{ route('login') }}" 
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-[#199BCF] to-[#1A3165] hover:from-[#1A3165] hover:to-[#199BCF] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#199BCF] transition duration-150">
                                <i class="fi fi-rr-sign-in mr-2"></i>
                                Sign In to Resend Verification
                            </a>
                        </div>
                    @endauth
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
