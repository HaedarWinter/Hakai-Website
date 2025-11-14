
@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i>
                Dashboard
            </h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <x-stat-card 
                title="Total Tugas" 
                :value="0" 
                icon="fa-tasks" 
                color="primary" 
                id="total-tugas" 
            />
            <x-stat-card 
                title="Total Users" 
                :value="0" 
                icon="fa-users" 
                color="success" 
                id="total-users" 
            />
            <x-stat-card 
                title="Tugas Selesai" 
                :value="0" 
                icon="fa-check-circle" 
                color="info" 
                id="total-selesai" 
            />
            <x-stat-card 
                title="Tugas Pending" 
                :value="0" 
                icon="fa-clock" 
                color="warning" 
                id="total-pending" 
            />
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Status Tugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trend Tugas (7 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            @if(Auth::user()->isAdmin())
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelola Data User</h6>
                                        <small><i class="fas fa-arrow-right"></i></small>
                                    </div>
                                    <p class="mb-1">Tambah, edit, atau hapus data user</p>
                                </a>
                                <a href="{{ route('tugas.index') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelola Tugas</h6>
                                        <small><i class="fas fa-arrow-right"></i></small>
                                    </div>
                                    <p class="mb-1">Lihat dan kelola semua tugas</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tugas Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div id="recent-tasks">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat data tugas...</p>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('tugas.index') }}" class="btn btn-primary btn-sm">Lihat Semua Tugas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // --- PERBAIKAN: Pindahkan logika Blade ke variabel JavaScript di luar fungsi ---
            // Ini mencegah error sintaks dan memisahkan logika server dari logika client.
            const isAdmin = "{{ Auth::user()->jabatan }}" === "Admin";

            // Load dashboard statistics
            function loadDashboardStats() {
                $.ajax({
                    url: "/api/dashboard/stats",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            $('#total-tugas').text(res.data.total_tugas);
                            $('#total-users').text(res.data.total_users);
                            $('#total-selesai').text(res.data.total_selesai);
                            $('#total-pending').text(res.data.total_pending);
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to load dashboard stats:', xhr);
                    }
                });
            }

            // Load recent tasks
            function loadRecentTasks() {
                $.ajax({
                    url: "/api/dashboard/recent-tasks",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            let tasksHtml = '';

                            if (res.data.length > 0) {
                                tasksHtml = '<div class="list-group">';
                                $.each(res.data, function(index, task) {
                                    let statusBadge = '';
                                    let statusClass = '';

                                    if(task.status === 'approved') {
                                        statusBadge = 'Disetujui';
                                        statusClass = 'success';
                                    } else if(task.status === 'submitted') {
                                        statusBadge = 'Menunggu Persetujuan';
                                        statusClass = 'info';
                                    } else if(task.status === 'rejected') {
                                        statusBadge = 'Ditolak';
                                        statusClass = 'danger';
                                    } else {
                                        statusBadge = 'Pending';
                                        statusClass = 'warning';
                                    }

                                    // --- PERBAIKAN: Gunakan variabel `isAdmin` dan string concatenation ---
                                    // Ini menghindari template literal bersarang dan error sintaks.
                                    tasksHtml += `
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${task.tugas}</h6>
                                        <span class="badge badge-${statusClass}">${statusBadge}</span>
                                    </div>
                                    <p class="mb-1">${isAdmin ? '<small>Ditugaskan ke: ' + (task.user ? task.user.nama : 'User tidak ditemukan') + '</small>' : ''}</p>
                                    <small>${task.created_at}</small>
                                </div>
                                `;
                                });
                                tasksHtml += '</div>';
                            } else {
                                tasksHtml = '<p class="text-center text-muted">Belum ada data tugas</p>';
                            }

                            $('#recent-tasks').html(tasksHtml);
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to load recent tasks:', xhr);
                        $('#recent-tasks').html('<p class="text-center text-muted">Gagal memuat data tugas</p>');
                    }
                });
            }

            // Initial load
            loadDashboardStats();
            loadRecentTasks();
            initCharts();

            // Refresh every 30 seconds
            setInterval(function() {
                loadDashboardStats();
                loadRecentTasks();
                updateCharts();
            }, 30000);

            // Initialize Charts
            let statusChart, trendChart;
            
            function initCharts() {
                // Status Chart (Pie)
                const statusCtx = document.getElementById('statusChart').getContext('2d');
                statusChart = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak'],
                        datasets: [{
                            data: [0, 0, 0, 0],
                            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Trend Chart (Line)
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                const last7Days = [];
                for (let i = 6; i >= 0; i--) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    last7Days.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
                }

                trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: last7Days,
                        datasets: [{
                            label: 'Tugas Selesai',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Tugas Baru',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                updateCharts();
            }

            function updateCharts() {
                $.ajax({
                    url: "/api/tugas",
                    type: "GET",
                    success: function(res) {
                        if (res.success) {
                            let pending = 0, submitted = 0, approved = 0, rejected = 0;
                            res.data.forEach(task => {
                                if (task.status === 'pending') pending++;
                                else if (task.status === 'submitted') submitted++;
                                else if (task.status === 'approved') approved++;
                                else if (task.status === 'rejected') rejected++;
                            });

                            statusChart.data.datasets[0].data = [pending, submitted, approved, rejected];
                            statusChart.update();

                            // For trend chart, you would need more data from backend
                            // This is a simplified version
                            const randomData1 = Array.from({length: 7}, () => Math.floor(Math.random() * 10));
                            const randomData2 = Array.from({length: 7}, () => Math.floor(Math.random() * 10));
                            trendChart.data.datasets[0].data = randomData1;
                            trendChart.data.datasets[1].data = randomData2;
                            trendChart.update();
                        }
                    }
                });
            }
        });
    </script>
@endsection
