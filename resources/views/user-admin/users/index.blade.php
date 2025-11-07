@extends('layouts.admin', ['title' => 'User Management'])

@php
    $rolesCollection = collect($roles ?? []);
@endphp

@section('modal')
    @if (Route::is('admin.users.roles'))
        <!-- Create Role Modal -->
        <x-modal modal_id="create-role-modal" modal_name="Create Role" close_btn_id="create-role-modal-close-btn"
            modal_container_id="modal-container-role">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-user-tie flex justify-center items-center'></i>
            </x-slot>

            <form id="create-role-form" method="post" action="/admin/roles" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Role Name -->
                    <div>
                        <label for="role_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user-tie mr-2"></i>
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="role_name" required
                            placeholder="e.g., content_manager, data_analyst"
                            class="flex flex-row justify-start items-center border-2 border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    </div>


                    <!-- Permission Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-shield-check mr-2"></i>
                            Assign Permissions
                        </label>
                        <div id="role-permissions-container"
                            class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-4 bg-gray-50">
                            <!-- Permissions will be loaded here -->
                            <div class="text-center text-gray-500 py-4">
                                <i class="fi fi-rr-loading text-2xl mb-2"></i>
                                <p>Loading permissions...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="modal_buttons">
                <button id="create-role-cancel-btn"
                    class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                    Cancel
                </button>
                <button type="submit" form="create-role-form" name="action" value="create-role"
                    class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                    Create Role
                </button>
            </x-slot>
        </x-modal>

        <!-- Edit Role Modal -->
        <x-modal modal_id="edit-role-modal" modal_name="Edit Role" close_btn_id="edit-role-modal-close-btn"
            modal_container_id="modal-container-edit-role">
            <x-slot name="modal_icon">
                <i class='fi fi-rr-edit flex justify-center items-center'></i>
            </x-slot>
            <form id="edit-role-form" method="post" action="/admin/roles" class="p-6">
                @csrf
                <input type="hidden" name="role_id" id="edit_role_id">
                <div class="space-y-6">
                    <!-- Role Name -->
                    <div>
                        <label for="edit_role_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user-tie mr-2"></i>
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="edit_role_name" required
                            placeholder="e.g., teacher, registrar, admin"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>


                    <!-- Permission Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-shield-check mr-2"></i>
                            Assign Permissions
                        </label>
                        <div id="edit-role-permissions-container"
                            class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-4 bg-gray-50">
                            <!-- Permissions will be loaded here -->
                            <div class="text-center text-gray-500 py-4">
                                <i class="fi fi-rr-loading text-2xl mb-2"></i>
                                <p>Loading permissions...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <x-slot name="modal_buttons">
                <button id="edit-role-cancel-btn"
                    class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                    Cancel
                </button>
                <button type="submit" form="edit-role-form" name="action" value="update-role"
                    class="bg-[#199BCF] text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-[#C8A165]/40 hover:bg-[#C8A165] transition duration-200 shadow-sm">
                    Update Role
                </button>
            </x-slot>
        </x-modal>

        <!-- Delete Role Confirmation Modal -->
        <x-modal modal_id="delete-role-modal" modal_name="Delete Role Confirmation" close_btn_id="delete-role-close-btn"
            modal_container_id="modal-container-delete-role">
            <x-slot name="modal_icon">
                <i class='fi fi-ss-exclamation flex justify-center items-center text-red-500'></i>
            </x-slot>

            <form id="delete-role-form" method="post" action="" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            <p class="py-8 px-6 space-y-2 font-regular text-[14px]">Are you sure you want to delete this role? This action
                cannot be undone and will affect all users assigned to this role.</p>

            <x-slot name="modal_buttons">
                <button id="delete-role-cancel-btn"
                    class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">
                    Cancel
                </button>
                <button type="submit" form="delete-role-form" name="action" value="delete-role"
                    class="bg-[#EA4335] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">
                    Delete Role
                </button>
            </x-slot>
        </x-modal>
    @endif

    <x-modal modal_id="create-user-modal" modal_name="Create User" close_btn_id="create-user-modal-close-btn"
        modal_container_id="modal-container-create-user">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-user-plus flex justify-center items-center'></i>
        </x-slot>

        <form id="create-user-form" class="p-6">
            @csrf
            <div class="space-y-4 overflow-y-scroll w-full">
                <!-- Personal Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="create_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="create_first_name" required
                            placeholder="Enter first name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="create_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="create_last_name" required
                            placeholder="Enter last name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label for="create_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-envelope mr-2"></i>
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="create_email" required placeholder="Enter email address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="create_contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone-call mr-2"></i>
                        Contact Number
                    </label>
                    <input type="text" name="contact_number" id="create_contact_number"
                        placeholder="Enter contact number (optional)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="create_role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-shield-check mr-2"></i>
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="create_role" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a role</option>
                        @foreach ($rolesCollection as $role)
                            <option value="{{ $role->name }}">{{ \Illuminate\Support\Str::headline($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="create_program_field" class="hidden">
                    <label for="create_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program_id" id="create_program_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a program</option>
                        <!-- Programs will be loaded dynamically -->
                    </select>
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2 mt-2">
                            <i class="fi fi-rr-book mr-2"></i>
                            Specialization
                        </label>
                        <input type="text" name="specialization" id="specialization"
                            placeholder="Enter Specialization (Optional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="create-user-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button type="submit" form="create-user-form" id="create-user-submit-btn"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                <i class="fi fi-rr-user-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                Create User
            </button>
        </x-slot>
    </x-modal>
    <x-modal modal_id="edit-user-modal" modal_name="Edit User" close_btn_id="edit-user-modal-close-btn"
        modal_container_id="modal-container-edit-user">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-edit flex justify-center items-center'></i>
        </x-slot>

        <form id="edit-user-form" class="p-6">
            @csrf
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="space-y-4">
                <!-- Personal Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="edit_first_name" required
                            placeholder="Enter first name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fi fi-rr-user mr-2"></i>
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="edit_last_name" required
                            placeholder="Enter last name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="edit_middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-user mr-2"></i>
                        Middle Name
                    </label>
                    <input type="text" name="middle_name" id="edit_middle_name"
                        placeholder="Enter middle name (optional)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-envelope mr-2"></i>
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="edit_email" required placeholder="Enter email address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-phone-call mr-2"></i>
                        Contact Number
                    </label>
                    <input type="text" name="contact_number" id="edit_contact_number"
                        placeholder="Enter contact number (optional)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-shield-check mr-2"></i>
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="edit_role" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a role</option>
                        @foreach ($rolesCollection as $role)
                            <option value="{{ $role->name }}">{{ \Illuminate\Support\Str::headline($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit_program_field" class="hidden">
                    <label for="edit_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program_id" id="edit_program_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a program</option>
                        <!-- Programs will be loaded dynamically -->
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="edit-user-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button type="submit" form="edit-user-form" id="edit-user-submit-btn"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                <i class="fi fi-rr-disk opacity-70 flex justify-center items-center text-[18px]"></i>
                Update User
            </button>
        </x-slot>
    </x-modal>
    <x-modal modal_id="delete-user-modal" modal_name="Delete User Confirmation"
        close_btn_id="delete-user-modal-close-btn" modal_container_id="modal-container-delete-user">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to delete this user? This action cannot be undone and
                        will permanently remove the user account.</p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-user-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <form id="delete-user-form" class="inline">
                @csrf
                <button type="submit" id="delete-user-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete User
                </button>
            </form>
        </x-slot>
    </x-modal>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">User Management</h1>
            <p class="text-[14px] text-gray-600 mt-1">Manage all users, invitations, and account statuses</p>
        </div>
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black">User Management</h1>
                    <p class="text-[16px] text-white/60">System Administration
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p id="total-users" class="text-[50px] font-bold">0</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Users</p>
                    </div>
                </div>

            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-check flex justify-center items-center"></i>
                        <p class="text-[14px]">Active Users</p>
                    </div>
                    <p class="font-bold text-[24px]" id="active-users">0</p>
                    <p class="text-[12px] truncate text-gray-300">Currently active accounts</p>
                </div>

                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">New This Week</p>
                    </div>
                    <p class="font-bold text-[24px]" id="new-users-this-week">0</p>
                    <p class="text-[12px] truncate text-gray-300">Users registered this week</p>
                </div>

            </div>

            <!-- Second Row - Role Distribution -->
            <div class="flex flex-row justify-center items-center w-full gap-4 mt-4">
                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-chalkboard-teacher flex justify-center items-center"></i>
                        <p class="text-[12px]">Teachers</p>
                    </div>
                    <p class="font-bold text-[20px]" id="total-teachers">0</p>
                    <p class="text-[10px] truncate text-gray-300">Teaching staff</p>
                </div>

                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-employee-man-alt flex justify-center items-center"></i>
                        <p class="text-[12px]">Students</p>
                    </div>
                    <p class="font-bold text-[20px]" id="total-students">0</p>
                    <p class="text-[10px] truncate text-gray-300">Enrolled students</p>
                </div>

                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-tie flex justify-center items-center"></i>
                        <p class="text-[12px]">Registrars</p>
                    </div>
                    <p class="font-bold text-[20px]" id="total-registrars">0</p>
                    <p class="text-[10px] truncate text-gray-300">Admin staff</p>
                </div>

                <div
                    class="analytics-card flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-crown flex justify-center items-center"></i>
                        <p class="text-[12px]">Head Teachers</p>
                    </div>
                    <p class="font-bold text-[20px]" id="total-head-teachers">0</p>
                    <p class="text-[10px] truncate text-gray-300">Department heads</p>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Tab Navigation -->
    <div
        class="px-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-300">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
                    {{ Route::is('admin.users.index') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Users
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('admin.users.roles') }}"
                    class="inline-block p-4 border-b-2 rounded-t-lg 
                    {{ Route::is('admin.users.roles') ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Roles and Permissions
                </a>
            </li>
        </ul>
    </div>

    @if (Route::is('admin.users.index'))
        <div class="flex flex-row justify-center items-start gap-4">
            <div
                class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-col my-2 justify-center items-center w-full">
                    <span class="font-semibold text-[18px]">
                        All Users
                    </span>
                    <span class="font-medium text-gray-400 text-[14px]">
                        Manage all users
                    </span>
                </div>

                <div class="flex flex-row justify-between items-center w-full h-full py-2">

                    <div class="flex flex-row justify-between w-3/4 items-center gap-4">

                        <label for="myCustomSearch"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="myCustomSearch"
                                class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                                placeholder="Search by section name, program, etc.">
                            <button id="clear-btn"
                                class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>
                        <div class="flex flex-row justify-start items-center w-full gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <select name="pageLength" id="page-length-selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option selected disabled>Entries</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                    <option value="200">200</option>
                                </select>
                                <i id="clear-gender-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>

                            <div id="role_selection_container"
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">

                                <select name="role_selection" id="role_selection"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option value="" disabled selected>Role</option>
                                    @foreach ($rolesCollection as $role)
                                        <option value="{{ $role->name }}">{{ \Illuminate\Support\Str::headline($role->name) }}</option>
                                    @endforeach
                                </select>
                                <i id="clear-role-filter-btn"
                                    class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>

                            </div>

                            <!-- Layout Toggle Button -->
                            <div id="layout_toggle_container"
                                class="flex flex-row justify-center items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <button id="layout-toggle-btn"
                                    class="flex flex-row justify-center items-center gap-2 text-[14px] font-medium text-gray-700 hover:text-[#1A3165] transition-colors duration-150">
                                    <i id="layout-toggle-icon"
                                        class="fi fi-sr-apps text-[16px] flex justify-center items-center"></i>
                                    <span id="layout-toggle-text">Cards</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <button id="create-user-modal-btn"
                        class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                        <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                        New User
                    </button>
                </div>

                <!-- Table Layout Container -->
                <div id="table-layout-container" class="w-full hidden">
                    <table id="users-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Email</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Role</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Created</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10  px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md"></tr> --}}
                        </tbody>
                    </table>
                </div>

                <!-- Card Layout Container -->
                <div id="card-layout-container" class="w-full">
                    <div id="users-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Cards will be dynamically inserted here -->
                    </div>

                    <!-- Card Layout Pagination -->
                    <div id="card-pagination" class="flex justify-center items-center mt-6 gap-2">
                        <!-- Pagination will be dynamically inserted here -->
                    </div>
                </div>
            </div>
    @endif

    @if (Route::is('admin.users.roles'))
        <div class="flex flex-row justify-center items-start gap-4">
            <!-- Main Role Table -->
            <div
                class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[60%]">
                <div class="flex flex-row justify-between items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Role Management
                    </span>
                    <div class="flex flex-row justify-center items-center gap-2">


                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="flex flex-row justify-between items-center w-full h-full py-2">
                    <div class="flex flex-row justify-between items-center w-2/3 gap-4">
                        <label for="role-search"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-2 gap-2 w-[80%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                            <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                            <input type="search" name="" id="role-search"
                                class="bg-transparent outline-none text-[14px] w-full peer" placeholder="Search roles...">
                            <button id="clear-role-search-btn"
                                class="flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                                <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                            </button>
                        </label>

                        <div class="flex flex-row justify-start items-center w-1/3 gap-2">
                            <div
                                class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                                <select name="role-page-length" id="role-page-length"
                                    class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                    <option selected disabled>Entries</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                            </div>
                        </div>
                    </div>
                    @can('create roles')
                        <button id="create-role-modal-btn"
                            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                            <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
                            Create Role
                        </button>
                    @endcan
                </div>



                <!-- Role Table -->
                <div class="w-full">
                    <table id="roles-table" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Role Name</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Permissions</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Users Count</span>
                                    <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                                </th>
                                <th class="w-1/4 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Permission Sidebar -->
            <div
                class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
                <div class="flex flex-row justify-between items-center w-full">
                    <span class="font-semibold text-[18px]">
                        Permission Assignment
                    </span>
                </div>

                <!-- Selected Role Info -->
                <div id="selected-role-info" class="w-full bg-white rounded-lg p-4 border border-[#1e1e1e]/10 hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Selected Role</h3>
                    <p id="selected-role-name" class="text-sm text-gray-600"></p>
                    <p id="selected-role-description" class="text-sm text-gray-500 mt-1"></p>
                </div>

                <!-- Permission Categories -->
                <div id="permission-categories" class="w-full space-y-4">
                    <!-- Empty state -->
                    <div class="text-center text-gray-500 py-8">
                        <i class="fi fi-rr-shield-check text-4xl mb-3 text-gray-300"></i>
                        <p class="text-sm">Select or view a role to view its permissions</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div id="permission-actions" class="w-full space-y-2 hidden">
                    <button id="save-permissions-btn"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition duration-150">
                        <i class="fi fi-rr-check mr-2"></i>
                        Save Permissions
                    </button>
                    <button id="cancel-permission-edit-btn"
                        class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition duration-150">
                        <i class="fi fi-rr-cross mr-2"></i>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection


@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropdown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import {
            initCustomDataTable
        } from "/js/initTable.js";
        import {
            initModal
        } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let usersTable;
        window.selectedRole = '';
        window.selectedStatus = '';
        window.selectedPageLength = 10;
        window.currentLayout = 'cards'; // 'table' or 'cards'
        window.currentPage = 1;
        window.totalPages = 1;
        window.usersData = [];

        document.addEventListener("DOMContentLoaded", function() {
            // Load analytics data
            loadAnalytics();

            // Check current route and initialize appropriate functionality
            const currentPath = window.location.pathname;

            if (currentPath === '/admin/users' || currentPath === '/admin/users/') {
                initializeUsersTab();
            } else if (currentPath === '/admin/users/roles') {
                initializeRolesTab();
            }
        });

        // ========================================
        // ANALYTICS FUNCTIONALITY
        // ========================================

        function loadAnalytics() {
            fetch('/admin/users/analytics')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const analytics = data.analytics;

                        // Update stat section elements
                        document.getElementById('total-users').textContent = analytics.total_users;
                        document.getElementById('active-users').textContent = analytics.active_users;
                        document.getElementById('new-users-this-week').textContent = analytics.new_users_this_week;
                        document.getElementById('pending-invitations').textContent = analytics.pending_invitations;
                        document.getElementById('expired-invitations').textContent = analytics.expired_invitations;

                        // Update role distribution
                        document.getElementById('total-teachers').textContent = analytics.total_teachers;
                        document.getElementById('total-students').textContent = analytics.total_students;
                        document.getElementById('total-registrars').textContent = analytics.total_registrars;
                        document.getElementById('total-head-teachers').textContent = analytics.total_head_teachers;

                        // Reset all card backgrounds to default first
                        const allCards = document.querySelectorAll('.analytics-card');
                        allCards.forEach(card => {
                            card.classList.remove('bg-red-500/20', 'border-red-400/30', 'bg-green-500/20',
                                'border-green-400/30');
                            card.classList.add('bg-[#E3ECFF]/30');
                        });

                        // Add visual indicators for expired invitations
                        const expiredElement = document.getElementById('expired-invitations');
                        if (analytics.expired_invitations > 0) {
                            expiredElement.parentElement.classList.remove('bg-[#E3ECFF]/30');
                            expiredElement.parentElement.classList.add('bg-red-500/20', 'border-red-400/30');
                        }

                        // Add visual indicators for new users
                        const newUsersElement = document.getElementById('new-users-this-week');
                        if (analytics.new_users_this_week > 0) {
                            newUsersElement.parentElement.classList.remove('bg-[#E3ECFF]/30');
                            newUsersElement.parentElement.classList.add('bg-green-500/20', 'border-green-400/30');
                        }

                    } else {
                        console.error('Error loading analytics');
                    }
                })
                .catch(error => {
                    console.error('Error loading analytics');
                });
        }

        // ========================================
        // USERS TAB FUNCTIONALITY
        // ========================================

        function initializeUsersTab() {
            // Initialize user modals
            initModal('create-user-modal', 'create-user-modal-btn', 'create-user-modal-close-btn',
                'create-user-cancel-btn', 'modal-container-create-user');
            initModal('edit-user-modal', 'edit-user-modal-btn', 'edit-user-modal-close-btn',
                'edit-user-cancel-btn', 'modal-container-edit-user');
            initModal('delete-user-modal', 'delete-user-modal-btn', 'delete-user-modal-close-btn',
                'delete-user-cancel-btn', 'modal-container-delete-user');

            usersTable = initCustomDataTable(
                'users-table',
                '/admin/users/data',
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'name',
                        width: '15%',
                        orderable: true
                    },
                    {
                        data: 'email',
                        width: '20%',
                        orderable: true
                    },
                    {
                        data: 'roles',
                        width: '15%',
                        orderable: true,
                        render: function(data, type, row) {
                            if (data && data !== 'No Role') {
                                const roleColors = {
                                    'super_admin': 'bg-red-100 text-red-800',
                                    'registrar': 'bg-green-100 text-green-800',
                                    'teacher': 'bg-blue-100 text-blue-800',
                                    'head_teacher': 'bg-purple-100 text-purple-800',
                                    'student': 'bg-yellow-100 text-yellow-800',
                                    'applicant': 'bg-gray-100 text-gray-800'
                                };
                                const colorClass = roleColors[data] || 'bg-gray-100 text-gray-800';
                                return `<span class="px-2 py-1 text-xs font-medium rounded-full ${colorClass}">${data.replace('_', ' ')}</span>`;
                            }
                            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">No Role</span>';
                        }
                    },
                    {
                        data: 'status',
                        width: '12%',
                        orderable: true,
                        render: function(data, type, row) {
                            return `<span class="px-2 py-1 text-xs font-medium rounded-full ${row.status_class}">${data}</span>`;
                        }
                    },
                    {
                        data: 'created_at',
                        width: '12%',
                        orderable: true
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            let actions = '';

                            if (row.status === 'Invited') {
                                actions += `
                                    <button onclick="resendInvitation(${data})" class="group relative inline-flex items-center gap-2 bg-orange-100 text-orange-500 font-semibold px-3 py-1 rounded-xl hover:bg-orange-500 hover:ring hover:ring-orange-200 hover:text-white transition duration-150 mr-2">
                                        <i class="fi fi-rr-envelope text-[16px]"></i>
                                        Resend
                                    </button>
                                    <button onclick="cancelInvitation(${data})" class="group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold px-3 py-1 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-cross text-[16px]"></i>
                                        Cancel
                                    </button>
                                `;
                            } else if (row.status === 'Registered' || row.status === 'Active') {
                                actions += `
                                    <button type="button" id="open-edit-user-modal-btn-${data}"
                                        data-user-id="${data}"
                                        class="edit-user-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 mr-2">
                                        <i class="fi fi-rr-edit text-[16px]"></i>
                                        Edit
                                    </button>
                                    <button type="button" id="open-delete-user-modal-btn-${data}"
                                        data-user-id="${data}"
                                        class="delete-user-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold px-3 py-1 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-trash text-[16px]"></i>
                                        Delete
                                    </button>
                                `;
                            } else {
                                actions += `
                                    <span class="text-sm text-gray-500">No actions</span>
                                `;
                            }

                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>
                                ${actions}
                            </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'myCustomSearch',
                [] // columnDefs parameter
            );

            // Initialize edit and delete modals dynamically
            initializeEditUserModals();
            initializeDeleteUserModals();

            // Reinitialize modals after table draw
            usersTable.on('draw', function() {
                initializeEditUserModals();
                initializeDeleteUserModals();
            });

            const customSearch1 = document.getElementById("myCustomSearch");

            // Update search functionality to work with both layouts
            customSearch1.addEventListener("input", function() {
                if (window.currentLayout === 'table') {
                    usersTable.search(this.value).draw();
                } else {
                    // For card layout, fetch with search
                    fetchUsersForCards(1);
                }
            });

            clearSearch('clear-btn', 'myCustomSearch', usersTable)

            // Layout Toggle Functionality
            const layoutToggleBtn = document.getElementById('layout-toggle-btn');
            const layoutToggleIcon = document.getElementById('layout-toggle-icon');
            const layoutToggleText = document.getElementById('layout-toggle-text');
            const tableLayoutContainer = document.getElementById('table-layout-container');
            const cardLayoutContainer = document.getElementById('card-layout-container');

            // Function to render cards
            function renderCards(data, currentPage = 1, totalPages = 1) {
                const cardsGrid = document.getElementById('users-cards-grid');
                const paginationContainer = document.getElementById('card-pagination');

                if (!data || data.length === 0) {
                    cardsGrid.innerHTML = `
                        <div class="col-span-full flex flex-col justify-center items-center py-12 text-gray-500">
                            <i class="fi fi-sr-users text-4xl mb-4"></i>
                            <p class="text-lg font-medium">No users found</p>
                            <p class="text-sm">No users match your current filters</p>
                        </div>
                    `;
                    paginationContainer.innerHTML = '';
                    return;
                }

                // Render cards
                cardsGrid.innerHTML = data.map(user => `
                    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 p-6">
                        <div class="flex flex-col space-y-4">
                            <!-- Header -->
                            <div class="flex flex-row justify-between items-start">
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-bold text-gray-800">${user.name}</h3>
                                    <p class="text-sm text-gray-500">${user.email}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs text-gray-500">#${user.index}</span>
                                    <div class="mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${user.status_class}">${user.status}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3">
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 p-1 h-[35px] w-[35px] rounded-full">
                                        <i class="fi fi-rr-shield-check flex justify-center items-center text-gray-500"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Role</span>
                                        <span class="text-sm font-medium text-gray-800">${user.roles}</span>
                                    </div>
                                </div>
                                
                                
                                <div class="flex flex-row items-center gap-3">
                                    <div class="flex justify-center items-center bg-gray-200 p-1 h-[35px] w-[35px] rounded-full">
                                        <i class="fi fi-rr-calendar flex justify-center items-center text-gray-500"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Created</span>
                                        <span class="text-sm font-medium text-gray-800">${user.created_at}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-2 border-t border-gray-100">
                                ${user.status === 'Invited' ? `
                                                                                            <div class="flex gap-2">
                                                                                                <button onclick="resendInvitation(${user.id})" 
                                                                                                    class="flex-1 flex justify-center items-center gap-2 bg-orange-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors duration-150">
                                                                                                    <i class="fi fi-rr-envelope text-sm"></i>
                                                                                                    Resend
                                                                                                </button>
                                                                                                <button onclick="cancelInvitation(${user.id})" 
                                                                                                    class="flex-1 flex justify-center items-center gap-2 bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors duration-150">
                                                                                                    <i class="fi fi-rr-cross text-sm"></i>
                                                                                                    Cancel
                                                                                                </button>
                                                                        </div>
                                    ` : (user.status === 'Registered' || user.status === 'Active') ? `
                                        <div class="flex gap-2">
                                            <button type="button" id="open-edit-user-modal-btn-${user.id}"
                                                data-user-id="${user.id}"
                                                class="edit-user-btn flex-1 flex justify-center items-center gap-2 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors duration-150">
                                                <i class="fi fi-rr-edit text-sm"></i>
                                                Edit
                                            </button>
                                            <button type="button" id="open-delete-user-modal-btn-${user.id}"
                                                data-user-id="${user.id}"
                                                class="delete-user-btn flex-1 flex justify-center items-center gap-2 bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors duration-150">
                                                <i class="fi fi-rr-trash text-sm"></i>
                                                Delete
                                            </button>
                                        </div>
                                                                                        ` : `
                                                                                            <div class="text-center text-sm text-gray-500 py-2">
                                                                                                No actions available
                                                                                            </div>
                                                                                        `}
                            </div>
                        </div>
                    </div>
                `).join('');

                // Initialize modals for card layout
                initializeEditUserModals();
                initializeDeleteUserModals();

                // Render pagination
                if (totalPages > 1) {
                    let paginationHTML = '';

                    // Previous button
                    if (currentPage > 1) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${currentPage - 1})" 
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                                <i class="fi fi-sr-angle-left"></i>
                            </button>
                        `;
                    }

                    // Page numbers
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, currentPage + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${i})" 
                                    class="px-3 py-2 text-sm font-medium ${i === currentPage ? 'bg-[#199BCF] text-white' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700'} rounded-lg transition-colors duration-150">
                                ${i}
                            </button>
                        `;
                    }

                    // Next button
                    if (currentPage < totalPages) {
                        paginationHTML += `
                            <button onclick="changeCardPage(${currentPage + 1})" 
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                                <i class="fi fi-sr-angle-right"></i>
                            </button>
                        `;
                    }

                    paginationContainer.innerHTML = paginationHTML;
                } else {
                    paginationContainer.innerHTML = '';
                }
            }

            // Function to fetch data for cards
            async function fetchUsersForCards(page = 1) {
                try {
                    const response = await fetch(
                        `/admin/users/data?start=${(page - 1) * window.selectedPageLength}&length=${window.selectedPageLength}&role_filter=${window.selectedRole}&status_filter=${window.selectedStatus}&search[value]=${document.getElementById('myCustomSearch').value}`
                    );
                    const data = await response.json();

                    window.usersData = data.data;
                    window.currentPage = page;
                    window.totalPages = Math.ceil(data.recordsTotal / window.selectedPageLength);

                    renderCards(data.data, page, window.totalPages);
                } catch (error) {
                    console.error('Error fetching users');
                }
            }

            // Function to change card page
            window.changeCardPage = function(page) {
                fetchUsersForCards(page);
            }

            // Layout toggle event listener
            layoutToggleBtn.addEventListener('click', function() {
                if (window.currentLayout === 'table') {
                    // Switch to cards
                    window.currentLayout = 'cards';
                    tableLayoutContainer.classList.add('hidden');
                    cardLayoutContainer.classList.remove('hidden');

                    layoutToggleIcon.className = 'fi fi-sr-list text-[16px]';
                    layoutToggleText.textContent = 'Table';

                    // Fetch data for cards
                    fetchUsersForCards(1);
                } else {
                    // Switch to table
                    window.currentLayout = 'table';
                    cardLayoutContainer.classList.add('hidden');
                    tableLayoutContainer.classList.remove('hidden');

                    layoutToggleIcon.className = 'fi fi-sr-apps text-[16px]';
                    layoutToggleText.textContent = 'Cards';

                    // Refresh table
                    usersTable.draw();
                }
            });

            let roleSelection = document.querySelector('#role_selection');
            let pageLengthSelection = document.querySelector('#page-length-selection');

            let clearRoleFilterBtn = document.querySelector('#clear-role-filter-btn');
            let roleContainer = document.querySelector('#role_selection_container');

            pageLengthSelection.addEventListener('change', (e) => {
                let selectedPageLength = parseInt(e.target.value, 10);
                window.selectedPageLength = selectedPageLength;
                usersTable.page.len(selectedPageLength).draw();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchUsersForCards(1);
                }
            })

            roleSelection.addEventListener('change', (e) => {
                let selectedOption = e.target.selectedOptions[0];
                let role = selectedOption.value;

                selectedRole = role;

                // Reload DataTable with new parameters
                usersTable.ajax.reload();

                // If in card layout, refresh cards
                if (window.currentLayout === 'cards') {
                    fetchUsersForCards(1);
                }

                let clearRoleFilterRem = ['text-gray-500', 'fi-rr-caret-down'];
                let clearRoleFilterAdd = ['fi-bs-cross-small', 'cursor-pointer', 'text-[#1A3165]'];
                let roleSelectionRem = ['border-[#1e1e1e]/10', 'text-gray-700'];
                let roleSelectionAdd = ['text-[#1A3165]'];
                let roleContainerRem = ['bg-gray-100'];
                let roleContainerAdd = ['bg-[#1A73E8]/15', 'bg-[#1A73E8]/15', 'border-[#1A73E8]',
                    'hover:bg-[#1A73E8]/25'
                ];

                clearRoleFilterBtn.classList.remove(...clearRoleFilterRem);
                clearRoleFilterBtn.classList.add(...clearRoleFilterAdd);
                roleSelection.classList.remove(...roleSelectionRem);
                roleSelection.classList.add(...roleSelectionAdd);
                roleContainer.classList.remove(...roleContainerRem);
                roleContainer.classList.add(...roleContainerAdd);

                handleClearRoleFilter(selectedOption)
            })

            function handleClearRoleFilter(selectedOption) {
                clearRoleFilterBtn.addEventListener('click', () => {
                    roleContainer.classList.remove('bg-[#1A73E8]/15')
                    roleContainer.classList.remove('border-blue-300')
                    roleContainer.classList.remove('hover:bg-blue-300')
                    clearRoleFilterBtn.classList.remove('fi-bs-cross-small');

                    clearRoleFilterBtn.classList.add('fi-rr-caret-down');
                    roleContainer.classList.add('bg-gray-100')
                    roleSelection.classList.remove('text-[#1A3165]')
                    roleSelection.classList.add('text-gray-700')
                    clearRoleFilterBtn.classList.remove('text-[#1A3165]')
                    clearRoleFilterBtn.classList.add('text-gray-500')

                    roleSelection.selectedIndex = 0
                    selectedRole = '';

                    // Reload DataTable with new parameters
                    usersTable.ajax.reload();

                    // If in card layout, refresh cards
                    if (window.currentLayout === 'cards') {
                        fetchUsersForCards(1);
                    }
                })
            }

            window.onload = function() {
                roleSelection.selectedIndex = 0
                pageLengthSelection.selectedIndex = 0

                // Initialize with cards layout (default)
                fetchUsersForCards(1);
            }

            dropDown('dropdown_btn', 'dropdown_selection');

            // Load programs for create and edit forms
            loadPrograms();

            // Handle role change to show/hide program field
            document.getElementById('create_role').addEventListener('change', function() {
                const programField = document.getElementById('create_program_field');
                const programSelect = document.getElementById('create_program_id');

                if (this.value === 'teacher' || this.value === 'head_teacher') {
                    programField.classList.remove('hidden');
                    programSelect.required = true;
                } else {
                    programField.classList.add('hidden');
                    programSelect.required = false;
                    programSelect.value = '';
                }
            });

            document.getElementById('edit_role').addEventListener('change', function() {
                const programField = document.getElementById('edit_program_field');
                const programSelect = document.getElementById('edit_program_id');

                if (this.value === 'teacher' || this.value === 'head_teacher') {
                    programField.classList.remove('hidden');
                    programSelect.required = true;
                } else {
                    programField.classList.add('hidden');
                    programSelect.required = false;
                    programSelect.value = '';
                }
            });

            // Create User Form Submission
            document.getElementById('create-user-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);

                // Show loader
                showLoader("Creating user...");

                fetch('/admin/users', {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();
                            document.getElementById('create_program_field').classList.add('hidden');

                            // Close modal
                            closeModal('create-user-modal', 'modal-container-create-user');

                            // Show success alert
                            showAlert('success', 'User created successfully!');

                            // Refresh table
                            if (typeof usersTable !== 'undefined') {
                                usersTable.draw();
                            }

                            // Refresh card layout if currently in card view
                            if (window.currentLayout === 'cards') {
                                fetchUsersForCards(1);
                            }

                            // Refresh analytics
                            loadAnalytics();
                        } else if (data.error) {
                            closeModal('create-user-modal', 'modal-container-create-user');
                            showAlert('error', data.error);
                        } else if (data.message) {
                            closeModal('create-user-modal', 'modal-container-create-user');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('create-user-modal', 'modal-container-create-user');
                        showAlert('error', 'Something went wrong while creating the user');
                    });
            });

            // Edit User Form Submission
            document.getElementById('edit-user-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);
                const userId = formData.get('user_id');

                if (!userId) {
                    showAlert('error', 'User ID not found');
                    return;
                }

                // Add the user ID to the form data
                formData.append('_method', 'PUT');

                // Show loader
                showLoader("Updating user...");

                fetch(`/admin/users/${userId}`, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('edit-user-modal', 'modal-container-edit-user');

                            // Show success alert
                            showAlert('success', 'User updated successfully!');

                            // Refresh table
                            if (typeof usersTable !== 'undefined') {
                                usersTable.draw();
                            } else {
                                console.error('usersTable is not defined');
                            }

                            // Refresh card layout if currently in card view
                            if (window.currentLayout === 'cards') {
                                fetchUsersForCards(1);
                            }

                            // Refresh analytics
                            loadAnalytics();
                        } else if (data.error) {
                            closeModal('edit-user-modal', 'modal-container-edit-user');
                            showAlert('error', data.error);
                        } else if (data.message) {
                            closeModal('edit-user-modal', 'modal-container-edit-user');
                            showAlert('error', data.message);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('edit-user-modal', 'modal-container-edit-user');
                        showAlert('error', 'Something went wrong while updating the user');
                    });
            });

            // Delete User Form Submission
            const deleteUserForm = document.getElementById('delete-user-form');
            if (deleteUserForm) {
                deleteUserForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const userId = formData.get('user_id');

                    showLoader();
                    fetch(`/admin/users/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success === true) {
                                showAlert('success', data.message);
                                usersTable.draw(); // Refresh the table

                                // Refresh card layout if currently in card view
                                if (window.currentLayout === 'cards') {
                                    fetchUsersForCards(1);
                                }

                                loadAnalytics(); // Refresh analytics

                                // Close modal
                                document.getElementById('delete-user-modal-close-btn').click();
                            } else {
                                showAlert('error', data.error || data.message);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while deleting the user');
                        });
                });
            }
        }

        // User management functions
        function resendInvitation(userId) {
            if (confirm('Are you sure you want to resend the invitation?')) {
                showLoader();
                fetch(`/admin/users/${userId}/resend-invitation`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            showAlert('success', 'Invitation resent successfully!');
                            usersTable.draw();
                        } else {
                            showAlert('error', data.error || 'Failed to resend invitation');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        showAlert('error', 'An error occurred while resending the invitation');
                    });
            }
        }

        function cancelInvitation(userId) {
            if (confirm('Are you sure you want to cancel this invitation? This action cannot be undone.')) {
                showLoader();
                fetch(`/admin/users/${userId}/cancel-invitation`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            showAlert('success', 'Invitation cancelled successfully!');
                            usersTable.draw();
                        } else {
                            showAlert('error', data.error || 'Failed to cancel invitation');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        showAlert('error', 'An error occurred while cancelling the invitation');
                    });
            }
        }

        // ========================================
        // USER MODAL INITIALIZATION FUNCTIONS
        // ========================================

        // Initialize edit user modals dynamically
        function initializeEditUserModals() {
            document.querySelectorAll('.edit-user-btn').forEach((button) => {
                let userId = button.getAttribute('data-user-id');
                let buttonId = `open-edit-user-modal-btn-${userId}`;

                // Initialize modal for this specific button
                initModal('edit-user-modal', buttonId, 'edit-user-modal-close-btn',
                    'edit-user-cancel-btn', 'modal-container-edit-user');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('edit-user-form');
                    let existingInputs = form.querySelectorAll('input[name="user_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Add user ID as hidden input
                    let userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.value = userId;
                    userIdInput.name = "user_id";
                    userIdInput.id = "edit_user_id";
                    form.appendChild(userIdInput);

                    // Fetch user data and populate the form
                    showLoader();
                    fetch(`/admin/users/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success && data.user) {
                                const user = data.user;

                                // Populate form fields
                                document.getElementById('edit_first_name').value = user.first_name ||
                                '';
                                document.getElementById('edit_last_name').value = user.last_name || '';
                                document.getElementById('edit_middle_name').value = user.middle_name ||
                                    '';
                                document.getElementById('edit_email').value = user.email || '';
                                document.getElementById('edit_contact_number').value = user
                                    .contact_number || '';
                                document.getElementById('edit_role').value = user.role || '';


                                // Handle program field visibility and value
                                const programField = document.getElementById('edit_program_field');
                                const programSelect = document.getElementById('edit_program_id');

                                if (user.role === 'teacher' || user.role === 'head_teacher') {
                                    programField.classList.remove('hidden');
                                    programSelect.required = true;
                                    programSelect.value = user.program_id || '';
                                } else {
                                    programField.classList.add('hidden');
                                    programSelect.required = false;
                                    programSelect.value = '';
                                }

                                // Open the modal after data is loaded
                                const modal = document.getElementById('edit-user-modal');
                                const modalContainer = document.getElementById(
                                    'modal-container-edit-user');
                                if (modal && modalContainer) {
                                    modal.classList.remove('opacity-0', 'pointer-events-none',
                                        'scale-95');
                                    modal.classList.add('opacity-100', 'scale-100');
                                    modalContainer.classList.remove('opacity-0', 'pointer-events-none');
                                    modalContainer.classList.add('opacity-100');
                                }
                            } else {
                                showAlert('error', 'Error loading user: ' + data.error);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while loading the user');
                        });
                });
            });
        }

        // Initialize delete user modals dynamically
        function initializeDeleteUserModals() {
            document.querySelectorAll('.delete-user-btn').forEach((button) => {
                let userId = button.getAttribute('data-user-id');
                let buttonId = `open-delete-user-modal-btn-${userId}`;

                // Initialize modal for this specific button
                initModal('delete-user-modal', buttonId, 'delete-user-modal-close-btn',
                    'delete-user-cancel-btn', 'modal-container-delete-user');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('delete-user-form');
                    let existingInputs = form.querySelectorAll('input[name="user_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Set the form action dynamically
                    form.action = `/admin/users/${userId}`;

                    // Add user ID as hidden input
                    let userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = 'user_id';
                    userIdInput.value = userId;
                    form.appendChild(userIdInput);

                    // Open the modal after form is set up
                    const modal = document.getElementById('delete-user-modal');
                    const modalContainer = document.getElementById('modal-container-delete-user');
                    if (modal && modalContainer) {
                        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                        modal.classList.add('opacity-100', 'scale-100');
                        modalContainer.classList.remove('opacity-0', 'pointer-events-none');
                        modalContainer.classList.add('opacity-100');
                    }
                });
            });
        }

        // Load programs for dropdowns
        function loadPrograms() {
            fetch('/admin/programs')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.programs) {
                        const createProgramSelect = document.getElementById('create_program_id');
                        const editProgramSelect = document.getElementById('edit_program_id');

                        // Clear existing options except the first one
                        createProgramSelect.innerHTML = '<option value="">Select a program</option>';
                        editProgramSelect.innerHTML = '<option value="">Select a program</option>';

                        data.programs.forEach(program => {
                            const createOption = document.createElement('option');
                            createOption.value = program.id;
                            createOption.textContent = program.name;
                            createProgramSelect.appendChild(createOption);

                            const editOption = document.createElement('option');
                            editOption.value = program.id;
                            editOption.textContent = program.name;
                            editProgramSelect.appendChild(editOption);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading programs');
                });
        }

        // Close modal function
        function closeModal(modalId, modalContainerId) {
            let modal = document.querySelector(`#${modalId}`)
            let body = document.querySelector(`#${modalContainerId}`);

            if (modal && body) {
                modal.classList.remove('opacity-100', 'scale-100');
                modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                body.classList.remove('opacity-100');
                body.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        // ========================================
        // ROLES TAB FUNCTIONALITY
        // ========================================

        let rolesTable;
        let selectedRoleId = null;
        let selectedRoleData = null;

        function initializeRolesTab() {
            // Initialize roles DataTable
            rolesTable = initCustomDataTable(
                'roles-table',
                '/admin/roles/data',
                [{
                        data: 'index',
                        width: '5%',
                        searchable: true
                    },
                    {
                        data: 'name',
                        width: '30%',
                        orderable: true,
                        render: function(data, type, row) {
                            return `<span class="font-semibold text-[#1A3165]">${data}</span>`;
                        }
                    },
                    {
                        data: 'permissions_count',
                        width: '30%',
                        orderable: true,
                        render: function(data, type, row) {
                            return `<span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">${data} permissions</span>`;
                        }
                    },
                    {
                        data: 'users_count',
                        width: '30%',
                        orderable: true,
                        render: function(data, type, row) {
                            return `<span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${data} users</span>`;
                        }
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '25%',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-2'>
                                    <button onclick="viewRole(${data})" 
                                        class="group relative inline-flex items-center gap-2 bg-green-100 text-green-500 font-semibold p-2 rounded-xl hover:bg-green-500 hover:ring hover:ring-green-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-eye text-[16px] flex justify-center items-center"></i>
                                        
                                    </button>
                                    <button type="button" id="open-edit-modal-btn-${data}"
                                        data-role-id="${data}"
                                        class="edit-role-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold p-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-edit text-[16px] flex justify-center items-center"></i>
                                        
                                    </button>
                                    <button type="button" id="open-delete-modal-btn-${data}"
                                        data-role-id="${data}"
                                        class="delete-role-btn group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold p-2 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
                                        
                                    </button>
                                </div>
                                `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'role-search',
                []
            );

            clearSearch('clear-role-search-btn', 'role-search', rolesTable)


            // Initialize modals
            const createRoleBtn = document.getElementById('create-role-modal-btn');

            if (createRoleBtn) {
                initModal('create-role-modal', 'create-role-modal-btn', 'create-role-modal-close-btn',
                    'create-role-cancel-btn', 'modal-container-role');
            }

            // Initialize edit role modal
            initModal('edit-role-modal', 'edit-role-modal-btn', 'edit-role-modal-close-btn',
                'edit-role-cancel-btn', 'modal-container-edit-role');

            // Load permissions for create role modal
            loadPermissionsForCreateRole();

            // Initialize delete role modals dynamically (following document-details pattern)
            // This will be called after the table is drawn
            initializeDeleteRoleModals();
            initializeEditRoleModals();

            // Reinitialize modals after table draw
            rolesTable.on('draw', function() {
                initializeDeleteRoleModals();
                initializeEditRoleModals();
            });

            // Handle create role form submission
            const createRoleForm = document.getElementById('create-role-form');
            if (createRoleForm) {
                createRoleForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const roleData = {
                        name: formData.get('name'),
                        permissions: Array.from(document.querySelectorAll(
                            'input[name="permissions[]"]:checked')).map(cb => cb.value)
                    };

                    showLoader();
                    fetch('/admin/roles', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(roleData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success) {
                                showAlert('success', 'Role created successfully');
                                rolesTable.draw(); // Refresh the table
                                // Close modal and reset form
                                document.getElementById('create-role-modal-close-btn').click();
                                createRoleForm.reset();
                            } else {
                                showAlert('error', data.error || 'Failed to create role');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while creating the role');
                        });
                });
            }

            // Handle edit role form submission
            const editRoleForm = document.getElementById('edit-role-form');
            if (editRoleForm) {
                editRoleForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const roleId = formData.get('role_id');
                    const roleData = {
                        name: formData.get('name'),
                        permissions: Array.from(document.querySelectorAll(
                            'input[name="role_permissions[]"]:checked')).map(cb => cb.value)
                    };

                    showLoader();
                    fetch(`/admin/roles/${roleId}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(roleData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success) {
                                showAlert('success', 'Role updated successfully');
                                rolesTable.draw(); // Refresh the table

                                // If we're currently viewing this role, refresh the view
                                if (selectedRoleId && selectedRoleId == roleId) {
                                    // Small delay to ensure database is updated
                                    setTimeout(() => {
                                        viewRole(roleId); // Refresh the view with updated permissions
                                    }, 100);
                                }

                                // Close modal
                                document.getElementById('edit-role-modal-close-btn').click();
                            } else {
                                showAlert('error', data.error || 'Failed to update role');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while updating the role');
                        });
                });
            }

            // Handle delete role form submission
            const deleteRoleForm = document.getElementById('delete-role-form');
            if (deleteRoleForm) {
                deleteRoleForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const roleId = formData.get('role_id');

                    showLoader();
                    fetch(`/admin/roles/${roleId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success) {
                                showAlert('success', 'Role deleted successfully');
                                rolesTable.draw(); // Refresh the table
                                // Close modal
                                document.getElementById('delete-role-close-btn').click();
                            } else {
                                showAlert('error', data.error || 'Failed to delete role');
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while deleting the role');
                        });
                });
            }


            // Event listeners
            setupRolesEventListeners();
        }

        function initializeDeleteRoleModals() {
            // Initialize delete role modals for each button (following document-details pattern)
            document.querySelectorAll('.delete-role-btn').forEach((button, index) => {
                let roleId = button.getAttribute('data-role-id');
                let buttonId = `open-delete-modal-btn-${roleId}`;

                // Initialize modal for this specific button
                initModal('delete-role-modal', buttonId, 'delete-role-close-btn',
                    'delete-role-cancel-btn', 'modal-container-delete-role');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('delete-role-form');
                    let existingInputs = form.querySelectorAll('input[name="role_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Set the form action dynamically
                    form.action = `/admin/roles/${roleId}`;

                    // Add role ID as hidden input
                    let roleIdInput = document.createElement('input');
                    roleIdInput.type = 'hidden';
                    roleIdInput.name = 'role_id';
                    roleIdInput.value = roleId;
                    form.appendChild(roleIdInput);

                });
            });
        }

        function initializeEditRoleModals() {
            // Initialize edit role modals for each button (following document-details pattern)
            document.querySelectorAll('.edit-role-btn').forEach((button, index) => {
                let roleId = button.getAttribute('data-role-id');
                let buttonId = `open-edit-modal-btn-${roleId}`;

                // Initialize modal for this specific button
                initModal('edit-role-modal', buttonId, 'edit-role-modal-close-btn',
                    'edit-role-cancel-btn', 'modal-container-edit-role');

                button.addEventListener('click', () => {
                    // Clear any existing hidden inputs first
                    let form = document.getElementById('edit-role-form');
                    let existingInputs = form.querySelectorAll('input[name="role_id"]');
                    existingInputs.forEach(input => input.remove());

                    // Add role ID as hidden input
                    let roleIdInput = document.createElement('input');
                    roleIdInput.type = 'hidden';
                    roleIdInput.value = roleId;
                    roleIdInput.name = "role_id";
                    roleIdInput.id = "edit_role_id";
                    form.appendChild(roleIdInput);

                    // Fetch role data and populate the form
                    showLoader();
                    fetch(`/admin/roles/${roleId}`)
                        .then(response => response.json())
                        .then(data => {
                            hideLoader();
                            if (data.success) {
                                // Populate form fields
                                document.getElementById('edit_role_name').value = data.role.name;

                                // Load permissions for this role
                                loadPermissionsForEditRole(data.role.permissions);

                            } else {
                                showAlert('error', 'Error loading role: ' + data.error);
                            }
                        })
                        .catch(error => {
                            hideLoader();
                            showAlert('error', 'An error occurred while loading the role');
                        });
                });
            });
        }

        function setupRolesEventListeners() {
            // Search functionality
            const roleSearch = document.getElementById('role-search');
            if (roleSearch) {
                roleSearch.addEventListener('input', function() {
                    rolesTable.search(this.value).draw();
                });
            }

            // Page length selection
            const rolePageLength = document.getElementById('role-page-length');
            if (rolePageLength) {
                rolePageLength.addEventListener('change', function() {
                    rolesTable.page.len(parseInt(this.value)).draw();
                });
            }

            // Save permissions button
            const savePermissionsBtn = document.getElementById('save-permissions-btn');
            if (savePermissionsBtn) {
                savePermissionsBtn.addEventListener('click', saveRolePermissions);
            }

            // Cancel permission edit button
            const cancelPermissionEditBtn = document.getElementById('cancel-permission-edit-btn');
            if (cancelPermissionEditBtn) {
                cancelPermissionEditBtn.addEventListener('click', cancelPermissionEdit);
            }
        }

        // Global functions for role management
        window.viewRole = function(roleId) {
            showLoader();
            selectedRoleId = roleId; // Track which role is currently being viewed

            // Fetch role data and permissions
            fetch(`/admin/roles/${roleId}`)
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        // Show selected role info
                        document.getElementById('selected-role-name').textContent = data.role.name;
                        document.getElementById('selected-role-description').textContent = data.role.description ||
                            'No description';
                        document.getElementById('selected-role-info').classList.remove('hidden');

                        // Display permissions in view-only mode
                        displayRolePermissionsByCategory(data.role.permissions);

                        // Hide action buttons for view mode
                        document.getElementById('permission-actions').classList.add('hidden');
                    } else {
                        showAlert('error', 'Error loading role: ' + data.error);
                    }
                })
                .catch(error => {
                    hideLoader();
                    showAlert('error', 'An error occurred while loading the role');
                });
        };

        window.selectRole = function(roleId) {
            showLoader();
            // Load role data and permissions
            fetch(`/admin/roles/${roleId}`)
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        selectedRoleId = roleId;
                        selectedRoleData = data.role;

                        // Show selected role info
                        document.getElementById('selected-role-name').textContent = data.role.name;
                        document.getElementById('selected-role-description').textContent = data.role.description ||
                            'No description';
                        document.getElementById('selected-role-info').classList.remove('hidden');

                        // Load permissions for this role
                        loadPermissionsForRole(data.role.permissions);

                        // Show action buttons
                        document.getElementById('permission-actions').classList.remove('hidden');
                    } else {
                        showAlert('error', 'Error loading role: ' + data.error);
                    }
                })
                .catch(error => {
                    hideLoader();
                    showAlert('error', 'An error occurred while loading the role');
                });
        };

        function loadPermissionsForCreateRole() {
            fetch('/admin/permissions')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPermissionsForCreateRole(data.permissions);
                    } else {
                        document.getElementById('role-permissions-container').innerHTML =
                            '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                    }
                })
                .catch(error => {
                    document.getElementById('role-permissions-container').innerHTML =
                        '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                });
        }

        function renderPermissionsForCreateRole(permissions) {
            const container = document.getElementById('role-permissions-container');

            if (permissions.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-4"><p>No permissions available.</p></div>';
                return;
            }

            // Group permissions by category
            const categoryGroups = {};
            permissions.forEach(permission => {
                const category = permission.category || 'General';
                if (!categoryGroups[category]) {
                    categoryGroups[category] = [];
                }
                categoryGroups[category].push(permission);
            });

            let html = '';
            Object.entries(categoryGroups).forEach(([categoryName, categoryPermissions]) => {
                html += `
                    <div class="bg-white rounded-lg p-4 border border-[#1e1e1e]/10 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fi fi-rr-tag mr-2 text-blue-600"></i>
                            ${categoryName}
                        </h4>
                        <div class="space-y-2">
                `;

                categoryPermissions.forEach(permission => {
                    html += `
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="${permission.id}" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">${permission.name}</span>
                        </label>
                    `;
                });
                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function loadPermissionsForEditRole(rolePermissions = []) {
            fetch('/admin/permissions')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPermissionsForEditRole(data.permissions, rolePermissions);
                    } else {
                        document.getElementById('edit-role-permissions-container').innerHTML =
                            '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                    }
                })
                .catch(error => {
                    document.getElementById('edit-role-permissions-container').innerHTML =
                        '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                });
        }

        function renderPermissionsForEditRole(permissions, rolePermissions = []) {
            const container = document.getElementById('edit-role-permissions-container');

            if (permissions.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-4"><p>No permissions available.</p></div>';
                return;
            }

            // Group permissions by category
            const categoryGroups = {};
            permissions.forEach(permission => {
                const category = permission.category || 'General';
                if (!categoryGroups[category]) {
                    categoryGroups[category] = [];
                }
                categoryGroups[category].push(permission);
            });

            let html = '';
            Object.entries(categoryGroups).forEach(([categoryName, categoryPermissions]) => {
                html += `
                    <div class="bg-white rounded-lg p-4 border border-[#1e1e1e]/10 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fi fi-rr-tag mr-2 text-blue-600"></i>
                            ${categoryName}
                        </h4>
                        <div class="space-y-2">
                `;

                categoryPermissions.forEach(permission => {
                    const isChecked = rolePermissions.some(rp => rp.id === permission.id);
                    html += `
                        <label class="flex items-center">
                            <input type="checkbox" name="role_permissions[]" value="${permission.id}" 
                                ${isChecked ? 'checked' : ''}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">${permission.name}</span>
                        </label>
                    `;
                });
                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function loadPermissionsForRole(rolePermissions = [], viewOnly = false) {
            fetch('/admin/permissions')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPermissionsForRole(data.permissions, rolePermissions, viewOnly);
                    } else {
                        document.getElementById('permission-categories').innerHTML =
                            '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                    }
                })
                .catch(error => {
                    document.getElementById('permission-categories').innerHTML =
                        '<div class="text-center text-red-500 py-4"><p>Error loading permissions</p></div>';
                });
        }

        function displayRolePermissionsByCategory(rolePermissions = []) {
            const container = document.getElementById('permission-categories');

            if (rolePermissions.length === 0) {
                container.innerHTML =
                    '<div class="text-center text-gray-500 py-4"><p>No permissions assigned to this role.</p></div>';
                return;
            }

            // Group role permissions by their actual category from the database
            const categoryGroups = {};
            rolePermissions.forEach(permission => {
                // Use the category from the permission object (from permission_categories table)
                const category = permission.category || 'General';

                if (!categoryGroups[category]) {
                    categoryGroups[category] = [];
                }
                categoryGroups[category].push(permission);
            });

            let html = '';
            Object.entries(categoryGroups).forEach(([categoryName, categoryPermissions]) => {
                html += `
                    <div class="bg-white rounded-lg p-4 border border-[#1e1e1e]/10 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fi fi-rr-tag mr-2 text-blue-600"></i>
                            ${categoryName}
                        </h4>
                        <div class="space-y-2">
                `;

                categoryPermissions.forEach(permission => {
                    html += `
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-4 h-4 mr-2">
                                <i class="fi fi-rr-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-sm text-gray-700 font-medium">${permission.name}</span>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function renderPermissionsForRole(permissions, rolePermissions = [], viewOnly = false) {
            const container = document.getElementById('permission-categories');

            if (permissions.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-4"><p>No permissions available.</p></div>';
                return;
            }

            // Group permissions by category
            const categoryGroups = {};
            permissions.forEach(permission => {
                const category = permission.category || 'General';
                if (!categoryGroups[category]) {
                    categoryGroups[category] = [];
                }
                categoryGroups[category].push(permission);
            });

            let html = '';
            Object.entries(categoryGroups).forEach(([categoryName, categoryPermissions]) => {
                html += `
                    <div class="bg-white rounded-lg p-4 border border-[#1e1e1e]/10 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fi fi-rr-tag mr-2 text-blue-600"></i>
                            ${categoryName}
                        </h4>
                        <div class="space-y-2">
                `;

                categoryPermissions.forEach(permission => {
                    const isChecked = rolePermissions.some(rp => rp.id === permission.id);

                    if (viewOnly) {
                        html += `
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-4 h-4 mr-2">
                                    ${isChecked ? 
                                        '<i class="fi fi-rr-check text-green-600 text-sm"></i>' : 
                                        '<i class="fi fi-rr-cross text-gray-400 text-sm"></i>'
                                    }
                                </div>
                                <span class="text-sm text-gray-700 ${isChecked ? 'font-medium' : 'text-gray-500'}">${permission.name}</span>
                            </div>
                        `;
                    } else {
                        html += `
                            <label class="flex items-center">
                                <input type="checkbox" name="role_permissions[]" value="${permission.id}" 
                                    ${isChecked ? 'checked' : ''}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">${permission.name}</span>
                            </label>
                        `;
                    }
                });
                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function saveRolePermissions() {
            const checkboxes = document.querySelectorAll('input[name="role_permissions[]"]:checked');
            const permissionIds = Array.from(checkboxes).map(cb => cb.value);

            showLoader();
            fetch(`/admin/roles/${selectedRoleId}/permissions`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        permissions: permissionIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        showAlert('success', 'Permissions updated successfully');
                        // Refresh the permission sidebar to show updated state
                        if (selectedRoleId) {
                            selectRole(selectedRoleId);
                        }
                    } else {
                        showAlert('error', data.error);
                    }
                })
                .catch(error => {
                    hideLoader();
                    showAlert('error', 'An error occurred while saving permissions');
                });
        }

        function cancelPermissionEdit() {
            selectedRoleId = null;
            selectedRoleData = null;

            // Hide selected role info
            document.getElementById('selected-role-info').classList.add('hidden');

            // Clear permission categories and show empty state
            document.getElementById('permission-categories').innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fi fi-rr-shield-check text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">Select or view a role to view its permissions</p>
                </div>
            `;

            // Hide action buttons
            document.getElementById('permission-actions').classList.add('hidden');
        }
    </script>
@endpush
