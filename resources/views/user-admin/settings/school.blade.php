@extends('layouts.admin', ['title' => 'School Settings'])

@section('content')
    <div class="p-6">
        <div class="flex flex-row justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fi fi-rr-school mr-3 text-[#199BCF]"></i>
                    School Settings
                </h1>
                <p class="text-[14px] text-gray-600 mt-1">Manage your school's basic information and contact details</p>
                @if ($setting->exists)
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
                        <strong>Down Payment:</strong>
                        {{ $setting->down_payment ? '₱' . number_format($setting->down_payment) : 'Not set' }}
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
                            @if ($setting->name)
                                <span class="text-xs text-green-600 ml-2">
                                    <i class="fi fi-rr-check mr-1"></i>Has data
                                </span>
                            @endif
                        </label>
                        <input type="text" name="name" value="{{ old('name', $setting->name) }}"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="Enter full school name" required>
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
                        <input type="text" name="short_name" value="{{ old('short_name', $setting->short_name) }}"
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
                        <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}"
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
                        <input type="email" name="email" value="{{ old('email', $setting->email) }}"
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
                        <input type="url" name="website" value="{{ old('website', $setting->website) }}"
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
                        <input type="text" name="address_line1"
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
                        <input type="text" name="address_line2"
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
                        <input type="text" name="city" value="{{ old('city', $setting->city) }}"
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
                        <input type="text" name="province" value="{{ old('province', $setting->province) }}"
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
                        <input type="text" name="country" value="{{ old('country', $setting->country) }}"
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
                        <input type="text" name="zip" value="{{ old('zip', $setting->zip) }}"
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
                        <input type="number" name="down_payment"
                            value="{{ old('down_payment', $setting->down_payment) }}"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="e.g., 5000" min="0">
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
                                <option value="{{ $i }}"
                                    {{ old('due_day_of_month', $setting->due_day_of_month) == $i ? 'selected' : '' }}>
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
                            <input type="checkbox" name="use_last_day_if_shorter" value="1"
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

            @role('super_admin')
                <!-- Activity Log -->
                <div class="bg-white rounded-lg shadow-sm border border-[#1e1e1e]/10 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fi fi-rr-clock mr-2 text-[#199BCF]"></i>
                        Recent Activity Log
                    </h2>

                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <i class="fi fi-rr-info mr-1"></i>
                            System activity and user actions
                        </p>
                        <div class="flex items-center gap-2">
                            <select id="activity-filter" class="text-xs border border-gray-300 rounded px-2 py-1">
                                <option value="">All Activities</option>
                                <option value="default">General</option>
                                <option value="user">User Actions</option>
                                <option value="payment">Payment</option>
                                <option value="enrollment">Enrollment</option>
                            </select>
                            <button id="refresh-activities" class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                                <i class="fi fi-rr-refresh mr-1"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <div id="activity-log-container" class="max-h-96 overflow-y-auto space-y-3">
                        <div class="flex items-center justify-center py-8">
                            <div class="text-center">
                                <i class="fi fi-rr-clock text-gray-400 text-2xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Loading activity log...</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole


            <!-- Action Buttons -->
            <div class="flex flex-row justify-end items-center gap-3 pt-4">
                <button type="button" onclick="window.location.reload()"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activityContainer = document.getElementById('activity-log-container');
            const activityFilter = document.getElementById('activity-filter');
            const refreshButton = document.getElementById('refresh-activities');

            // Load activities on page load
            loadActivities();

            // Filter change handler
            activityFilter.addEventListener('change', function() {
                loadActivities();
            });

            // Refresh button handler
            refreshButton.addEventListener('click', function() {
                loadActivities();
            });

            function loadActivities() {
                const filter = activityFilter.value;
                const url = new URL('/admin/activity-logs', window.location.origin);
                if (filter) {
                    url.searchParams.set('log_name', filter);
                }
                url.searchParams.set('limit', 20);

                // Show loading state
                activityContainer.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="text-center">
                    <i class="fi fi-rr-clock text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Loading activity log...</p>
                </div>
            </div>
        `;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayActivities(data.activities);
                        } else {
                            showError('Failed to load activity log');
                        }
                    })
                    .catch(error => {
                        showError('Error loading activity log');
                    });
            }

            function displayActivities(activities) {
                if (activities.length === 0) {
                    activityContainer.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <i class="fi fi-rr-clock text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500 text-sm">No activities found</p>
                    </div>
                </div>
            `;
                    return;
                }

                const activitiesHtml = activities.map(activity => {
                    const icon = getActivityIcon(activity.log_name);
                    const color = getActivityColor(activity.log_name);

                    return `
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 ${color.bg} rounded-full flex items-center justify-center">
                            <i class="${icon} ${color.text} text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">${activity.description}</p>
                            <span class="text-xs text-gray-500">${activity.created_at_human}</span>
                        </div>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="text-xs text-gray-600">
                                <i class="fi fi-rr-user mr-1"></i>
                                ${activity.causer_name}
                            </span>
                            ${activity.log_name ? `<span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">${activity.log_name}</span>` : ''}
                        </div>
                        ${activity.properties && Object.keys(activity.properties).length > 0 ? `
                                <div class="mt-2 text-xs text-gray-500">
                                    <details class="cursor-pointer">
                                        <summary class="hover:text-gray-700">View Details</summary>
                                        <pre class="mt-2 p-2 bg-white rounded border text-xs overflow-x-auto">${JSON.stringify(activity.properties, null, 2)}</pre>
                                    </details>
                                </div>
                            ` : ''}
                    </div>
                </div>
            `;
                }).join('');

                activityContainer.innerHTML = activitiesHtml;
            }

            function getActivityIcon(logName) {
                switch (logName) {
                    case 'user':
                        return 'fi fi-rr-user';
                    case 'payment':
                        return 'fi fi-rr-credit-card';
                    case 'enrollment':
                        return 'fi fi-rr-graduation-cap';
                    case 'default':
                        return 'fi fi-rr-clock';
                    default:
                        return 'fi fi-rr-clock';
                }
            }

            function getActivityColor(logName) {
                switch (logName) {
                    case 'user':
                        return {
                            bg: 'bg-green-100', text: 'text-green-600'
                        };
                    case 'payment':
                        return {
                            bg: 'bg-blue-100', text: 'text-blue-600'
                        };
                    case 'enrollment':
                        return {
                            bg: 'bg-purple-100', text: 'text-purple-600'
                        };
                    case 'default':
                        return {
                            bg: 'bg-gray-100', text: 'text-gray-600'
                        };
                    default:
                        return {
                            bg: 'bg-gray-100', text: 'text-gray-600'
                        };
                }
            }

            function showError(message) {
                activityContainer.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="text-center">
                    <i class="fi fi-rr-exclamation text-red-400 text-2xl mb-2"></i>
                    <p class="text-red-500 text-sm">${message}</p>
                </div>
            </div>
        `;
            }
        });
    </script>
@endsection
