<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" role="navigation" aria-label="Sidebar navigation">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('welcome')}}" aria-label="Hakai - Home">
        <div class="sidebar-brand-icon" aria-hidden="true">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="sidebar-brand-text mx-4">HAKAI</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" role="separator">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ $menuDashboard ?? ''}} ">
        <a class="nav-link" href="{{route('dashboard')}}" aria-label="Dashboard">
            <i class="fas fa-fw fa-tachometer-alt" aria-hidden="true"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" role="separator">

    @if(auth()->user()->jabatan == 'Admin')
        <!-- Heading -->
        <div class="sidebar-heading" role="heading" aria-level="2">
            Menu Admin
        </div>
        <!-- Nav Data - User -->
        <li class="nav-item {{ $menuAdminUser ?? ''}}">
            <a class="nav-link" href="{{route('user.index')}}" aria-label="Data User">
                <i class="fas fa-fw fa-user" aria-hidden="true"></i>
                <span>Data User</span></a>
        </li>
        <!-- Nav Data - Tugas -->
        <li class="nav-item {{ $menuAdminTugas ?? '' }} ">
            <a class="nav-link" href="{{route('tugas.index')}}" aria-label="Data Tugas">
                <i class="fas fa-fw fa-tasks" aria-hidden="true"></i>
                <span>Data Tugas</span>
                <span id="pending-approval-badge" class="badge badge-danger badge-counter ml-auto" style="display:none;" role="status" aria-live="polite" aria-label="Pending approvals">0</span>
            </a>
        </li>
    @else
        <!-- Heading -->
        <div class="sidebar-heading" role="heading" aria-level="2">
            Menu Karyawan
        </div>
        <!-- Nav Item - Tables -->
        <li class="nav-item {{ $menuKaryawanTugas ?? '' }} ">
            <a class="nav-link" href="{{route('tugas.index')}}" aria-label="Data Tugas">
                <i class="fas fa-fw fa-tasks" aria-hidden="true"></i>
                <span>Data Tugas</span></a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block" role="separator">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" aria-label="Toggle sidebar" aria-controls="accordionSidebar" aria-expanded="true"></button>
    </div>

</ul>
<!-- End of Sidebar -->
