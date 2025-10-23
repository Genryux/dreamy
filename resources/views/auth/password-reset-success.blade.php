@extends('layouts.app')

@section('login_page')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-check-circle text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Password Reset Successful!
            </h2>
        </div>

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165] mb-6">
                    <i class="fi fi-rr-shield-check text-white text-3xl"></i>
                </div>
                
                <h3 class="text-lg font-medium text-white mb-2">
                    Your Password Has Been Updated!
                </h3>
                
                <p class="text-sm text-gray-300 mb-8">
                    Your password has been successfully updated. You can now log in with your new password.
                </p>

                <div class="bg-green-500/20 border border-green-400/30 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fi fi-rr-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-300">
                                Account Secured
                            </h3>
                            <div class="mt-2 text-sm text-green-200">
                                <p>Your account is now secure with your new password. Please log in to continue.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        <i class="fi fi-rr-sign-in mr-2"></i>
                        Continue to Login
                    </a>

                    <div class="text-center">
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-gray-300 hover:text-white transition duration-150">
                            <i class="fi fi-rr-refresh mr-1"></i>
                            Reset Another Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="bg-blue-500/20 border border-blue-400/30 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fi fi-rr-shield-check text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-300">
                        Security Tips
                    </h3>
                    <div class="mt-2 text-sm text-blue-200">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Use a strong, unique password</li>
                            <li>Don't share your password with others</li>
                            <li>Log out when using shared computers</li>
                            <li>Report any suspicious activity immediately</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-400">
                Thank you for keeping your account secure!
            </p>
        </div>
    </div>
</div>
@endsection
