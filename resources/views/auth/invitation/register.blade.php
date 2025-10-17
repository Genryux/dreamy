<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ucfirst($role) }} Registration - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <!-- Flaticon -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#1A3165]">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    @if($role === 'teacher')
                        <i class="fi fi-rr-chalkboard-user flex justify-center items-center text-2xl text-blue-600"></i>
                    @elseif($role === 'head_teacher')
                        <i class="fi fi-rr-user-tie text-2xl text-blue-600"></i>
                    @else
                        <i class="fi fi-rr-user-tie text-2xl text-blue-600"></i>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ ucfirst($role) }} Registration
                </h1>
                <p class="text-sm text-gray-600 mt-2">
                    Complete your account setup for {{ config('app.name') }}
                </p>
            </div>

            <!-- User Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Invitation Details</h3>
                <div class="text-sm text-blue-700">
                    <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->invitation_role) }}</p>
                    <p><strong>Expires:</strong> {{ $expires_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('user.register.store', $token) }}" class="space-y-4">
                @csrf

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fi fi-rr-lock mr-2"></i>
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fi fi-rr-lock mr-2"></i>
                        Confirm Password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a>
                        </label>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-xl shadow-xl text-sm font-medium text-white bg-[#199BCF] hover:bg-[#C8A165] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A165] transition duration-200">
                        Complete Registration
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
