@extends('layouts.app')

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-lock text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Forgot Your Password?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                No problem. Just let us know your email address and we will email you a password reset link.
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                        <p class="mt-1 text-sm text-green-700">
                            Please check your email and follow the instructions to reset your password.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent @error('email') border-red-400 @enderror"
                           placeholder="Enter your email address"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-500/20 border border-yellow-400/30 rounded-md p-4">
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
                                    <li>The reset link will expire in 60 minutes</li>
                                    <li>Check your spam folder if you don't see the email</li>
                                    <li>You can only use the reset link once</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        <i class="fi fi-rr-envelope mr-2"></i>
                        Send Password Reset Link
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" 
                           class="text-sm text-gray-300 hover:text-white transition duration-150">
                            <i class="fi fi-rr-arrow-left mr-1"></i>
                            Back to Login
                        </a>
                    </div>
                </div>
            </form>
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
