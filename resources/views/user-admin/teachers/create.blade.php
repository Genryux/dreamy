@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="flex flex-row justify-between items-center mb-2 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="/admin" class="block transition-colors hover:text-gray-900">Admin</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.teachers.index') }}" class="block transition-colors hover:text-gray-900">Teachers</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="block text-gray-900">Create Teacher</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Create Teacher Account</h1>
            <p class="text-[14px] text-gray-600 mt-1">Add a new teacher to the system</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full gap-3">
            <a href="{{ route('admin.teachers.index') }}"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                <i class="fi fi-rr-arrow-left mr-2"></i>Back to Teachers
            </a>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
        <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col">
                        <label for="first_name" class="text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="middle_name" class="text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('middle_name') border-red-500 @enderror">
                        @error('middle_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="last_name" class="text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label for="email" class="text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="contact_number" class="text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                        <input type="tel" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('contact_number') border-red-500 @enderror">
                        @error('contact_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="flex flex-col">
                        <label for="password" class="text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" name="password" id="password" required
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with letters and numbers</p>
                    </div>

                    <div class="flex flex-col">
                        <label for="password_confirmation" class="text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label for="specialization" class="text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <select name="specialization" id="specialization"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('specialization') border-red-500 @enderror">
                            <option value="">Select Specialization</option>
                            <option value="Mathematics" {{ old('specialization') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                            <option value="Science" {{ old('specialization') == 'Science' ? 'selected' : '' }}>Science</option>
                            <option value="English" {{ old('specialization') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Filipino" {{ old('specialization') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                            <option value="Social Studies" {{ old('specialization') == 'Social Studies' ? 'selected' : '' }}>Social Studies</option>
                            <option value="Physical Education" {{ old('specialization') == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                            <option value="Arts" {{ old('specialization') == 'Arts' ? 'selected' : '' }}>Arts</option>
                            <option value="Technology" {{ old('specialization') == 'Technology' ? 'selected' : '' }}>Technology</option>
                        </select>
                        @error('specialization')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="years_of_experience" class="text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                        <input type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience') }}" min="0"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('years_of_experience') border-red-500 @enderror">
                        @error('years_of_experience')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Status Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                
                <div class="flex flex-col">
                    <label for="status" class="text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('status') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Active teachers can log in and access the system</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-row justify-end items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.teachers.index') }}"
                    class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-4 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-[#1A3165] text-[14px] px-4 py-2 rounded-md text-white font-bold hover:ring hover:ring-blue-200 hover:bg-blue-500 transition duration-150 shadow-sm">
                    Create Teacher Account
                </button>
            </div>
        </form>
    </div>
@endsection
