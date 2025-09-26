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
                <span class="block text-gray-900">User Invitations</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">User Invitations</h1>
            <p class="text-[14px] text-gray-600 mt-1">Manage user invitations and pending registrations</p>
        </div>
        <div class="flex flex-row justify-center items-center h-full gap-3">
            <a href="{{ route('admin.users.invite') }}"
                class="bg-green-600 px-4 py-2 rounded-lg text-[14px] font-semibold flex justify-center items-center gap-2 text-white hover:bg-green-700 transition duration-150">
                <i class="fi fi-rr-envelope flex justify-center items-center"></i>
                Send Invitation
            </a>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Pending Invitations Section -->
    <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <i class="fi fi-sr-envelope text-orange-500 text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Pending Invitations</h3>
            </div>
        </div>
        <div id="pending-invitations-content">
            <!-- Content will be loaded here -->
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fi fi-sr-info text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">How it works</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Send invitations to teachers or registrars by clicking "Send Invitation"</li>
                        <li>Invited users will receive an email with a registration link</li>
                        <li>Users have 7 days to complete their registration</li>
                        <li>You can resend or cancel invitations as needed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function() {
            loadPendingInvitations();
        });

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
                            const invitationSentAt = new Date(invitation.invitation_sent_at);
                            const expiresAt = new Date(invitationSentAt.getTime() + 7 * 24 * 60 * 60 * 1000);
                            const now = new Date();
                            const timeLeftMs = expiresAt.getTime() - now.getTime();
                            const daysLeft = Math.ceil(timeLeftMs / (1000 * 60 * 60 * 24));

                            const roleBadge = invitation.invitation_role === 'teacher' 
                                ? 'bg-blue-100 text-blue-800' 
                                : invitation.invitation_role === 'head_teacher'
                                ? 'bg-purple-100 text-purple-800'
                                : 'bg-green-100 text-green-800';

                            html += `
                                <div class="flex items-center justify-between p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">${invitation.first_name} ${invitation.last_name}</span>
                                            <span class="ml-2 text-sm text-gray-500">(${invitation.email})</span>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full ${roleBadge}">
                                                ${invitation.invitation_role.charAt(0).toUpperCase() + invitation.invitation_role.slice(1)}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span>Sent: ${invitationSentAt.toLocaleDateString()}</span>
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

        function resendInvitation(userId) {
            if (confirm('Are you sure you want to resend this invitation?')) {
                fetch(`/admin/invitations/${userId}/resend`, {
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
                            showAlert('success', data.message);
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

        function cancelInvitation(userId) {
            if (confirm('Are you sure you want to cancel this invitation? This action cannot be undone.')) {
                fetch(`/admin/invitations/${userId}/cancel`, {
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
                            showAlert('success', data.message);
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
    </script>
@endsection
