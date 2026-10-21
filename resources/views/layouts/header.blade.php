<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" role="navigation"
    aria-label="Main navigation">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" aria-label="Toggle sidebar"
        aria-controls="accordionSidebar" aria-expanded="false">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </button>

    <!-- Topbar Search -->
    <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search position-relative"
        onsubmit="return false;">
        <div class="input-group">
            <input type="text" id="globalSearchInput" class="form-control bg-light border-0 small"
                placeholder="Cari tugas..." aria-label="Search" aria-describedby="basic-addon2"
                style="background-color: #f1f3f9 !important; border-radius: 10px 0 0 10px;" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="globalSearchBtn"
                    style="border-radius: 0 10; border-radius: 0 10px 10px 0; box-shadow: 2px 0 10px rgba(67, 56, 202, 0.2);">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>

        <!-- Search Suggestions Dropdown -->
        <div id="searchSuggestions" class="dropdown-menu shadow animated--grow-in w-100 mt-2"
            style="display: none; position: absolute; z-index: 1000; left: 0; top: 100%;">
            <div class="dropdown-header">Hasil Pencarian</div>
            <div id="searchSuggestionsContent"></div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto align-items-center">



        <!-- Date & Time Widget (Pill Style) -->
        <li class="nav-item d-none d-lg-flex align-items-center mr-3">
            <div class="glass-pill px-3 py-1 d-flex align-items-center" style="height: 38px;">
                <i class="far fa-calendar-alt text-primary mr-2" style="font-size: 0.8rem;"></i>
                <span id="header-date" class="font-weight-bold text-gray-700 mr-2"
                    style="font-size: 0.75rem;">...</span>
                <span class="mx-1 text-gray-300">|</span>
                <i class="far fa-clock text-primary mx-2" style="font-size: 0.8rem;"></i>
                <span id="header-time" class="font-weight-bold text-gray-700"
                    style="min-width: 65px; display: inline-block; font-size: 0.75rem;">...</span>
            </div>
        </li>


        <!-- Dark Mode Toggle -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link" href="#" id="darkModeToggle" role="button" title="Toggle Dark Mode">
                <i class="fas fa-moon fa-fw"></i>
            </a>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="View notifications">
                <i class="fas fa-bell fa-fw" aria-hidden="true"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" id="notification-counter" style="display: none;">0</span>
                <!-- Notification Pulse Animation -->
                <span class="notification-pulse" id="notification-pulse" style="display: none;"></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown" style="min-width: 320px;">
                <div class="dropdown-header d-flex justify-content-between align-items-center py-2 px-3">
                    <h6 class="m-0 text-white">
                        <i class="fas fa-bell mr-2"></i>Notifikasi
                    </h6>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-link text-white p-0 mr-2" id="toggle-notification-sound"
                            title="Toggle Suara Notifikasi" data-sound="on" style="opacity: 0.7;">
                            <i class="fas fa-volume-up"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-white p-0" id="mark-all-read"
                            title="Tandai Semua Dibaca" style="opacity: 0.7;">
                            <i class="fas fa-check-double"></i>
                        </button>
                    </div>
                </div>
                <div id="notification-list" style="max-height: 350px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-white" role="status"></div>
                        <p class="small text-muted mt-2 mb-0">Memuat notifikasi...</p>
                    </div>
                </div>
                <a class="dropdown-item text-center small text-primary font-weight-bold py-2"
                    href="{{ route('tugas.index') }}">
                    <i class="fas fa-tasks mr-1"></i>Lihat Semua Tugas
                </a>
            </div>
        </li>

        <!-- Nav Item - Messages (Comment Notifications) -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="View messages">
                <i class="fas fa-envelope fa-fw" aria-hidden="true"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter" id="message-counter" style="display: none;">0</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown" style="min-width: 340px;">
                <div class="dropdown-header d-flex justify-content-between align-items-center py-2 px-3">
                    <h6 class="m-0 text-white">
                        <i class="fas fa-envelope mr-2"></i>Pesan Komentar
                    </h6>
                    <button class="btn btn-sm btn-link text-white p-0" id="mark-all-messages-read"
                        title="Tandai Semua Dibaca" style="opacity: 0.7;">
                        <i class="fas fa-check-double"></i>
                    </button>
                </div>
                <div id="message-list" style="max-height: 350px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-white" role="status"></div>
                        <p class="small text-muted mt-2 mb-0">Memuat pesan...</p>
                    </div>
                </div>
                <a class="dropdown-item text-center small text-primary font-weight-bold py-2"
                    href="{{ route('tugas.index') }}">
                    <i class="fas fa-comments mr-1"></i>Lihat Semua Komentar
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                aria-label="User menu for {{ auth()->user()->nama }}">
                <div class="text-right mr-3 d-none d-lg-block">
                    <span id="greeting-text" class="text-xs text-gray-500 d-block mb-0"
                        style="line-height: 1;">Halo...</span>
                    <span class="font-weight-bold text-gray-800 small">{{ auth()->user()->nama }}</span>
                </div>
                <img class="img-profile rounded-circle shadow-sm border border-white"
                    src="{{ auth()->user()->profile_photo_url ?? asset('sbadmin2/img/undraw_profile.svg') }}"
                    alt="{{ auth()->user()->nama }} profile picture" loading="lazy"
                    style="width: 40px; height: 40px; object-fit: cover;">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <div class="d-flex justify-content-center">
                        <span
                            class="badge {{ auth()->user()->jabatan == 'Admin' ? 'badge-success badge-pill px-5 py-1' : 'badge-danger badge-pill px-5 py-1' }}">
                            {{ auth()->user()->jabatan }}
                        </span>
                    </div>
                </a>
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="{{ route('settings.index') }}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Notification sound preference
        let notificationSound = localStorage.getItem('notificationSound') !== 'off';
        const soundToggle = document.getElementById('toggle-notification-sound');

        // Update sound toggle icon
        function updateSoundIcon() {
            const icon = soundToggle.querySelector('i');
            if (notificationSound) {
                icon.className = 'fas fa-volume-up';
                soundToggle.title = 'Suara Notifikasi: ON';
            } else {
                icon.className = 'fas fa-volume-mute text-muted';
                soundToggle.title = 'Suara Notifikasi: OFF';
            }
        }
        updateSoundIcon();

        // Function to play notification sound
        function playNotificationSound() {
            try {
                // Priority: Play MP3 sound if possible
                const audio = new Audio("{{ asset('sounds/Formula 1 Radio Notification.mp3') }}");
                audio.volume = 1.0; // Max volume for MP3

                audio.play().catch(error => {
                    console.log('MP3 playback failed, falling back to generated beep:', error);
                    // Fallback to generated sound if MP3 fails
                    playFallbackBeep();
                });
            } catch (error) {
                console.log('Could not play notification sound:', error);
                playFallbackBeep();
            }
        }

        // Fallback function for generated beep (louder)
        function playFallbackBeep() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 659.25;
                oscillator.type = 'sine';

                // Increased volume envelope (was 0.3, now 0.8)
                gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.8, audioContext.currentTime + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);

                setTimeout(() => {
                    audioContext.close();
                }, 600);
            } catch (e) {
                console.error('Fallback beep failed:', e);
            }
        }

        // Toggle notification sound
        soundToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            notificationSound = !notificationSound;
            localStorage.setItem('notificationSound', notificationSound ? 'on' : 'off');
            updateSoundIcon();

            // Play test sound when enabling
            if (notificationSound) {
                playNotificationSound();
            }
        });

        // Mark all notifications as read - now persists to server
        document.getElementById('mark-all-read').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Call API to mark notifications as read on server
            fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide counter and pulse
                        document.getElementById('notification-counter').style.display = 'none';
                        document.getElementById('notification-pulse').style.display = 'none';
                        previousCount = 0;

                        // Show toast
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Semua notifikasi ditandai dibaca',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notifications as read:', error);
                });
        });

        // Function to load notifications
        let previousCount = 0;

        function loadNotifications() {
            fetch('/api/notifications', {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const counter = document.getElementById('notification-counter');
                        const pulse = document.getElementById('notification-pulse');
                        const list = document.getElementById('notification-list');

                        // Update counter
                        if (data.count > 0) {
                            counter.innerText = data.count > 9 ? '9+' : data.count;
                            counter.style.display = 'inline-block';
                            pulse.style.display = 'block';

                            // Play sound if new notifications
                            if (data.count > previousCount && notificationSound && previousCount > 0) {
                                // Play notification sound
                                playNotificationSound();
                            }
                        } else {
                            counter.style.display = 'none';
                            pulse.style.display = 'none';
                        }

                        previousCount = data.count;

                        // Update list
                        if (data.notifications.length > 0) {
                            let html = '';
                            data.notifications.forEach((notif, index) => {
                                const animDelay = index * 0.05;
                                html += `
                                <a class="dropdown-item d-flex align-items-center py-2 px-3 notification-item" 
                                   href="${notif.url}" style="animation: fadeInUp 0.3s ease-out ${animDelay}s both;">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-${notif.color}" style="width: 40px; height: 40px;">
                                            <i class="${notif.icon} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="font-weight-bold small text-truncate-notif" title="${notif.message}">${notif.message}</span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i>${notif.time}
                                        </small>
                                    </div>
                                </a>
                            `;
                            });
                            list.innerHTML = html;
                        } else {
                            list.innerHTML = `
                                <div class="text-center py-4">
                                    <i class="fas fa-bell-slash fa-2x text-gray-300 mb-2"></i>
                                    <p class="text-muted small mb-0">Tidak ada notifikasi baru</p>
                                </div>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notification-list').innerHTML = `
                        <div class="text-center py-3 text-muted small">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Gagal memuat
                        </div>
                    `;
                });
        }

        // Load immediately
        loadNotifications();

        // =============== MESSAGE NOTIFICATIONS ===============
        function loadMessages() {
            fetch('/api/messages', {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const counter = document.getElementById('message-counter');
                        const list = document.getElementById('message-list');
                        const pulse = document.getElementById('notification-pulse');

                        // Update counter
                        if (data.count > 0) {
                            counter.innerText = data.count > 9 ? '9+' : data.count;
                            counter.style.display = 'inline-block';
                            pulse.style.display = 'block';
                        } else {
                            counter.style.display = 'none';
                            pulse.style.display = 'none';
                        }

                        // Sidebar approval badge shares the same count; no extra /api/tugas call.
                        const approvalBadge = document.getElementById('pending-approval-badge');
                        if (approvalBadge) {
                            if (data.count > 0) {
                                approvalBadge.innerText = data.count > 9 ? '9+' : data.count;
                                approvalBadge.style.display = 'inline-block';
                            } else {
                                approvalBadge.style.display = 'none';
                            }
                        }

                        // Update list
                        if (data.messages.length > 0) {
                            let html = '';
                            data.messages.forEach((msg, index) => {
                                const avatarHtml = msg.user.photo
                                    ? `<img src="${msg.user.photo}" alt="${msg.user.nama}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #e3e6f0;">`
                                    : `<div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: bold; font-size: 16px; background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white;">${msg.user.nama.charAt(0).toUpperCase()}</div>`;

                                const typeIcon = msg.type === 'reply'
                                    ? '<span class="badge badge-primary badge-sm mr-1"><i class="fas fa-reply fa-xs"></i></span>'
                                    : '<span class="badge badge-info badge-sm mr-1"><i class="fas fa-comment fa-xs"></i></span>';

                                html += `
                            <a class="message-item d-flex align-items-start py-3 px-3 border-bottom" 
                               href="#" 
                               data-task-id="${msg.task_id}"
                               onclick="openTaskWithComments(${msg.task_id}); return false;">
                                <div class="mr-3 flex-shrink-0">
                                    ${avatarHtml}
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="font-weight-bold text-dark" style="font-size: 13px;">${msg.user.nama}</span>
                                        <small class="text-muted" style="font-size: 11px;">${msg.time}</small>
                                    </div>
                                    <div class="text-gray-700 mb-1 text-truncate-message" style="font-size: 13px; line-height: 1.4;" title="${msg.message}">
                                        ${typeIcon}${msg.message}
                                    </div>
                                    <div class="text-muted text-truncate" style="font-size: 11px;" title="${msg.task_title}">
                                        <i class="fas fa-clipboard-list mr-1"></i>${msg.task_title}
                                    </div>
                                </div>
                            </a>
                            `;
                            });
                            list.innerHTML = html;
                        } else {
                            list.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-envelope-open-text fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada pesan baru</p>
                                <small class="text-muted">Komentar dari rekan kerja akan muncul di sini</small>
                            </div>
                        `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    document.getElementById('message-list').innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                        <p class="small mb-0">Gagal memuat pesan</p>
                    </div>
                `;
                });
        }

        // Function to open task detail with comments
        window.openTaskWithComments = function (taskId) {
            // Close dropdown
            $('#messagesDropdown').dropdown('hide');

            // Store task ID to open comments after modal shows
            sessionStorage.setItem('openTaskComments', taskId);

            // Navigate to tugas page with task ID in URL hash
            const currentPath = window.location.pathname;
            if (currentPath.includes('/tugas')) {
                // Already on tugas page, trigger detail button click
                const detailBtn = document.querySelector(`.btn-detail[data-id="${taskId}"]`);
                if (detailBtn) {
                    detailBtn.click();
                } else {
                    // Task not visible, reload with hash
                    window.location.href = '{{ route("tugas.index") }}#task-' + taskId;
                }
            } else {
                // Navigate to tugas page with hash
                window.location.href = '{{ route("tugas.index") }}#task-' + taskId;
            }
        };

        // Check if we need to open a task from message click (on page load)
        const pendingTaskId = sessionStorage.getItem('openTaskComments');
        if (pendingTaskId) {
            sessionStorage.removeItem('openTaskComments');
            setTimeout(() => {
                const detailBtn = document.querySelector(`.btn-detail[data-id="${pendingTaskId}"]`);
                if (detailBtn) {
                    detailBtn.click();
                }
            }, 500);
        }

        // Mark all messages as read - persists to server
        document.getElementById('mark-all-messages-read').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Call API to mark messages as read on server
            fetch('/api/messages/mark-read', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide counter
                        document.getElementById('message-counter').style.display = 'none';

                        // Show toast
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Semua pesan ditandai dibaca',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking messages as read:', error);
                });
        });

        // Load messages immediately
        loadMessages();

        // Single poller for header data, paused while the tab is hidden.
        if (window.headerPoller) clearInterval(window.headerPoller);
        window.headerPoller = setInterval(function () {
            if (document.hidden) return;
            loadNotifications();
            loadMessages();
        }, 60000);

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                loadNotifications();
                loadMessages();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateHeaderClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

            const dayName = days[now.getDay()];
            const day = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // Format Time String
            const timeString = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} WIB`;

            // Date String
            const dateString = `${dayName}, ${day} ${month} ${year}`;

            // Dynamic Greeting
            // Dynamic Greeting
            let greeting = 'Halo';
            let icon = '<i class="fas fa-smile-beam text-warning ml-1"></i>';

            if (hours >= 4 && hours < 11) {
                greeting = 'Selamat Pagi';
                icon = '<i class="fas fa-cloud-sun text-warning ml-1"></i>';
            } else if (hours >= 11 && hours < 15) {
                greeting = 'Selamat Siang';
                icon = '<i class="fas fa-sun text-warning ml-1"></i>';
            } else if (hours >= 15 && hours < 18) {
                greeting = 'Selamat Sore';
                icon = '<i class="fas fa-coffee text-warning ml-1"></i>';
            } else {
                greeting = 'Selamat Malam';
                icon = '<i class="fas fa-moon text-warning ml-1"></i>';
            }

            const dateEl = document.getElementById('header-date');
            const timeEl = document.getElementById('header-time');
            const greetingEl = document.getElementById('greeting-text');

            if (dateEl) dateEl.textContent = dateString;
            if (timeEl) timeEl.textContent = timeString;
            if (greetingEl) greetingEl.innerHTML = `${greeting} ${icon}`;
        }

        setInterval(updateHeaderClock, 1000);
        updateHeaderClock();
    });
</script>

<style>
    .text-truncate-notif {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 13px !important;
        line-height: 1.4 !important;
    }

    .text-truncate-message {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        line-height: normal;
    }

    .message-item {
        transition: background-color 0.15s ease;
        text-decoration: none !important;
    }

    .message-item:hover {
        background-color: #f8f9fc !important;
    }

    html.dark-mode .message-item:hover {
        background-color: #2d3748 !important;
    }
</style>