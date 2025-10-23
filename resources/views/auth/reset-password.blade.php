@extends('layouts.app')

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-key text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Reset Your Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                Please enter your new password below to complete the reset process.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent @error('email') border-red-400 @enderror"
                           placeholder="Enter your email address"
                           value="{{ $email ?? old('email') }}" readonly>
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        New Password
                    </label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent @error('password') border-red-400 @enderror"
                           placeholder="Enter your new password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        Confirm New Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent @error('password_confirmation') border-red-400 @enderror"
                           placeholder="Confirm your new password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-500/20 border border-blue-400/30 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fi fi-rr-shield-check text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-300">
                                Password Requirements
                            </h3>
                            <div class="mt-2 text-sm text-blue-200">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>At least 8 characters long</li>
                                    <li>Include uppercase and lowercase letters</li>
                                    <li>Include at least one number</li>
                                    <li>Use a strong, unique password</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        <i class="fi fi-rr-check mr-2"></i>
                        Reset Password
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
