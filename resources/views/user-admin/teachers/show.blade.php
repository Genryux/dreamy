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
                <span class="block text-gray-900">{{ $teacher->getFullNameAttribute() }}</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">{{ $teacher->getFullNameAttribute() }}</h1>
            <p class="text-[14px] text-gray-600 mt-1">Teacher Profile Details</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full gap-3">
            <a href="{{ route('admin.teachers.edit', $teacher) }}"
                class="bg-yellow-500 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-yellow-600 transition duration-150">
                <i class="fi fi-rr-edit flex justify-center items-center"></i>
                Edit Teacher
            </a>
            <a href="{{ route('admin.teachers.index') }}"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                <i class="fi fi-rr-arrow-left mr-2"></i>Back to Teachers
            </a>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Teacher Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center mb-4">
                <i class="fi fi-sr-user text-[#1A3165] text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Full Name</span>
                    <p class="font-medium">{{ $teacher->getFullNameAttribute() }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Employee ID</span>
                    <p class="font-medium">{{ $teacher->employee_id }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Email</span>
                    <p class="font-medium">{{ $teacher->user->email_address }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Contact Number</span>
                    <p class="font-medium">{{ $teacher->contact_number ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center mb-4">
                <i class="fi fi-sr-graduation-cap text-[#1A3165] text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Professional Information</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Specialization</span>
                    <p class="font-medium">{{ $teacher->specialization ?? 'Not specified' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Years of Experience</span>
                    <p class="font-medium">{{ $teacher->years_of_experience ?? 0 }} years</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $teacher->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Account Created</span>
                    <p class="font-medium">{{ $teacher->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Teaching Assignments -->
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center mb-4">
                <i class="fi fi-sr-school text-[#1A3165] text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Teaching Assignments</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Assigned Sections</span>
                    <p class="font-medium">{{ $teacher->sections->count() }} sections</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Subject Assignments</span>
                    <p class="font-medium">{{ $teacher->sectionSubjects->count() }} subjects</p>
                </div>
                @if($teacher->sections->count() > 0)
                    <div>
                        <span class="text-sm text-gray-500">Section Names</span>
                        <div class="mt-1">
                            @foreach($teacher->sections as $section)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                    {{ $section->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Subject Assignments Table -->
    @if($teacher->sectionSubjects->count() > 0)
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="flex items-center mb-6">
                <i class="fi fi-sr-book text-[#1A3165] text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Subject Assignments</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#E3ECFF]/50">
                        <tr>
                            <th class="text-left px-4 py-3 text-sm font-medium text-gray-700">Subject</th>
                            <th class="text-left px-4 py-3 text-sm font-medium text-gray-700">Section</th>
                            <th class="text-left px-4 py-3 text-sm font-medium text-gray-700">Room</th>
                            <th class="text-left px-4 py-3 text-sm font-medium text-gray-700">Schedule</th>
                            <th class="text-left px-4 py-3 text-sm font-medium text-gray-700">Students</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($teacher->sectionSubjects as $sectionSubject)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $sectionSubject->subject->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $sectionSubject->subject->category ?? 'General' }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sectionSubject->section->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sectionSubject->room ?? 'Not assigned' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @if($sectionSubject->days_of_week && $sectionSubject->start_time)
                                        {{ implode(', ', $sectionSubject->days_of_week) }}<br>
                                        <span class="text-gray-500">{{ $sectionSubject->start_time }} - {{ $sectionSubject->end_time }}</span>
                                    @else
                                        <span class="text-gray-500">Not scheduled</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sectionSubject->students()->count() }} students</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
            <div class="text-center py-8">
                <i class="fi fi-sr-book text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Subject Assignments</h3>
                <p class="text-gray-500">This teacher hasn't been assigned to any subjects yet.</p>
            </div>
        </div>
    @endif
@endsection
