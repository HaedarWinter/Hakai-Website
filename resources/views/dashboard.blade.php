@extends('layouts.app')

@section('styles')
    <style>
        /* Pagination Disabled Cursor */
        .paginate_button.disabled a,
        .paginate_button.disabled,
        .page-item.disabled .page-link,
        .page-item.disabled {
            cursor: not-allowed !important;
            pointer-events: all !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i>
                Dashboard
            </h1>
            <div class="d-flex align-items-center">
                @if(Auth::user()->isAdmin())
                    <div class="btn-group btn-group-sm mr-3" role="group">
                        <button type="button" class="btn btn-primary" id="scope-global" onclick="setScope('global')">
                            <i class="fas fa-globe mr-1"></i> Semua Tugas
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="scope-personal"
                            onclick="setScope('personal')">
                            <i class="fas fa-user mr-1"></i> Tugas Saya
                        </button>
                    </div>
                @endif
                <div class="d-none d-sm-flex align-items-center">
                    <span class="text-muted small mr-3" id="last-updated">
                        <i class="fas fa-sync-alt mr-1"></i> Terakhir diperbarui: --
                    </span>
                    <button class="btn btn-sm btn-outline-primary" id="refresh-dashboard" title="Refresh Data">
                        <i class="fas fa-redo-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-pill px-4 py-2 d-inline-flex align-items-center mb-2"
                    style="border-radius: 12px; backdrop-filter: blur(4px);">
                    <span class="small font-weight-bold text-primary mr-3"><i class="fas fa-bolt mr-2"></i>Aksi
                        Cepat:</span>
                    <div class="quick-actions">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('tugas.index') }}"
                                class="btn btn-sm btn-white shadow-sm border rounded-pill mr-2">
                                <i class="fas fa-plus-circle text-primary mr-1"></i> Buat Tugas
                            </a>
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-white shadow-sm border rounded-pill mr-2">
                                <i class="fas fa-user-plus text-success mr-1"></i> Tambah User
                            </a>
                            <a href="{{ route('admin.audit-log.index') }}"
                                class="btn btn-sm btn-white shadow-sm border rounded-pill mr-2">
                                <i class="fas fa-history text-info mr-1"></i> Audit Log
                            </a>
                        @endif
                        <a href="{{ route('tugas.index') }}" class="btn btn-sm btn-white shadow-sm border rounded-pill">
                            <i class="fas fa-list-ul text-warning mr-1"></i> Daftar Tugas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <x-stat-card title="Total Tugas" :value="0" icon="fa-tasks" color="primary" id="total-tugas" />
            @if(Auth::user()->isAdmin())
                <x-stat-card title="Total Users" :value="0" icon="fa-users" color="success" id="total-users" />
            @endif
            <x-stat-card title="Tugas Selesai" :value="0" icon="fa-check-circle" color="info" id="total-selesai" />
            <x-stat-card title="Tugas Pending" :value="0" icon="fa-clock" color="warning" id="total-pending" />
        </div>

        <!-- Deadline Warning & Completion Rate Row -->
        <div class="row mb-4">
            <!-- Deadline Warning Widget -->
            <div class="col-xl-4 col-lg-6 mb-4 animate-in" style="animation-delay: 0.1s">
                <div class="premium-card h-100 border-top-danger">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-800">
                            <i class="fas fa-hourglass-half mr-2 text-danger"></i>Deadline Mendekat
                        </h6>
                        <span class="badge badge-danger badge-pill px-3" id="deadline-count">0</span>
                    </div>
                    <div class="card-body p-0">
                        <div id="deadline-tasks" style="max-height: 250px; overflow-y: auto;">
                            <!-- Skeleton Loading -->
                            <div class="skeleton-loading p-3">
                                <div class="skeleton-row">
                                    <div class="skeleton skeleton-avatar"></div>
                                    <div class="skeleton-content">
                                        <div class="skeleton skeleton-text"></div>
                                        <div class="skeleton skeleton-text short"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton skeleton-avatar"></div>
                                    <div class="skeleton-content">
                                        <div class="skeleton skeleton-text"></div>
                                        <div class="skeleton skeleton-text short"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Rate Widget -->
            <div class="col-xl-4 col-lg-6 mb-4 animate-in" style="animation-delay: 0.2s">
                <div class="premium-card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-chart-pie mr-2"></i>Tingkat Penyelesaian
                        </h6>
                    </div>
                    <div class="card-body completion-container pt-0 text-center">
                        <div class="position-relative d-inline-block mb-4">
                            <svg width="160" height="160" viewBox="0 0 160 160" class="completion-svg">
                                <circle cx="80" cy="80" r="70" fill="none" stroke="#f1f3f9" stroke-width="14" />
                                <circle id="completion-circle" cx="80" cy="80" r="70" fill="none"
                                    stroke="url(#completion-gradient)" stroke-width="14" stroke-linecap="round"
                                    stroke-dasharray="440" stroke-dashoffset="440" transform="rotate(-90 80 80)"
                                    style="transition: stroke-dashoffset 1.5s cubic-bezier(0.16, 1, 0.3, 1);" />
                                <defs>
                                    <linearGradient id="completion-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#1cc88a" />
                                        <stop offset="100%" style="stop-color:#36b9cc" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <div class="h1 font-weight-extrabold text-gray-800 mb-0 animate-number"
                                    id="completion-rate">0%</div>
                                <div class="text-xs text-muted font-weight-bold text-uppercase">Selesai</div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-success mb-0" id="rate-approved">0</div>
                                <small class="text-muted">Disetujui</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-info mb-0" id="rate-submitted">0</div>
                                <small class="text-muted">Proses</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-warning mb-0" id="rate-pending">0</div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Widget -->
            <div class="col-xl-4 col-lg-12 mb-4 animate-in" style="animation-delay: 0.3s">
                <div class="premium-card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history mr-2"></i>Aktivitas Terbaru
                        </h6>
                    </div>
                    <div class="card-body p-4 pt-1">
                        <div class="activity-timeline" id="activity-log" style="max-height: 220px; overflow-y: auto;">
                            <!-- Activity items will be loaded here -->
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <p class="small text-muted mt-2 mb-0">Memuat aktivitas...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-6 animate-in" style="animation-delay: 0.4s">
                <div class="premium-card mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="m-0 font-weight-bold text-primary">Status Tugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="statusChart"></canvas>
                            <div id="statusChartEmpty"
                                class="d-none align-items-center justify-content-center flex-column h-100 w-100 position-absolute"
                                style="top: 0; left: 0; background: white;">
                                <div class="icon-circle bg-light mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                                <p class="text-muted small mb-0">Belum ada data tugas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 animate-in" style="animation-delay: 0.5s">
                <div class="premium-card mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="m-0 font-weight-bold text-primary">Trend Tugas (7 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            @if(Auth::user()->isAdmin())
                <div class="col-lg-6 mb-4 animate-in" style="animation-delay: 0.6s">
                    <div class="premium-card h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <a href="{{ route('user.index') }}" class="btn btn-outline-primary btn-block py-3">
                                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                        <span>Kelola User</span>
                                    </a>
                                </div>
                                <div class="col-6 mb-3">
                                    <a href="{{ route('tugas.index') }}" class="btn btn-outline-success btn-block py-3">
                                        <i class="fas fa-tasks fa-2x mb-2 d-block"></i>
                                        <span>Kelola Tugas</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('users.excel') }}" data-export-type="Excel"
                                        class="btn btn-outline-info btn-block py-3">
                                        <i class="fas fa-file-excel fa-2x mb-2 d-block"></i>
                                        <span>Export User</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-block py-3">
                                        <i class="fas fa-cog fa-2x mb-2 d-block"></i>
                                        <span>Pengaturan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ Auth::user()->isAdmin() ? 'col-lg-6' : 'col-12' }} mb-4 animate-in"
                style="animation-delay: 0.7s">
                <div class="premium-card h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clipboard-list mr-2"></i>Tugas Terbaru
                        </h6>
                        <a href="{{ route('tugas.index') }}" class="btn btn-sm btn-link text-primary font-weight-bold">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div id="recent-tasks" style="max-height: 300px; overflow-y: auto;">
                            <!-- Skeleton Loading -->
                            <div class="skeleton-loading">
                                <div class="skeleton-row">
                                    <div class="skeleton skeleton-avatar"></div>
                                    <div class="skeleton-content">
                                        <div class="skeleton skeleton-text"></div>
                                        <div class="skeleton skeleton-text short"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton skeleton-avatar"></div>
                                    <div class="skeleton-content">
                                        <div class="skeleton skeleton-text"></div>
                                        <div class="skeleton skeleton-text short"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton skeleton-avatar"></div>
                                    <div class="skeleton-content">
                                        <div class="skeleton skeleton-text"></div>
                                        <div class="skeleton skeleton-text short"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/export-handler.js') }}"></script>
    <script>
        const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/page/dashboard.js') }}"></script>
@endsection

@section('needs-chart')
@endsection