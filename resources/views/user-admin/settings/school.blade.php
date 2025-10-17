@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fi fi-rr-school mr-3 text-[#199BCF]"></i>
                School Settings
            </h1>
            <p class="text-[14px] text-gray-600 mt-1">Manage your school's basic information and contact details</p>
            @if($setting->exists)
                <div class="flex items-center mt-2 text-xs text-green-600">
                    <i class="fi fi-rr-check-circle mr-1"></i>
                    Settings loaded - {{ $setting->name ?: 'Unnamed School' }}
                </div>
            @else
                <div class="flex items-center mt-2 text-xs text-orange-600">
                    <i class="fi fi-rr-exclamation mr-1"></i>
                    No settings found - using default values
                </div>
            @endif
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

    <!-- Current Data Summary -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
            <i class="fi fi-rr-info mr-2"></i>
            Current Settings Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="flex items-center">
                <i class="fi fi-rr-building mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>School:</strong> {{ $setting->name ?: 'Not set' }}
                </span>
            </div>
            <div class="flex items-center">
                <i class="fi fi-rr-phone mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>Contact:</strong> {{ $setting->phone ?: $setting->email ?: 'Not set' }}
                </span>
            </div>
            <div class="flex items-center">
                <i class="fi fi-rr-usd-circle mr-2 text-blue-600"></i>
                <span class="text-blue-800">
                    <strong>Down Payment:</strong> {{ $setting->down_payment ? '₱' . number_format($setting->down_payment) : 'Not set' }}
                </span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.school.update') }}" class="space-y-6">
        @csrf
        
        <!-- School Information -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-school mr-2 text-[#199BCF]"></i>
                School Information
            </h2>
            
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-building mr-2"></i>
                        School Name <span class="text-red-500">*</span>
                        @if($setting->name)
                            <span class="text-xs text-green-600 ml-2">
                                <i class="fi fi-rr-check mr-1"></i>Has data
                            </span>
                        @endif
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $setting->name) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="Enter full school name"
                           required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tag mr-2"></i>
                        Short Name
                    </label>
                    <input type="text" 
                           name="short_name" 
                           value="{{ old('short_name', $setting->short_name) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="e.g., ABC School">
                    @error('short_name')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-phone-call mr-2 text-[#199BCF]"></i>
                Contact Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone mr-2"></i>
                        Phone Number
                    </label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone', $setting->phone) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="+1 (555) 123-4567">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-envelope mr-2"></i>
                        Email Address
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $setting->email) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="info@schoolname.edu">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-globe mr-2"></i>
                        Website
                    </label>
                    <input type="url" 
                           name="website" 
                           value="{{ old('website', $setting->website) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="https://www.schoolname.edu">
                    @error('website')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-marker mr-2 text-[#199BCF]"></i>
                Address Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-home mr-2"></i>
                        Address Line 1
                    </label>
                    <input type="text" 
                           name="address_line1" 
                           value="{{ old('address_line1', $setting->address_line1) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="Street address, building number">
                    @error('address_line1')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-home mr-2"></i>
                        Address Line 2
                    </label>
                    <input type="text" 
                           name="address_line2" 
                           value="{{ old('address_line2', $setting->address_line2) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="Apartment, suite, unit, building, floor, etc.">
                    @error('address_line2')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-city mr-2"></i>
                        City
                    </label>
                    <input type="text" 
                           name="city" 
                           value="{{ old('city', $setting->city) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="City name">
                    @error('city')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-map mr-2"></i>
                        Province/State
                    </label>
                    <input type="text" 
                           name="province" 
                           value="{{ old('province', $setting->province) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="Province or state">
                    @error('province')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-flag mr-2"></i>
                        Country
                    </label>
                    <input type="text" 
                           name="country" 
                           value="{{ old('country', $setting->country) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="Country name">
                    @error('country')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-mailbox mr-2"></i>
                        ZIP/Postal Code
                    </label>
                    <input type="text" 
                           name="zip" 
                           value="{{ old('zip', $setting->zip) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="12345 or A1B 2C3">
                    @error('zip')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-usd-circle mr-2 text-[#199BCF]"></i>
                Financial Settings
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-money mr-2"></i>
                        Default Down Payment
                    </label>
                    <input type="number" 
                           name="down_payment" 
                           value="{{ old('down_payment', $setting->down_payment) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="e.g., 5000"
                           min="0">
                    @error('down_payment')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-calendar mr-2"></i>
                        Due Day of Month
                    </label>
                    <select name="due_day_of_month" 
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                        <option value="">Select day</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ old('due_day_of_month', $setting->due_day_of_month) == $i ? 'selected' : '' }}>
                                {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                            </option>
                        @endfor
                    </select>
                    @error('due_day_of_month')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="use_last_day_if_shorter" 
                               value="1" 
                               {{ old('use_last_day_if_shorter', $setting->use_last_day_if_shorter) ? 'checked' : '' }}
                               class="mr-3 rounded border-gray-300 text-[#199BCF] focus:ring-[#199BCF] focus:ring-offset-0">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fi fi-rr-calendar-check mr-2"></i>
                            Use last day of month if selected day doesn't exist (e.g., Feb 30th becomes Feb 28th/29th)
                        </span>
                    </label>
                    @error('use_last_day_if_shorter')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- School Branding -->
        <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fi fi-rr-picture mr-2 text-[#199BCF]"></i>
                School Branding
            </h2>
            
            <div class="grid grid-cols-1 gap-4">
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-picture mr-2"></i>
                        Logo Path
                    </label>
                    <input type="text" 
                           name="logo_path" 
                           value="{{ old('logo_path', $setting->logo_path) }}" 
                           class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]" 
                           placeholder="e.g., /images/logo.png or /storage/logos/school-logo.png">
                    @error('logo_path')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fi fi-rr-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fi fi-rr-info mr-1"></i>
                        Path to your school's logo file (relative to public directory)
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-row justify-end items-center gap-3 pt-4">
            <button type="button" 
                    onclick="window.location.reload()" 
                    class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                <i class="fi fi-rr-refresh mr-2"></i>
                Reset
            </button>
            <button type="submit" 
                    class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                <i class="fi fi-rr-check flex justify-center items-center"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection


