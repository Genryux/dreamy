@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fi fi-rr-user mr-3 text-[#199BCF]"></i>
                Profile Settings
            </h1>
            <p class="text-[14px] text-gray-600 mt-1">Manage your personal information and security settings</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fi fi-rr-check-circle text-green-500 mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fi fi-rr-exclamation text-red-500 mr-3"></i>
                <div>
                    <p class="text-red-800 font-medium">Please fix the errors below:</p>
                    <ul class="mt-2 text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Profile Summary -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
            <i class="fi fi-rr-info mr-2"></i>
            Current Profile Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="flex items-center">
                <i class="fi fi-rr-user mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}
                </span>
            </div>
            <div class="flex items-center">
                <i class="fi fi-rr-envelope mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>Email:</strong> {{ $user->email }}
                </span>
            </div>
            <div class="flex items-center">
                <i class="fi fi-rr-shield-check mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>PIN Status:</strong> {{ $user->pin_enabled ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-user mr-2 text-[#199BCF]"></i>
                Personal Information
            </h2>
            
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="first_name" 
                               value="{{ old('first_name', $user->first_name) }}" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter your first name"
                               required>
                        @error('first_name')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="last_name" 
                               value="{{ old('last_name', $user->last_name) }}" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter your last name"
                               required>
                        @error('last_name')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-envelope mr-2"></i>
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter your email address"
                               required>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-row justify-end items-center gap-3 pt-4">
                    <button type="submit" 
                            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-rr-check flex justify-center items-center"></i>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Change -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-lock mr-2 text-[#199BCF]"></i>
                Change Password
            </h2>
            
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-lock mr-2"></i>
                            Current Password
                        </label>
                        <input type="password" 
                               name="current_password" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter current password">
                        @error('current_password')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-lock mr-2"></i>
                            New Password
                        </label>
                        <input type="password" 
                               name="password" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter new password">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-lock mr-2"></i>
                            Confirm New Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Confirm new password">
                    </div>

                    @if($user->pin_enabled)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-shield-check mr-2"></i>
                            PIN Verification <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="pin_verification" 
                               maxlength="6"
                               class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                               placeholder="Enter your 6-digit PIN">
                        @error('pin_verification')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <i class="fi fi-rr-exclamation mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @endif
                </div>

                @if($user->pin_enabled)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center">
                        <i class="fi fi-rr-info text-blue-600 mr-2"></i>
                        <p class="text-blue-800 text-sm">
                            <strong>Security:</strong> PIN verification is required to change your password.
                        </p>
                    </div>
                </div>
                @endif

                <div class="flex flex-row justify-end items-center gap-3 pt-4">
                    <button type="submit" 
                            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-rr-check flex justify-center items-center"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- PIN Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-shield-check mr-2 text-[#199BCF]"></i>
                PIN Settings
            </h2>
            
            @if(!$user->pin)
                <!-- PIN Setup Form (No PIN exists) -->
                <form method="POST" action="{{ route('profile.pin.setup') }}" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fi fi-rr-shield-check mr-2"></i>
                                New PIN <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="new_pin" 
                                   maxlength="6"
                                   class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                   placeholder="Enter new PIN (6 digits)"
                                   required>
                            @error('new_pin')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fi fi-rr-exclamation mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fi fi-rr-shield-check mr-2"></i>
                                Confirm New PIN <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="new_pin_confirmation" 
                                   maxlength="6"
                                   class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                   placeholder="Confirm new PIN (6 digits)"
                                   required>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fi fi-rr-info text-green-600 mr-2"></i>
                            <p class="text-green-800 text-sm">
                                <strong>Setup PIN:</strong> Create a 6-digit PIN for additional security verification.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row justify-end items-center gap-3 pt-4">
                        <button type="submit" 
                                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i class="fi fi-rr-check flex justify-center items-center"></i>
                            Setup PIN
                        </button>
                    </div>
                </form>
            @elseif($user->pin && !$user->pin_enabled)
                <!-- PIN Disabled - Show Enable Option -->
                <div class="space-y-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fi fi-rr-shield-cross text-yellow-600 mr-2"></i>
                            <h3 class="text-md font-semibold text-yellow-800">PIN is Currently Disabled</h3>
                        </div>
                        <p class="text-yellow-800 text-sm mb-4">
                            Your PIN is set up but currently disabled. You can re-enable it anytime to add an extra layer of security.
                        </p>
                        
                        <form method="POST" action="{{ route('profile.pin.enable') }}" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fi fi-rr-shield-check mr-2"></i>
                                        Enter PIN to Re-enable
                                    </label>
                                    <input type="password" 
                                           name="current_pin" 
                                           id="enable_current_pin"
                                           maxlength="6"
                                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                           placeholder="Enter your 6-digit PIN"
                                           required>
                                    @error('current_pin')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fi fi-rr-exclamation mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-row justify-end items-center gap-3 pt-4">
                                <button type="submit" 
                                        class="self-end flex flex-row justify-center items-center bg-green-600 py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-green-700 hover:scale-95 transition duration-200 shadow-green-600/20 hover:shadow-green-700/20 shadow-lg truncate">
                                    <i class="fi fi-rr-check flex justify-center items-center"></i>
                                    Enable PIN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <!-- PIN Management (Update/Disable) -->
                <div class="space-y-6">
                    <!-- Update PIN Form -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fi fi-rr-edit mr-2"></i>
                            Update PIN
                        </h3>
                        
                        <form method="POST" action="{{ route('profile.pin.update') }}" class="space-y-4" id="update-pin-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fi fi-rr-shield-check mr-2"></i>
                                        Current PIN
                                    </label>
                                    <input type="password" 
                                           name="current_pin" 
                                           id="update_current_pin"
                                           maxlength="6"
                                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                           placeholder="Enter current PIN (6 digits)">
                                    @error('current_pin')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fi fi-rr-exclamation mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fi fi-rr-shield-check mr-2"></i>
                                        New PIN
                                    </label>
                                    <input type="password" 
                                           name="new_pin" 
                                           id="update_new_pin"
                                           maxlength="6"
                                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                           placeholder="Enter new PIN (6 digits)">
                                    @error('new_pin')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fi fi-rr-exclamation mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fi fi-rr-shield-check mr-2"></i>
                                        Confirm New PIN
                                    </label>
                                    <input type="password" 
                                           name="new_pin_confirmation" 
                                           id="update_new_pin_confirmation"
                                           maxlength="6"
                                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                           placeholder="Confirm new PIN (6 digits)">
                                </div>
                            </div>

                            <div class="flex flex-row justify-end items-center gap-3 pt-4">
                                <button type="submit" 
                                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                                    <i class="fi fi-rr-check flex justify-center items-center"></i>
                                    Update PIN
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Disable PIN Form -->
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <h3 class="text-md font-semibold text-red-800 mb-3 flex items-center">
                            <i class="fi fi-rr-cross mr-2"></i>
                            Disable PIN
                        </h3>
                        
                        <form method="POST" action="{{ route('profile.pin.disable') }}" class="space-y-4" id="disable-pin-form">
                            @csrf
                            @method('DELETE')
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fi fi-rr-shield-check mr-2"></i>
                                        Current PIN
                                    </label>
                                    <input type="password" 
                                           name="current_pin" 
                                           id="disable_current_pin"
                                           maxlength="6"
                                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                                           placeholder="Enter current PIN to disable"
                                           required>
                                    @error('current_pin')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <i class="fi fi-rr-exclamation mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="bg-red-100 border border-red-300 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fi fi-rr-exclamation text-red-600 mr-2"></i>
                                    <p class="text-red-800 text-sm">
                                        <strong>Warning:</strong> Disabling your PIN will remove the additional security layer. You will no longer need to enter your PIN for password changes.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-row justify-end items-center gap-3 pt-4">
                                <button type="button" 
                                        onclick="openDisablePinModal()"
                                        class="self-end flex flex-row justify-center items-center bg-red-600 py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-red-700 hover:scale-95 transition duration-200 shadow-red-600/20 hover:shadow-red-700/20 shadow-lg truncate">
                                    <i class="fi fi-rr-cross flex justify-center items-center"></i>
                                    Disable PIN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                <div class="flex items-center">
                    <i class="fi fi-rr-info text-yellow-600 mr-2"></i>
                    <p class="text-yellow-800 text-sm">
                        <strong>Security Note:</strong> Your PIN is used for additional security verification. Keep it secure and don't share it with anyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disable PIN Confirmation Modal -->
<div id="disable-pin-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fi fi-rr-exclamation text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Temporarily Disable PIN</h3>
                    <p class="text-sm text-gray-500">You can re-enable it anytime</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 mb-3">
                    Are you sure you want to disable your PIN? This will temporarily disable the additional security layer. You can re-enable it anytime by entering your PIN.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center">
                        <i class="fi fi-rr-info text-yellow-600 mr-2"></i>
                        <p class="text-yellow-800 text-sm">
                            <strong>Note:</strong> Your PIN will be kept and you can re-enable it later without setting up a new one.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-row justify-end items-center gap-3">
                <button type="button" 
                        onclick="closeDisablePinModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">
                    Cancel
                </button>
                <button type="button" 
                        onclick="confirmDisablePin()"
                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition duration-150">
                    Yes, Temporarily Disable
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openDisablePinModal() {
    document.getElementById('disable-pin-modal').classList.remove('hidden');
}

function closeDisablePinModal() {
    document.getElementById('disable-pin-modal').classList.add('hidden');
}

function confirmDisablePin() {
    // Submit the disable PIN form
    const form = document.getElementById('disable-pin-form');
    if (form) {
        form.submit();
    }
    closeDisablePinModal();
}

// Close modal when clicking outside
document.getElementById('disable-pin-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDisablePinModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDisablePinModal();
    }
});
</script>
@endsection
