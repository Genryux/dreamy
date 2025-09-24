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
                <span class="block text-gray-900">Teachers</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Teacher Management</h1>
            <p class="text-[14px] text-gray-600 mt-1">Manage teacher accounts and profiles</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full gap-3">
            <button onclick="togglePendingInvitations()"
                class="bg-orange-500 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-orange-600 transition duration-150">
                <i class="fi fi-rr-envelope flex justify-center items-center"></i>
                Pending Invitations
            </button>
                    <a href="{{ route('admin.invitations.invite') }}"
                class="bg-green-600 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-green-700 transition duration-150">
                <i class="fi fi-rr-envelope flex justify-center items-center"></i>
                Invite Teacher
            </a>
            <a href="{{ route('admin.teachers.create') }}"
                class="bg-[#1A3165] px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-[#0f1f3a] transition duration-150">
                <i class="fi fi-rr-plus flex justify-center items-center"></i>
                Add Teacher
            </a>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Pending Invitations Section -->
    <div id="pending-invitations-section" class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 mb-6" style="display: none;">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <i class="fi fi-sr-envelope text-orange-500 text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Pending Invitations</h3>
            </div>
            <button onclick="togglePendingInvitations()" class="text-gray-500 hover:text-gray-700">
                <i class="fi fi-rr-cross text-lg"></i>
            </button>
        </div>
        <div id="pending-invitations-content">
            <!-- Content will be loaded here -->
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
        <!-- Search and Filters -->
        <div class="flex flex-row justify-between items-center mb-6 gap-4">
            <label for="teacherSearch"
                class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 rounded-lg py-2 px-3 gap-2 flex-1 hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                <input type="search" name="" id="teacherSearch"
                    class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                    placeholder="Search teachers...">
                <button id="clear-search-btn"
                    class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                    <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                </button>
            </label>

            <div class="flex flex-row gap-2">
                <!-- Status Filter -->
                <div
                    class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                    <select name="status_filter" id="status-filter"
                        class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                        <option value="" selected>All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                </div>

                <!-- Specialization Filter -->
                <div
                    class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                    <select name="specialization_filter" id="specialization-filter"
                        class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                        <option value="" selected>All Specializations</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Social Studies">Social Studies</option>
                        <option value="Physical Education">Physical Education</option>
                        <option value="Arts">Arts</option>
                        <option value="Technology">Technology</option>
                    </select>
                    <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                </div>

                <!-- Page Length -->
                <div
                    class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                    <select name="pageLength" id="page-length-selection"
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

        <!-- Teachers Table -->
        <div class="w-full">
            <table id="teachers-table" class="w-full table-fixed">
                <thead class="text-[14px]">
                    <tr>
                        <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                        </th>
                        <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">Employee ID</span>
                            <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                        </th>
                        <th class="w-1/3 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">Full Name</span>
                            <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                        </th>
                        <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">Email</span>
                            <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                        </th>
                        <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">Specialization</span>
                            <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                        </th>
                        <th class="w-1/6 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                            <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                        </th>
                        <th class="w-1/6 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-3">
                            <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            clearSearch
        } from "/js/clearSearch.js";

        let teachersTable;
        window.selectedStatus = '';
        window.selectedSpecialization = '';
        window.selectedPageLength = 10;

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize DataTable
            teachersTable = new DataTable('#teachers-table', {
                paging: true,
                searching: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/admin/getTeachers',
                    data: function(d) {
                        d.status_filter = window.selectedStatus;
                        d.specialization_filter = window.selectedSpecialization;
                        d.pageLength = window.selectedPageLength;
                    }
                },
                order: [[0, 'asc']],
                columnDefs: [
                    { width: '5%', targets: 0, className: 'text-center' },
                    { width: '15%', targets: 1 },
                    { width: '25%', targets: 2 },
                    { width: '25%', targets: 3 },
                    { width: '15%', targets: 4 },
                    { width: '10%', targets: 5, className: 'text-center' },
                    { width: '15%', targets: 6, className: 'text-center' }
                ],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                columns: [
                    { data: 'index' },
                    { data: 'employee_id' },
                    { data: 'full_name' },
                    { data: 'email' },
                    { data: 'specialization' },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            const statusClass = data === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            return `<span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    { 
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-1'>
                                    <a href="/admin/teachers/${data}" class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                        <i class="fi fi-rr-eye text-xs"></i>
                                    </a>
                                    <a href="/admin/teachers/${data}/edit" class="px-2 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded hover:bg-yellow-200 transition duration-150">
                                        <i class="fi fi-rr-edit text-xs"></i>
                                    </a>
                                    <button onclick="toggleStatus(${data})" class="px-2 py-1 text-xs font-medium text-purple-600 bg-purple-100 rounded hover:bg-purple-200 transition duration-150">
                                        <i class="fi fi-rr-refresh text-xs"></i>
                                    </button>
                                    <button onclick="deleteTeacher(${data})" class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
                                        <i class="fi fi-rr-trash text-xs"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            clearSearch('clear-search-btn', 'teacherSearch', teachersTable);

            // Event listeners for filters
            document.getElementById('status-filter').addEventListener('change', function(e) {
                window.selectedStatus = e.target.value;
                teachersTable.draw();
            });

            document.getElementById('specialization-filter').addEventListener('change', function(e) {
                window.selectedSpecialization = e.target.value;
                teachersTable.draw();
            });

            document.getElementById('page-length-selection').addEventListener('change', function(e) {
                window.selectedPageLength = parseInt(e.target.value, 10);
                teachersTable.page.len(window.selectedPageLength).draw();
            });
        });

        // Global functions for actions
        function togglePendingInvitations() {
            const section = document.getElementById('pending-invitations-section');
            if (section.style.display === 'none') {
                loadPendingInvitations();
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        function loadPendingInvitations() {
            fetch('/admin/invitations/pending')
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('pending-invitations-content');
                    if (data.invitations.length === 0) {
                        content.innerHTML = '<p class="text-gray-500 text-center py-4">No pending invitations</p>';
                    } else {
                        let html = '<div class="space-y-3">';
                        data.invitations.forEach(invitation => {
                            const daysLeft = Math.ceil((new Date(invitation.invitation_sent_at) + 7*24*60*60*1000 - new Date()) / (1000*60*60*24));
                            html += `
                                <div class="flex items-center justify-between p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">${invitation.first_name} ${invitation.last_name}</span>
                                            <span class="ml-2 text-sm text-gray-500">(${invitation.email_address})</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span>Employee ID: ${invitation.employee_id}</span>
                                            <span class="ml-4">Sent: ${new Date(invitation.invitation_sent_at).toLocaleDateString()}</span>
                                            <span class="ml-4 text-orange-600">Expires in ${daysLeft} days</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="resendInvitation(${invitation.id})" class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition duration-150">
                                            <i class="fi fi-rr-refresh mr-1"></i>Resend
                                        </button>
                                        <button onclick="cancelInvitation(${invitation.id})" class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition duration-150">
                                            <i class="fi fi-rr-trash mr-1"></i>Cancel
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        content.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error loading pending invitations:', error);
                    document.getElementById('pending-invitations-content').innerHTML = '<p class="text-red-500 text-center py-4">Error loading invitations</p>';
                });
        }

        function resendInvitation(teacherId) {
            if (confirm('Are you sure you want to resend this invitation?')) {
                        fetch(`/admin/invitations/${teacherId}/resend`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPendingInvitations();
                            showAlert('success', data.success);
                        } else {
                            showAlert('error', data.error || 'Failed to resend invitation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', 'Something went wrong');
                    });
            }
        }

        function cancelInvitation(teacherId) {
            if (confirm('Are you sure you want to cancel this invitation? This action cannot be undone.')) {
                        fetch(`/admin/invitations/${teacherId}/cancel`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPendingInvitations();
                            teachersTable.draw();
                            showAlert('success', data.success);
                        } else {
                            showAlert('error', data.error || 'Failed to cancel invitation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', 'Something went wrong');
                    });
            }
        }

        function toggleStatus(teacherId) {
            if (confirm('Are you sure you want to toggle this teacher\'s status?')) {
                fetch(`/admin/teachers/${teacherId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            teachersTable.draw();
                            // Show success message
                            showAlert('success', data.success);
                        } else {
                            showAlert('error', data.error || 'Failed to update teacher status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', 'Something went wrong');
                    });
            }
        }

        function deleteTeacher(teacherId) {
            if (confirm('Are you sure you want to delete this teacher? This action cannot be undone.')) {
                fetch(`/admin/teachers/${teacherId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            teachersTable.draw();
                            showAlert('success', data.success);
                        } else {
                            showAlert('error', data.error || 'Failed to delete teacher');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', 'Something went wrong');
                    });
            }
        }
    </script>
@endpush
