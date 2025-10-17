@extends('layouts.auth')

@section('title', 'Setup Security PIN')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-[#199BCF] to-[#C8A165] rounded-full flex items-center justify-center">
                <i class="fi fi-rr-shield-check text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Setup Security PIN
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Create a 6-digit PIN for additional security
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-exclamation text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were errors with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('auth.pin.setup.store') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="new_pin" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-shield-check mr-2"></i>
                        New PIN <span class="text-red-500">*</span>
                    </label>
                    <input id="new_pin" 
                           name="new_pin" 
                           type="password" 
                           maxlength="6"
                           required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#199BCF] focus:border-[#199BCF] focus:z-10 sm:text-sm text-center text-2xl tracking-widest"
                           placeholder="••••••">
                    <p class="mt-1 text-xs text-gray-500">Enter a 6-digit PIN</p>
                </div>

                <div>
                    <label for="new_pin_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-shield-check mr-2"></i>
                        Confirm PIN <span class="text-red-500">*</span>
                    </label>
                    <input id="new_pin_confirmation" 
                           name="new_pin_confirmation" 
                           type="password" 
                           maxlength="6"
                           required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#199BCF] focus:border-[#199BCF] focus:z-10 sm:text-sm text-center text-2xl tracking-widest"
                           placeholder="••••••">
                    <p class="mt-1 text-xs text-gray-500">Re-enter your 6-digit PIN</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-info text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Security Information
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Your PIN will be required for sensitive operations</li>
                                <li>You can disable/enable your PIN anytime in settings</li>
                                <li>Keep your PIN secure and don't share it</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fi fi-rr-shield-check text-white"></i>
                    </span>
                    Setup Security PIN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-format PIN inputs
document.addEventListener('DOMContentLoaded', function() {
    const pinInputs = document.querySelectorAll('input[type="password"][maxlength="6"]');
    
    pinInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Only allow digits
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Auto-focus next input if current is filled
            if (e.target.value.length === 6) {
                const nextInput = e.target.nextElementSibling;
                if (nextInput && nextInput.tagName === 'INPUT') {
                    nextInput.focus();
                }
            }
        });
        
        input.addEventListener('keydown', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
