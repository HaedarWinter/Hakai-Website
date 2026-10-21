<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Manajemen Tugas') | Hakai</title>
    <meta name="description"
        content="@yield('meta_description', 'Sistem Manajemen Tugas Karyawan - Kelola tugas, monitor progress, dan tingkatkan produktivitas tim Anda dengan Hakai.')">
    <meta name="keywords" content="manajemen tugas, task management, produktivitas, karyawan, hakai">
    <meta name="author" content="Hakai">
    <meta name="robots" content="index, follow">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4e73df">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Hakai">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">

    <!-- Security & API -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session('sanctum_token') }}">

    <!-- Preconnect to External Domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Optimized Font Loading with display=swap -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Critical CSS - Load immediately -->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">



    <!-- Custom CSS for Fixed Sidebar -->
    <link href="{{ asset('css/custom-sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
    <link href="{{ asset('css/enhancements.css') }}" rel="stylesheet">

    <!-- Non-Critical CSS - Load with low priority -->
    <!-- DataTables CSS is tiny; async media=print trick let tables paint unstyled first on every navigation -->
    <link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Prevent sidebar flash on page load - Apply state before render -->
    <script>
        (function () {
            // Sidebar State
            const sidebarToggled = localStorage.getItem('sidebarToggled');
            if (sidebarToggled === 'true') {
                document.documentElement.classList.add('sidebar-toggled-preload');
            }

            // Dark Mode State
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'enabled') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>
    <style>
        /* Apply toggled state immediately to prevent flash */
        html.sidebar-toggled-preload body {
            overflow-x: hidden;
        }

        html.sidebar-toggled-preload .sidebar {
            width: 6.5rem !important;
        }

        html.sidebar-toggled-preload .sidebar .nav-item .nav-link span {
            display: none;
        }

        html.sidebar-toggled-preload .sidebar .nav-item .nav-link {
            text-align: center;
            padding: 0.75rem 1rem;
            width: 6.5rem;
        }

        html.sidebar-toggled-preload .sidebar .sidebar-brand .sidebar-brand-text {
            display: none;
        }

        html.sidebar-toggled-preload .sidebar .sidebar-brand .sidebar-brand-icon {
            margin-right: 0;
        }

        html.sidebar-toggled-preload #content-wrapper {
            margin-left: 6.5rem;
        }
    </style>

    @yield('styles')
</head>

