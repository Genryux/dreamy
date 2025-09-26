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
                <a href="{{ route('admin.users.index') }}" class="block text-gray-900 hover:text-blue-600">User Management</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="block text-gray-900">Send Invitation</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Send User Invitation</h1>
            <p class="text-[14px] text-gray-600 mt-1">Invite teachers or registrars to join the system</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-500 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-gray-600 transition duration-150">
                <i class="fi fi-rr-arrow-left flex justify-center items-center"></i>
                Back to Invitations
            </a>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
        <form action="{{ route('admin.users.send-invitation') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fi fi-rr-user-tag mr-2"></i>
                    User Role <span class="text-red-500">*</span>
                </label>
                <select name="role" id="role" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" 
                                {{ old('role') == $role->name ? 'selected' : '' }}
                                data-is-teacher="{{ in_array($role->name, ['teacher', 'head_teacher']) ? 'true' : 'false' }}">
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" required
                        value="{{ old('first_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" required
                        value="{{ old('last_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fi fi-rr-user mr-2"></i>
                    Middle Name
                </label>
                <input type="text" name="middle_name" id="middle_name"
                    value="{{ old('middle_name') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('middle_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-envelope mr-2"></i>
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required
                        value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone-call mr-2"></i>
                        Contact Number
                    </label>
                    <input type="text" name="contact_number" id="contact_number"
                        value="{{ old('contact_number') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('contact_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Teacher-specific fields (shown when teacher role is selected) -->
            <div id="teacher-fields" class="space-y-6" style="display: none;">
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fi fi-rr-chalkboard-teacher mr-2"></i>
                        Teacher Information
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-graduation-cap mr-2"></i>
                            Program/Faculty <span class="text-red-500">*</span>
                        </label>
                        <select name="program_id" id="program_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Program</option>
                            @foreach(\App\Models\Program::all() as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }} ({{ $program->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="years_of_experience" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-calendar mr-2"></i>
                            Years of Experience
                        </label>
                        <input type="number" name="years_of_experience" id="years_of_experience" min="0"
                            value="{{ old('years_of_experience') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('years_of_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    <i class="fi fi-rr-envelope mr-2"></i>
                    Send Invitation
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const teacherFields = document.getElementById('teacher-fields');

            function toggleTeacherFields() {
                const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                const isTeacher = selectedOption.getAttribute('data-is-teacher') === 'true';
                
                if (isTeacher) {
                    teacherFields.style.display = 'block';
                } else {
                    teacherFields.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleTeacherFields);
            
            // Initialize on page load
            toggleTeacherFields();
        });
    </script>
@endsection
