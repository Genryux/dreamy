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
                <span class="block text-gray-900">Head Teacher Dashboard</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Head Teacher Dashboard</h1>
            {{-- <p class="text-[14px] text-gray-600 mt-1">Welcome, {{ Auth::user()->first_name }}! Manage sections and assignments</p> --}}
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 mb-6 text-white">
        <div class="flex items-center">
            <i class="fi fi-rr-user-tie text-3xl mr-4"></i>
            <div>
                <h2 class="text-xl font-bold">Welcome to Your Head Teacher Dashboard!</h2>
                <p class="text-purple-100 mt-1">You have elevated permissions to manage sections, assign subjects, and assign students.</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fi fi-rr-users-class text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Sections</h3>
                    <p class="text-2xl font-bold text-blue-600">0</p>
                    <p class="text-sm text-gray-500">Sections created</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fi fi-rr-books text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Subject Assignments</h3>
                    <p class="text-2xl font-bold text-green-600">0</p>
                    <p class="text-sm text-gray-500">Subjects assigned</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fi fi-rr-graduation-cap text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Student Assignments</h3>
                    <p class="text-2xl font-bold text-purple-600">0</p>
                    <p class="text-sm text-gray-500">Students assigned</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fi fi-rr-chalkboard-teacher text-2xl text-orange-500"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Teacher Assignments</h3>
                    <p class="text-2xl font-bold text-orange-600">0</p>
                    <p class="text-sm text-gray-500">Teachers assigned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 mb-6">
        <div class="flex items-center mb-4">
            <i class="fi fi-sr-rocket text-purple-500 text-xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/sections" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition duration-150">
                <div class="flex items-center">
                    <i class="fi fi-rr-users-class text-blue-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Manage Sections</h4>
                        <p class="text-sm text-blue-700">Create and edit sections</p>
                    </div>
                </div>
            </a>
            
            <a href="/subjects" class="p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition duration-150">
                <div class="flex items-center">
                    <i class="fi fi-rr-books text-green-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-green-900">Assign Subjects</h4>
                        <p class="text-sm text-green-700">Assign subjects to sections</p>
                    </div>
                </div>
            </a>
            
            <a href="/students" class="p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition duration-150">
                <div class="flex items-center">
                    <i class="fi fi-rr-graduation-cap text-purple-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-purple-900">Assign Students</h4>
                        <p class="text-sm text-purple-700">Assign students to sections</p>
                    </div>
                </div>
            </a>
            
            <a href="/admin/teachers" class="p-4 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition duration-150">
                <div class="flex items-center">
                    <i class="fi fi-rr-chalkboard-teacher text-orange-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-orange-900">Assign Teachers</h4>
                        <p class="text-sm text-orange-700">Assign teachers to sections</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Head Teacher Information -->
    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 mb-6">
        <div class="flex items-center mb-4">
            <i class="fi fi-sr-user-tie text-purple-500 text-xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Your Head Teacher Profile</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Personal Information</h4>
                <div class="space-y-2 text-sm">
                    {{-- <p><span class="text-gray-500">Name:</span> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    <p><span class="text-gray-500">Email:</span> {{ Auth::user()->email }}</p>
                    @if(Auth::user()->teacher)
                        <p><span class="text-gray-500">Employee ID:</span> {{ Auth::user()->teacher->employee_id }}</p>
                        @if(Auth::user()->teacher->specialization)
                            <p><span class="text-gray-500">Specialization:</span> {{ Auth::user()->teacher->specialization }}</p>
                        @endif
                    @endif --}}
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Account Status</h4>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">Status:</span> 
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                            Head Teacher
                        </span>
                    </p>
                    <p><span class="text-gray-500">Role:</span> Head Teacher</p>
                    {{-- <p><span class="text-gray-500">Member since:</span> {{ Auth::user()->created_at->format('M d, Y') }}</p> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Coming Soon Features -->
    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
        <div class="flex items-center mb-4">
            <i class="fi fi-sr-rocket text-purple-500 text-xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Advanced Features</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">Section Analytics</h4>
                <p class="text-sm text-blue-700">View detailed reports and analytics for all sections</p>
            </div>
            
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <h4 class="font-medium text-green-900 mb-2">Schedule Management</h4>
                <p class="text-sm text-green-700">Manage class schedules and time conflicts</p>
            </div>
            
            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <h4 class="font-medium text-purple-900 mb-2">Teacher Performance</h4>
                <p class="text-sm text-purple-700">Monitor teacher performance and assignments</p>
            </div>
            
            <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                <h4 class="font-medium text-orange-900 mb-2">Student Progress</h4>
                <p class="text-sm text-orange-700">Track student progress across all sections</p>
            </div>
        </div>
    </div>
@endsection
