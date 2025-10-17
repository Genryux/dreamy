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

    <div class="flex flex-col gap-6">
        <!-- Main Form Container -->
        <div class="bg-white rounded-xl shadow-lg border border-[#1e1e1e]/10 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-[#199BCF] to-[#1A3165] px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fi fi-rr-envelope text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">User Invitation Form</h2>
                        <p class="text-sm text-white/80">Fill in the details to send an invitation</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.users.send-invitation') }}" method="POST" class="p-6 space-y-8">
                @csrf

                <!-- Role Selection Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fi fi-rr-user-tag text-blue-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Role Selection</h3>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fi fi-rr-user-tag mr-2"></i>
                            User Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
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
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fi fi-rr-exclamation text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fi fi-rr-user text-emerald-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fi fi-rr-square-f mr-2"></i>
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" required
                                    value="{{ old('first_name') }}"
                                    placeholder="Enter first name"
                                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fi fi-rr-exclamation text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fi fi-rr-square-l mr-2"></i>
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" required
                                    value="{{ old('last_name') }}"
                                    placeholder="Enter last name"
                                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fi fi-rr-exclamation text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fi fi-rr-square-m mr-2"></i>
                                Middle Name
                            </label>
                            <input type="text" name="middle_name" id="middle_name"
                                value="{{ old('middle_name') }}"
                                placeholder="Enter middle name"
                                class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                            @error('middle_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fi fi-rr-exclamation text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fi fi-rr-phone-call text-purple-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fi fi-rr-envelope mr-2"></i>
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required
                                    value="{{ old('email') }}"
                                    placeholder="Enter email address"
                                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fi fi-rr-exclamation text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fi fi-rr-phone-flip mr-2"></i>
                                    Contact Number
                                </label>
                                <input type="text" name="contact_number" id="contact_number"
                                    value="{{ old('contact_number') }}"
                                    placeholder="Enter contact number"
                                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                                @error('contact_number')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fi fi-rr-exclamation text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teacher-specific fields (shown when teacher role is selected) -->
                <div id="teacher-fields" class="space-y-4" style="display: none;">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fi fi-rr-chalkboard-teacher text-orange-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Teacher Information</h3>
                    </div>
                    
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fi fi-rr-graduation-cap mr-2"></i>
                                    Program/Faculty <span class="text-red-500">*</span>
                                </label>
                                <select name="program_id" id="program_id" required
                                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-white self-start rounded-lg py-3 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                                    <option value="">Select Program</option>
                                    @foreach(\App\Models\Program::all() as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }} ({{ $program->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fi fi-rr-exclamation text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        <i class="fi fi-rr-arrow-left text-sm"></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-[#199BCF] to-[#1A3165] hover:from-[#1A3165] hover:to-[#199BCF] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#199BCF] transition duration-150 hover:shadow-lg hover:shadow-[#199BCF]/25">
                        <i class="fi fi-rr-envelope text-sm"></i>
                        Send Invitation
                    </button>
                </div>
            </form>
        </div>
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
