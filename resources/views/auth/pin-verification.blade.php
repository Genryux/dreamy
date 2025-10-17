@extends('layouts.auth')

@section('title', 'Enter Security PIN')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-[#199BCF] to-[#C8A165] rounded-full flex items-center justify-center">
                <i class="fi fi-rr-shield-check text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Enter Security PIN
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please enter your 6-digit PIN to continue
            </p>
            <div class="mt-4 text-center">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fi fi-rr-user mr-2"></i>
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-exclamation text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            PIN Verification Failed
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

        @if (session('pin_attempts'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-exclamation text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Security Notice
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Failed attempts: {{ session('pin_attempts') }}/3</p>
                            @if(session('pin_attempts') >= 3)
                                <p class="font-semibold">Account temporarily locked. Please contact administrator.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('auth.pin.verify.store') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-shield-check mr-2"></i>
                        Security PIN <span class="text-red-500">*</span>
                    </label>
                    <input id="pin" 
                           name="pin" 
                           type="password" 
                           maxlength="6"
                           required 
                           autofocus
                           class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#199BCF] focus:border-[#199BCF] focus:z-10 sm:text-sm text-center text-2xl tracking-widest"
                           placeholder="••••••">
                    <p class="mt-1 text-xs text-gray-500">Enter your 6-digit security PIN</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fi fi-rr-info text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Security Verification
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>This additional security step helps protect your account from unauthorized access.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fi fi-rr-shield-check text-white"></i>
                    </span>
                    Verify PIN & Continue
                </button>

                <div class="text-center">
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-sm text-gray-600 hover:text-gray-900 transition duration-150">
                        <i class="fi fi-rr-sign-out mr-1"></i>
                        Sign out and try again
                    </a>
                </div>
            </div>
        </form>

        <!-- Hidden logout form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
// Auto-format PIN input
document.addEventListener('DOMContentLoaded', function() {
    const pinInput = document.getElementById('pin');
    
    pinInput.addEventListener('input', function(e) {
        // Only allow digits
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });
    
    pinInput.addEventListener('keydown', function(e) {
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

    // Auto-submit when 6 digits are entered
    pinInput.addEventListener('input', function(e) {
        if (e.target.value.length === 6) {
            // Small delay to show the complete PIN
            setTimeout(() => {
                e.target.form.submit();
            }, 300);
        }
    });
});
</script>
@endsection
