<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#E4EAF9]/70 relative h-screen">
    @unless (Request::is('invoice/*'))
        @include('components.skeleton')
    @endunless
    <div id="main-container" class="h-full flex relative">

        @yield('modal')

        @unless (Request::is('invoice/*'))
            <x-side-nav-bar>
                <x-slot name="logo">
                    {{ asset('images/systemicon.png') }}
                </x-slot>
                <div class="max-h-[570px] overflow-y-scroll overflow-x-hidden flex flex-col justify-center items-start">
                    <div class="flex flex-col flex-1 space-y-2 w-full">
                        <x-divider color='#f8f8f8' opacity="0.10"></x-divider>
                        @role(['super_admin', 'registrar'])
                            <x-nav-link href="/admin" :active="request()->is('admin')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Enrollment Dashboard</p>
                                </span>

                            </x-nav-link>
                        @endrole
                        @role(['super_admin', 'registrar'])
                            <x-nav-link href="/applications/pending" :active="request()->is('admin')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate"> Applications</p>
                                </span>

                            </x-nav-link>
                        @endrole
                        {{-- For head teacher --}}
                        @role('head_teacher')
                            <x-nav-link href="/head-teacher/dashboard" :active="request()->is('head-teacher/dashboard')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Home</p>
                                </span>

                            </x-nav-link>
                        @endrole
                        {{-- For teachers --}}
                        @role('teacher')
                            <x-nav-link href="/teacher/dashboard" :active="request()->is('teacher/dashboard')">

                                <span class="flex flex-row items-center space-x-4">
                                    <i class="fi fi-rs-chart-simple text-[20px] flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate">Home</p>
                                </span>

                            </x-nav-link>
                        @endrole

                        <div id="dropdown-button"
                            class="cursor-pointer overflow-hidden ease-in-out duration-150 h-[40px] transition-all rounded-md">
                            <div class="px-4 w-full h-[41px] flex flex-row items-center space-x-4 hover:bg-[#199BCF]/30">
                                <div class="w-full h-[4px] flex flex-row items-center space-x-4 text-gray-300/80">
                                    <i
                                        class="fi fi-rs-memo-circle-check text-[20px] flex justify-center items-center flex-shrink-0"></i>
                                    <p class="font-semibold text-[16px] nav-text truncate select-none">Applications</p>
                                </div>
                                <div>
                                    <i class="fi fi-rs-angle-small-down text-[16px] text-white/60"></i>
                                </div>
                            </div>

                            <div id="flex" class="flex flex-col space-y-1 mt-1">
                                <x-nav-link href="/pending-applications" :active="request()->is('pending')">

                                    <span class="">
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending
                                            Applications
                                        </p>
                                    </span>

                                </x-nav-link>
                                <x-nav-link href="/selected-applications" :active="request()->is('selected')">

                                    <span class="">
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Selected
                                            Applications
                                        </p>
                                    </span>

                                </x-nav-link>
                                <x-nav-link href="/pending-documents" :active="request()->is('documents')">

                                    <span class="">
                                        <p class="px-8 mx-2 font-semibold text-[16px] nav-text truncate">Pending Documents
                                        </p>
                                    </span>

                                </x-nav-link>


                            </div>


                        </div>
                        <span class='flex items-center mt-4'>
                            <span class="h-[0.9px] flex-1 bg-[#f8f8f8]/20"></span>
                        </span>
                    </div>

                    <div
                        class="flex flex-col space-y-2 flex-1 mt-2 max-h-[360px] w-full overflow-x-hidden overflow-y-scroll">

                        <x-nav-link href="/enrolled-students" :active="request()->is('enrolled-students')">

                            <span class="flex flex-row items-center space-x-4">
                                <i
                                    class="fi fi-rr-graduation-cap flex justify-center items-center text-[20px] flex-shrink-0"></i>

                                <p class="font-semibold text-[16px] nav-text truncate">Enrolled Students</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin" :active="request()->is('documents')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-document text-[20px] flex-shrink-0"></i>

                                <p class="font-semibold text-[16px] nav-text truncate">Documents</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/programs" :active="request()->is('programs')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-lesson text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Programs</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/sections" :active="request()->is('sections')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-users-class text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Sections</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin/users" :active="request()->is('admin/users*')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-envelope text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">User Invitations</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/subjects" :active="request()->is('subjects')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-books text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Subjects</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/school-fees" :active="request()->is('school-fees')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-coins text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">School Fees</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/homepage" :active="request()->is('homepage')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-books text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">Site Management</p>
                            </span>

                        </x-nav-link>
                        <x-nav-link href="/admin/news" :active="request()->is('admin/news')">

                            <span class="flex flex-row items-center space-x-4">
                                <i class="fi fi-rr-newspaper text-[20px] flex-shrink-0"></i>
                                <p class="font-semibold text-[16px] nav-text truncate">News</p>
                            </span>

                        </x-nav-link>
                    </div>
                </div>


            </x-side-nav-bar>
        @endunless



        <!-- Main content area -->
        <div id="content" class="relative flex-1 flex flex-col transition-all duration-300 w-full">
            <!-- Top Navigation Bar -->
            @unless (Request::is('invoice/*'))
                <header id="top-nav-bar"
                    class="z-10 bg-[#f8f8f8] h-[60px] px-[10px] flex justify-between items-center gap-2 sticky top-0 shadow-sm border-b border-[#1e1e1e]/15">
                    <!-- profile icon, notifications, etc. -->
                    <button id="sidebar-toggle-button"
                        class="flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                        <i class="fi fi-rs-sidebar-flip text-[20px]"></i>
                    </button>
                    <button id="mobile-menu-button"
                        class="md:hidden flex flex-row py-2 px-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                        <i class="fi fi-rs-menu-burger text-[20px]"></i>
                    </button>

                    <span class="flex flex-row space-x-4">
                        {{-- Notification Bell --}}
                        <div class="relative">
                            <button id="notification-bell"
                                class="relative p-2 hover:bg-[#e0e0e0] rounded-md transition-all duration-150">
                                <i class="fi fi-rs-bell text-[20px]"></i>
                                <span id="notification-badge"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                            </button>

                            {{-- Notification Dropdown --}}
                            <div id="notification-dropdown"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                        <button id="mark-all-read"
                                            class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors hidden">
                                            Mark all read
                                        </button>
                                    </div>
                                </div>
                                <div id="notification-list" class="max-h-64 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto">
                                        </div>
                                        <p class="mt-2">Loading notifications...</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 bg-gray-50">
                                    <a href="#"
                                        class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <i class="fi fi-rs-user text-[20px]"></i>
                    </span>
                </header>
            @endunless

            <!-- Main Content -->
            <main id="main-content"
                class="flex-1 py-4 px-6 relative overflow-auto h-full flex flex-col text-[#0f111c] relative gap-y-4">

                @yield('breadcrumbs')
                @yield('dashboard-acad-term')
                @yield('header')
                @yield('stat')

                {{-- <section class="bg-[#f8f8f8] flex flex-col rounded-xl border shadow-sm border-[#1e1e1e]/15"> --}}

                @yield('content')


                {{-- </section> --}}
                @yield('ongoing-interviews')
                @yield('docs_submission_progress')

            </main>
        </div>

    </div>

    <x-loader />

    <script type="module">
        document.body.style.overflow = 'hidden';



        window.addEventListener('load', function() {

            const skeleton = document.getElementById('skeleton');
            if (!skeleton) return;

            skeleton.style.transition = 'opacity 0.5s';
            skeleton.style.opacity = 0;

            setTimeout(() => {
                skeleton.remove();
            }, 200);
        });

        // Simple Laravel Standard Notification System - v2.0
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notification-bell');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationBadge = document.getElementById('notification-badge');
            const notificationList = document.getElementById('notification-list');
            const markAllReadBtn = document.getElementById('mark-all-read');

            console.log('Simple Laravel notification system loaded - v2.0');
            let isDropdownOpen = false;

            // Toggle dropdown
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                notificationDropdown.classList.toggle('hidden');

                if (isDropdownOpen) {
                    loadNotifications();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                    isDropdownOpen = false;
                }
            });

            // Mark all as read functionality
            markAllReadBtn.addEventListener('click', function() {
                markAllAsRead();
            });

            // Simple function to load notifications
            async function loadNotifications() {
                try {
                    const response = await fetch('/notifications');
                    const data = await response.json();

                    if (data.notifications) {
                        renderNotifications(data.notifications);
                        updateBadge(data.notifications);
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML =
                        '<div class="p-4 text-center text-gray-500">Error loading notifications</div>';
                }
            }


            // Simple function to render notifications
            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationList.innerHTML =
                        '<div class="p-4 text-center text-gray-500">No notifications yet</div>';
                    markAllReadBtn.classList.add('hidden');
                    return;
                }

                markAllReadBtn.classList.remove('hidden');

                notificationList.innerHTML = notifications.map(notification => {
                    const isUnread = !notification.read_at;
                    const timeAgo = getTimeAgo(notification.created_at);

                    return `
                        <div class="p-4 border-b border-gray-200 hover:bg-blue-50 cursor-pointer transition-colors" 
                             onclick="handleNotificationClick('${notification.id}', '${notification.data?.url || ''}')">
                            <div class="flex items-start space-x-3">
                                <div class="w-[12%] flex-shrink-0 mt-1 flex justify-center items-center bg-blue-100 p-2 rounded-full">
                                    <i class="fi fi-ss-bell text-blue-500 text-sm"></i>
                                </div>
                                <div class="w-[88%] min-w-0">
                                    <p class="text-sm font-medium text-gray-900 ${isUnread ? 'font-semibold' : ''}">
                                        ${notification.data?.title || 'Notification'}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        ${notification.data?.message || 'No message'}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        ${timeAgo}
                                    </p>
                                </div>
                                ${isUnread ? '<div class="flex-shrink-0 mt-2"><div class="w-2 h-2 bg-blue-500 rounded-full"></div></div>' : ''}
                            </div>
                        </div>
                    `;
                }).join('');
            }

            // Simple function to update badge
            function updateBadge(notifications) {
                const unreadCount = notifications.filter(n => !n.read_at).length;

                if (unreadCount > 0) {
                    notificationBadge.textContent = unreadCount;
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            }

            // Simple function to get time ago
            function getTimeAgo(dateString) {
                const now = new Date();
                const date = new Date(dateString);
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
                return `${Math.floor(diffInSeconds / 86400)}d ago`;
            }

            // Simple function to handle notification click (mark as read + navigate)
            async function handleNotificationClick(notificationId, url) {
                try {
                    // Mark as read first
                    await fetch(`/notifications/${notificationId}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });

                    // Navigate to URL if provided
                    if (url && url.trim() !== '') {
                        window.location.href = url;
                    } else {
                        // If no URL, just reload notifications
                        loadNotifications();
                    }
                } catch (error) {
                    console.error('Error handling notification click:', error);
                }
            }

            // Simple function to mark all as read
            async function markAllAsRead() {
                try {
                    await fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });
                    loadNotifications(); // Reload to update UI
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            }

            // Simple real-time listener
            const userRoles = window.Laravel?.user?.roles?.map(role => role.name || role) || [];

            // Listen to admins channel (registrar, super_admin)
            if (userRoles.some(role => ['registrar', 'super_admin'].includes(role))) {
                console.log('Setting up admin notification listener');
                console.log('Connecting to admins channel...');

                window.Echo.channel('admins')
                    .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) => {
                        console.log('Admin notification received:', e);
                        loadNotifications();

                        if (Notification.permission === 'granted') {
                            new Notification(e.title, {
                                body: e.message,
                                icon: '/favicon.ico'
                            });
                        }
                    })
                    .subscribed(() => {
                        console.log('Successfully subscribed to admins channel');
                    })
                    .error((error) => {
                        console.error('Admins channel error:', error);
                    });
            }

            // Listen to teachers channel (head_teacher, teacher)
            if (userRoles.some(role => ['head_teacher', 'teacher'].includes(role))) {
                console.log('Setting up teacher notification listener');
                console.log('Connecting to teachers channel...');

                window.Echo.channel('teachers')
                    .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) => {
                        console.log('Teacher notification received:', e);
                        loadNotifications();

                        if (Notification.permission === 'granted') {
                            new Notification(e.title, {
                                body: e.message,
                                icon: '/favicon.ico'
                            });
                        }
                    })
                    .subscribed(() => {
                        console.log('Successfully subscribed to teachers channel');
                    })
                    .error((error) => {
                        console.error('Teachers channel error:', error);
                    });
            }

            if (!userRoles.some(role => ['registrar', 'super_admin', 'head_teacher', 'teacher'].includes(role))) {
                console.log('User does not have admin or teacher role, skipping Echo setup');
            }

            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Initial load
            loadNotifications();

            // Make function globally accessible for HTML onclick
            window.handleNotificationClick = handleNotificationClick;

            // Debug: Check Echo connection
            console.log('Echo instance:', window.Echo);
            console.log('Laravel user:', window.Laravel?.user);
            console.log('User roles:', window.Laravel?.user?.roles);
        });
    </script>

    @stack('scripts')
</body>

</html>
