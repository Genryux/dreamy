<!DOCTYPE html>
<html lang="en">
<x-head></x-head>

<body class="bg-[#1A3165]">

    <div id="main-container" class="relative min-h-screen flex flex-col md:flex-col pb-2">

        <header id="top-nav-bar" class=" flex justify-center items-center gap-2 p-4">
            <div>
                <a href="/admission" class="flex items-center space-x-2">
                    <img src="{{ asset('images/admportal.png') }}" alt="Logo" class="h-[80px]">
                </a>
            </div>

        </header>
        <span class="absolute top-5 right-10 p-2 flex flex-row space-x-4">
            {{-- Notification Bell --}}
            <div class="relative">
                <button id="notification-bell"
                    class="relative flex justify-center items-center p-2 bg-white/30 w-[45px] h-[45px] rounded-full transition-all duration-150 hover:bg-white/50">
                    <i class="fi fi-rs-bell flex justify-center items-center text-[20px] text-white"></i>
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

            <div class="flex justify-center items-center relative">
                <button id="user-button"
                    class="relative flex justify-center items-center p-2 bg-white/30 w-[45px] h-[45px] rounded-full transition-all duration-150 hover:bg-white/50">
                    <i class="fi fi-rr-user mt-1 text-[20px] text-white"></i>
                </button>

                {{-- User Dropdown --}}
                <div id="user-dropdown"
                    class="absolute right-0 top-12 mt-1 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                    <div class="py-2">
                        {{-- User Profile --}}
                        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <i class="fi fi-rs-user flex justify-center items-center text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $applicant->first_name ?? 'User' }} {{ $applicant->last_name ?? 'Name' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">View Profile</p>
                            </div>
                        </a>

                        {{-- Settings --}}
                        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <i class="fi fi-rs-settings flex justify-center items-center text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Settings</p>
                            </div>
                        </a>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center px-4 py-3 hover:bg-red-50 hover:text-red-700 transition-colors text-left">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <i
                                        class="fi fi-rr-power flex justify-center items-center text-gray-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Log Out</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </span>
        <main id="content" class="p-[10px] overflow-auto h-full flex flex-col justify-center items-center relative">

            @yield('alert')


            @if ($applicant->application_status == null)
                <section
                    class="bg-[#f8f8f8] flex flex-col rounded-xl border border-[#1e1e1e]/20 md:w-[70%] justify-center ">
                    @yield('content')
                    <div class="self-center w-[80%] md:w-[60%] opacity-30">
                        <x-divider></x-divider>
                    </div>
                    @yield('summary')

                </section>
            @elseif ($applicant->application_status == 'Pending')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('pending')
                </div>
            @elseif ($applicant->application_status == 'Accepted')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('accepted')
                </div>
            @elseif ($applicant->application_status == 'Rejected')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('Rejected')
                </div>
            @elseif ($applicant->application_status == 'Pending-Documents')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('pending-documents')
                </div>
            @elseif ($applicant->application_status == 'Completed-Failed')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('completed-failed')
                </div>
            @elseif ($applicant->application_status == 'Officially Enrolled')
                <div class="flex flex-col justify-center items-center gap-2 md:w-[70%]">
                    @yield('status')
                    @yield('officially-enrolled')
                </div>
            @endif






        </main>

        @stack('scripts')

    </div>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notification-bell');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationBadge = document.getElementById('notification-badge');
            const notificationList = document.getElementById('notification-list');
            const markAllReadBtn = document.getElementById('mark-all-read');

            // User dropdown functionality
            const userButton = document.getElementById('user-button');
            const userDropdown = document.getElementById('user-dropdown');
            let isUserDropdownOpen = false;

            console.log('Simple Laravel notification system loaded - v2.0');
            let isDropdownOpen = false;

            if (notificationBell) {

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

                // Simple real-time listener for applicants
                const userRoles = window.Laravel?.user?.roles?.map(role => role.name || role) || [];
                const userRole = window.Laravel?.user?.role ||
                    'applicant'; // Default to applicant for admission portal

                // Listen to applicants channel
                if (userRole === 'applicant' || userRoles.some(role => ['applicant'].includes(role))) {
                    console.log('Setting up applicant notification listener');
                    console.log('Connecting to applicants channel...');

                    window.Echo.channel('applicants')
                        .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) => {
                            console.log('Applicant notification received:', e);
                            loadNotifications();

                            if (Notification.permission === 'granted') {
                                new Notification(e.title, {
                                    body: e.message,
                                    icon: '/favicon.ico'
                                });
                            }
                        })
                        .subscribed(() => {
                            console.log('Successfully subscribed to applicants channel');
                        })
                        .error((error) => {
                            console.error('Applicants channel error:', error);
                        });
                } else {
                    console.log('User does not have applicant role, skipping Echo setup');
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
            }

            // User dropdown functionality
            if (userButton && userDropdown) {
                // Toggle user dropdown
                userButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isUserDropdownOpen = !isUserDropdownOpen;
                    userDropdown.classList.toggle('hidden');

                    // Close notification dropdown if open
                    if (isDropdownOpen) {
                        notificationDropdown.classList.add('hidden');
                        isDropdownOpen = false;
                    }
                });

                // Close user dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        isUserDropdownOpen = false;
                    }
                });

                // Handle dropdown item clicks
                const dropdownItems = userDropdown.querySelectorAll('a');
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        const text = this.querySelector('p').textContent.trim();

                        if (text === 'Log Out') {
                            // Let the form handle logout - don't prevent default
                            return;
                        }
                        
                        // Prevent default for other items
                        e.preventDefault();
                        
                        if (text.includes('Settings')) {
                            // Handle settings - you can customize this
                            console.log('Settings clicked');
                            // window.location.href = '/settings';
                        } else if (text.includes('View Profile')) {
                            // Handle profile view - you can customize this
                            console.log('Profile clicked');
                            // window.location.href = '/profile';
                        }

                        // Close dropdown after action
                        userDropdown.classList.add('hidden');
                        isUserDropdownOpen = false;
                    });
                });
            }

            function confirmLogout() {
                return confirm(
                    'Are you sure you want to log out? You will need to sign in again to access your account.');
            }
        });
    </script>
</body>

</html>