<body id="page-top">

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-file mr-2"></i>Preview File</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="file-preview-container" style="min-height: 500px; max-height: 80vh; overflow: auto;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="download-file-btn" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('layouts.header')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Hakai 2025 All Right Reserved.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- PERBAIKAN: Pindahkan Modal Logout ke sini, sebagai anak langsung dari <body> -->
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Anda yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Pilih "Logout" dibawah ini jika kamu ingin keluar untuk mengakhiri sesi ini.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts - Optimized Loading -->
    <!-- Critical scripts loaded normally -->
    <script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/performance.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

    <!-- Non-critical scripts loaded with defer -->
    <script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}" defer></script>
    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}" defer></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}" defer></script>

    <!-- SweetAlert2 - Load without defer to ensure availability -->
    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Load Chart.js only when needed -->
    @if(View::hasSection('needs-chart'))
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    @endif

    @yield('scripts')

    <script>
        // Setup AJAX headers for API requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content')
            }
        });

        // Sidebar Toggle State Persistence
        $(document).ready(function () {
            // Check if sidebar state is saved in localStorage
            const sidebarToggled = localStorage.getItem('sidebarToggled');

            // Apply saved state on page load and remove preload class
            if (sidebarToggled === 'true') {
                $('body').addClass('sidebar-toggled');
                $('.sidebar').addClass('toggled');
            }

            // Remove preload class after state is applied
            $('html').removeClass('sidebar-toggled-preload');

            // Save state when sidebar is toggled
            $('#sidebarToggle, #sidebarToggleTop').on('click', function (e) {
                // Determine if we are on mobile
                const isMobile = $(window).width() < 768;

                if (isMobile) {
                    // Custom Mobile Logic
                    e.stopImmediatePropagation();
                    e.preventDefault();

                    $('body').toggleClass('sidebar-mobile-open');
                    $('.sidebar').toggleClass('sidebar-mobile-open');

                    if ($('body').hasClass('sidebar-mobile-open')) {
                        showSidebarOverlay();
                    } else {
                        hideSidebarOverlay();
                    }
                } else {
                    // Desktop Logic
                    setTimeout(function () {
                        const isToggled = $('.sidebar').hasClass('toggled');
                        localStorage.setItem('sidebarToggled', isToggled);
                    }, 100);
                }
            });

            // Mobile Sidebar Overlay
            function showSidebarOverlay() {
                if ($('#sidebar-overlay').length === 0) {
                    $('body').append('<div id="sidebar-overlay"></div>');
                }
                $('#sidebar-overlay').addClass('show');
            }

            function hideSidebarOverlay() {
                $('#sidebar-overlay').removeClass('show');
            }

            function closeMobileSidebar() {
                $('body').removeClass('sidebar-mobile-open');
                $('.sidebar').removeClass('sidebar-mobile-open');
                hideSidebarOverlay();
            }

            // Close sidebar when clicking overlay
            $(document).on('click', '#sidebar-overlay', function () {
                closeMobileSidebar();
            });

            // Close sidebar when clicking a menu item on mobile
            if ($(window).width() < 768) {
                $('.sidebar .nav-link').on('click', function () {
                    setTimeout(closeMobileSidebar, 100);
                });
            }

            // Swipe to close sidebar
            let touchStartX = 0;
            let touchEndX = 0;

            $(document).on('touchstart', '.sidebar', function (e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            $(document).on('touchend', '.sidebar', function (e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                if (touchStartX - touchEndX > 50) { // Swipe left
                    closeMobileSidebar();
                }
            });

            // On mobile, don't persist sidebar state
            if ($(window).width() < 768) {
                // Clean up any legacy classes
                $('body').removeClass('sidebar-toggled');
                $('.sidebar').removeClass('toggled');
            }

            // Dark Mode Toggle Logic
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = darkModeToggle ? darkModeToggle.querySelector('i') : null;
            const htmlElement = document.documentElement;

            // Function to update icon
            function updateDarkModeIcon() {
                if (htmlElement.classList.contains('dark-mode')) {
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                } else {
                    darkModeIcon.classList.remove('fa-sun');
                    darkModeIcon.classList.add('fa-moon');
                }
            }

            // Initial Icon State
            if (darkModeIcon) {
                updateDarkModeIcon();
            }

            // Toggle Event
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    htmlElement.classList.toggle('dark-mode');

                    if (htmlElement.classList.contains('dark-mode')) {
                        localStorage.setItem('darkMode', 'enabled');
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                    }

                    updateDarkModeIcon();
                });
            }
        });

        // Load pending approval count for admin
        @if(auth()->user()->isAdmin())
            function loadPendingApprovalCount() {
                $.ajax({
                    url: '/api/tugas',
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            let pendingCount = res.data.filter(t => t.status === 'submitted').length;
                            if (pendingCount > 0) {
                                $('#pending-approval-badge').text(pendingCount).show();
                            } else {
                                $('#pending-approval-badge').hide();
                            }
                        }
                    }
                });
            }

            // Load on page load
            $(document).ready(function () {
                loadPendingApprovalCount();
                // Refresh every minute
                setInterval(loadPendingApprovalCount, 60000);
            });
        @endif

        // Handle 401 Unauthorized responses (tampilkan sekali saja & paksa logout web session)
        let isSessionExpired = false;
        $(document).ajaxError(function (event, jqXHR, settings, thrownError) {
            if (jqXHR.status === 401 && !isSessionExpired) {
                isSessionExpired = true;

                // Paksa logout session Laravel terlebih dahulu supaya tidak loop login → dashboard
                $.ajax({
                    url: '/logout',
                    type: 'POST',
                    global: false, // jangan trigger ajaxError lagi untuk request ini
                    complete: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Session Expired',
                            text: 'Please login again',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/login';
                        });
                    }
                });
            }
        });


        // File Preview Function
        window.previewFile = function (fileUrl) {
            $('#filePreviewModal').modal('show');
            $('#download-file-btn').attr('href', fileUrl);

            const fileExt = fileUrl.split('.').pop().toLowerCase();
            const container = $('#file-preview-container');

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
                container.html(`<img src="${fileUrl}" class="img-fluid w-100" alt="Preview">`);
            } else if (fileExt === 'pdf') {
                container.html(`<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px">`);
            } else {
                container.html(`
                <div class="text-center py-5">
                    <i class="fas fa-file fa-5x text-muted mb-3"></i>
                    <p class="text-muted">Preview tidak tersedia untuk file ini</p>
                    <a href="${fileUrl}" class="btn btn-primary" download>
                        <i class="fas fa-download mr-1"></i> Download File
                    </a>
                </div>
            `);
            }
        };

        // =====================================================
        // DROPDOWN FIX - Move dropdown to body to escape stacking contexts
        // =====================================================
        $(document).on('show.bs.dropdown', '.table .dropdown', function () {
            const $dropdown = $(this);
            const $menu = $dropdown.find('.dropdown-menu');
            const $toggle = $dropdown.find('.dropdown-toggle');

            // Get toggle position
            const toggleOffset = $toggle.offset();
            const toggleHeight = $toggle.outerHeight();
            const toggleWidth = $toggle.outerWidth();

            // Calculate menu position
            const menuWidth = $menu.outerWidth() || 180;
            const windowHeight = $(window).height();
            const windowWidth = $(window).width();
            const scrollTop = $(window).scrollTop();

            // Position menu below toggle, aligned to right
            let top = toggleOffset.top + toggleHeight + 2;
            let left = toggleOffset.left + toggleWidth - menuWidth;

            // Check if menu would go below viewport
            const menuHeight = $menu.outerHeight() || 200;
            if (top + menuHeight > scrollTop + windowHeight) {
                // Position above toggle instead
                top = toggleOffset.top - menuHeight - 2;
            }

            // Keep menu within viewport horizontally
            if (left < 10) left = 10;
            if (left + menuWidth > windowWidth - 10) {
                left = windowWidth - menuWidth - 10;
            }

            // Apply fixed positioning
            $menu.css({
                'position': 'fixed',
                'top': top - scrollTop + 'px',
                'left': left + 'px',
                'right': 'auto',
                'bottom': 'auto',
                'z-index': '9999',
                'transform': 'none',
                'will-change': 'auto'
            });

            // Move menu to body
            $menu.appendTo('body');

            // Store reference for cleanup
            $dropdown.data('dropdown-menu', $menu);
        });

        $(document).on('hidden.bs.dropdown', '.table .dropdown', function () {
            const $dropdown = $(this);
            const $menu = $dropdown.data('dropdown-menu');

            if ($menu) {
                // Move menu back to dropdown
                $menu.appendTo($dropdown);
                // Reset styles
                $menu.css({
                    'position': '',
                    'top': '',
                    'left': '',
                    'right': '',
                    'bottom': '',
                    'z-index': '',
                    'transform': '',
                    'will-change': ''
                });
            }
        });
    </script>

    <!-- PERBAIKAN: Satukan penanganan session flash message -->
    @if(session('success'))
        <script>
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                    .then(function (registration) {
                        // ServiceWorker registered successfully
                    })
                    .catch(function (err) {
                        // ServiceWorker registration failed
                    });
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            let searchTimeout;
            const $searchInput = $('#globalSearchInput');
            const $suggestionsBox = $('#searchSuggestions');
            const $suggestionsContent = $('#searchSuggestionsContent');

            $searchInput.on('input', function () {
                const query = $(this).val();
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    $suggestionsBox.hide();
                    return;
                }

                searchTimeout = setTimeout(function () {
                    $.ajax({
                        url: '/api/global-search',
                        type: 'GET',
                        data: { query: query },
                        success: function (response) {
                            let html = '';

                            // Tasks
                            if (response.tasks.length > 0) {
                                html += '<h6 class="dropdown-header">Tugas</h6>';
                                response.tasks.forEach(task => {
                                    html += `
                                        <a class="dropdown-item d-flex align-items-center" href="${task.url}">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div style="min-width: 0;">
                                                <div class="small text-gray-500 text-truncate">${task.subtitle}</div>
                                                <span class="font-weight-bold d-block text-truncate">${task.title}</span>
                                            </div>
                                        </a>
                                    `;
                                });
                            }

                            // Users
                            if (response.users.length > 0) {
                                html += '<div class="dropdown-divider"></div>';
                                html += '<h6 class="dropdown-header">Karyawan</h6>';
                                response.users.forEach(user => {
                                    let imgHtml = user.image
                                        ? `<img class="img-profile rounded-circle" src="/storage/${user.image}" style="width: 30px; height: 30px;">`
                                        : `<div class="icon-circle bg-success"><i class="fas fa-user text-white"></i></div>`;

                                    html += `
                                        <a class="dropdown-item d-flex align-items-center" href="${user.url}">
                                            <div class="mr-3">
                                                ${imgHtml}
                                            </div>
                                            <div style="min-width: 0;">
                                                <span class="font-weight-bold d-block text-truncate">${user.title}</span>
                                                <div class="small text-gray-500 text-truncate">${user.subtitle}</div>
                                            </div>
                                        </a>
                                    `;
                                });
                            }

                            if (html === '') {
                                html = '<a class="dropdown-item text-center small text-gray-500" href="#">Tidak ada hasil ditemukan</a>';
                            }

                            $suggestionsContent.html(html);
                            $suggestionsBox.show();
                        }
                    });
                }, 300); // Debounce 300ms
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.navbar-search').length) {
                    $suggestionsBox.hide();
                }
            });
        });
    </script>
</body>

</html>