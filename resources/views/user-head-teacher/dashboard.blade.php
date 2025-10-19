@extends('layouts.admin')

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Head Teacher Dashboard</h1>
            <p class="text-[14px] text-gray-900/60">Manage sections, students, and faculty assignments.
            </p>
        </div>
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[36px] font-black" id="section_name">Head Teacher Overview</h1>
                    <p class="text-[16px] text-white/60">Academic management and faculty oversight for
                        {{ $academicTermData['year'] ?? date('Y') }} - {{ $academicTermData['semester'] ?? 'Current Term' }}
                    </p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <div class="relative flex flex-row justify-center items-center gap-2">
                        <p id="studentCount" class="text-[48px] font-bold ">{{ $totalStudents }}</p>
                    </div>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Students</p>
                    </div>
                </div>

            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-users-class flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Sections</p>
                    </div>
                    <p id="totalSectionsDisplay" class="font-bold text-[24px]">{{ $totalSections }}</p>
                    <p class="opacity-70 text-[14px]">Active sections</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-chalkboard-teacher flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Active Teachers</p>
                    </div>
                    <p id="totalTeachersDisplay" class="font-bold text-[24px]">{{ $activeTeachers }}</p>
                    <p class="opacity-70 text-[14px]">Faculty members</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                        <p class="text-[14px]">Enrolled Students</p>
                    </div>
                    <p id="enrolledStudentsDisplay" class="font-bold text-[24px]">{{ $totalStudents }}</p>
                    <p class="opacity-70 text-[14px]">Current enrollment</p>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4 mt-2">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                        <p class="text-[14px]">Enrollment Status</p>
                    </div>
                    <p id="enrollmentStatusDisplay" class="font-bold text-[24px]">
                        @if ($enrollmentStatus === 'Ongoing')
                            <span class="text-green-300">Ongoing</span>
                        @elseif($enrollmentStatus === 'Paused')
                            <span class="text-yellow-300">Paused</span>
                        @else
                            <span class="text-red-300">Closed</span>
                        @endif
                    </p>
                    <p class="opacity-70 text-[14px]">{{ $enrollmentMessage }}</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Academic Year</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $academicTermData['year'] ?? 'No active term' }}</p>
                    <p class="opacity-70 text-[14px]">Current Term</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-6 py-4 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Academic Semester</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $academicTermData['semester'] ?? 'No active term' }}</p>
                    <p class="opacity-70 text-[14px]">Current Semester</p>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-col justify-center items-start gap-4">

        <div class="flex flex-row justify-center items-start w-full h-auto gap-4">
            <div class="flex flex-col w-[70%] bg-white rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
                <span class="text-[16px] text-gray-800 font-bold">Enrolled Students</span>

                <div class="flex flex-col items-center flex-grow space-y-2 ">
                    <div class="w-full">
                        <table id="studentsTable" class="w-full table-fixed">
                            <thead class="text-[14px]">
                                <tr>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">#</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">LRN</span>
                                    </th>
                                    <th class="w-1/4 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Full Name</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Program</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Grade Level</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Section</span>
                                    </th>
                                    <th class="w-1/6 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-[30%] h-full space-y-4">
                <div
                    class="flex-1 flex flex-col bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-sm p-6 gap-4">
                    <span class="font-bold text-white text-[16px]">Quick Actions</span>
                    <div class="flex flex-col justify-center items-center gap-2 text-center">
                        <a href="/sections"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Manage Sections
                        </a>
                        <a href="/tracks"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Manage Programs
                        </a>
                        <a href="/subjects"
                            class="w-full bg-[#33ACD6] py-2.5 text-[16px] px-4 rounded-xl font-medium text-white hover:bg-[#199BCF] hover:shadow-md transition duration-150">
                            Manage Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty/Teachers Table -->
        <div class="flex flex-row w-full h-auto gap-4">
            <div class="flex flex-col w-full h-auto bg-white rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
                <span class="text-[16px] text-gray-800 font-bold">Faculty & Teachers</span>

                <div class="flex flex-col items-center flex-grow space-y-2">
                    <div class="w-full">
                        <table id="teachersTable" class="w-full table-fixed">
                            <thead class="text-[14px]">
                                <tr>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">#</span>
                                    </th>
                                    <th class="w-1/4 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Full Name</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Program</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Specialization</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Advising</span>
                                    </th>
                                    <th class="w-1/6 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                        <span class="mr-2 font-medium opacity-70">Email</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropDown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import {
            initModal
        } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";



        document.addEventListener("DOMContentLoaded", function() {

            let schoolfeeTable = initCustomDataTable(
                'studentsTable',
                `/users`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'lrn',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'full_name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'grade_level',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'section',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <a href="/student/${data}"                                   
                                    class="edit-school-fee-btn group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold p-2 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-eye text-[16px] flex justify-center items-center"></i>
                                </a>
                            </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'desc']
                ],
                'school-fee-search',
                [{
                        width: '3%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '18%',
                        targets: 2
                    },
                    {
                        width: '12%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '15%',
                        targets: 5
                    },
                    {
                        width: '10%',
                        targets: 6,
                        className: 'text-center'
                    }
                ]
            );

            clearSearch('clear-btn', 'school-fee-search', schoolfeeTable)

            let teachersTable = initCustomDataTable(
                'teachersTable',
                `/getTeachers`,
                [{
                        data: 'index'
                    },
                    {
                        data: 'full_name',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'program',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'specialization',
                        render: DataTable.render.text()
                    },
                    {
                        data: 'advising_count',
                        render: function(data, type, row) {
                            return `<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${data} section${data !== 1 ? 's' : ''}</span>`;
                        }
                    },
                    {
                        data: 'email',
                        render: DataTable.render.text()
                    }
                ],
                [
                    [0, 'desc']
                ],
                'teachers-search',
                [{
                        width: '3%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '18%',
                        targets: 1
                    },
                    {
                        width: '12%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '22%',
                        targets: 5
                    }
                ]
            );
        })
    </script>
@endpush
