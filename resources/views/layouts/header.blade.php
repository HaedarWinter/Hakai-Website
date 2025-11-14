<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" role="navigation" aria-label="Main navigation">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" aria-label="Toggle sidebar" aria-controls="accordionSidebar" aria-expanded="false">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <!-- PERBAIKAN: Tambahkan tag <a> sebagai pemicu dropdown -->
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="View notifications">
                <i class="fas fa-bell fa-fw" aria-hidden="true"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" aria-label="3 or more notifications">3+</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">Alerts Center</h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been deposited into your account!
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <!-- PERBAIKAN: Tambahkan tag <a> sebagai pemicu dropdown -->
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="View messages">
                <i class="fas fa-envelope fa-fw" aria-hidden="true"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter" aria-label="7 unread messages">7</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">Message Center</h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <!-- PERBAIKAN: Gunakan path asset yang benar atau URL eksternal -->
                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="Emily Fowler profile picture" loading="lazy">
                        <div class="status-indicator bg-success" role="status" aria-label="Online"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="User menu for {{ auth()->user()->nama }}">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->nama }}</span>
                <!-- PERBAIKAN: Gunakan asset helper untuk path yang benar dan fallback avatar -->
                <img class="img-profile rounded-circle"
                     src="{{ auth()->user()->profile_photo_url ?? asset('sbadmin2/img/undraw_profile.svg') }}"
                     alt="{{ auth()->user()->nama }} profile picture"
                     loading="lazy">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <div class="d-flex justify-content-center">
                        <span class="badge {{ auth()->user()->jabatan == 'Admin' ? 'badge-success badge-pill px-5 py-1' : 'badge-danger badge-pill px-5 py-1' }}">
                            {{ auth()->user()->jabatan }}
                        </span>
                    </div>
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
