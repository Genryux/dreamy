@extends('layouts.auth')

@section('title', 'Setup Security PIN')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#199BCF] to-[#C8A165]">
                <i class="fi fi-rr-shield-check text-white text-xl flex justify-center items-center"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Setup Security PIN
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                Create a 6-digit PIN for additional security
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-cross-circle text-red-400"></i>
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

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 py-8 px-4 shadow-lg rounded-xl sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('auth.pin.setup.store') }}">
                @csrf
                
                <div>
                    <label for="new_pin" class="block text-sm font-medium text-white mb-2">
                        New PIN
                    </label>
                    <input id="new_pin" 
                           name="new_pin" 
                           type="password" 
                           maxlength="6"
                           required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent text-center text-2xl tracking-widest @error('new_pin') border-red-400 @enderror"
                           placeholder="••••••">
                    <p class="mt-1 text-xs text-gray-300">Enter a 6-digit PIN</p>
                    @error('new_pin')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_pin_confirmation" class="block text-sm font-medium text-white mb-2">
                        Confirm PIN
                    </label>
                    <input id="new_pin_confirmation" 
                           name="new_pin_confirmation" 
                           type="password" 
                           maxlength="6"
                           required 
                           class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199BCF] focus:border-transparent text-center text-2xl tracking-widest @error('new_pin_confirmation') border-red-400 @enderror"
                           placeholder="••••••">
                    <p class="mt-1 text-xs text-gray-300">Re-enter your 6-digit PIN</p>
                    @error('new_pin_confirmation')
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
                                Security Information
                            </h3>
                            <div class="mt-2 text-sm text-blue-200">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Your PIN will be required for sensitive operations</li>
                                    <li>You can disable/enable your PIN anytime in settings</li>
                                    <li>Keep your PIN secure and don't share it</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-4 md:py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        <i class="fi fi-rr-shield-check mr-2 flex justify-center items-center"></i>
                        Setup Security PIN
                    </button>
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
