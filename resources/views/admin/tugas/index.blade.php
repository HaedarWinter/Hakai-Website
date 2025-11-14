@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks text-primary mr-2"></i>
                Manajemen Tugas
            </h1>
            <div>
                <a href="{{route('tugas.excel')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                </a>
                <a href="{{route('tugas.pdf')}}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm ml-1">
                    <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Export PDF
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tugas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-tugas">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-selesai">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Proses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-proses">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-spinner fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-pending">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold text-primary">Data Tugas Karyawan</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddTask">
                            <i class="fas fa-plus mr-1"></i> Tambah Tugas
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">Filter Status:</label>
                        <select id="filter-status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="submitted">Menunggu Persetujuan</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">Filter Karyawan:</label>
                        <select id="filter-user" class="form-control form-control-sm">
                            <option value="">Semua Karyawan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">Filter Prioritas:</label>
                        <select id="filter-priority" class="form-control form-control-sm">
                            <option value="">Semua Prioritas</option>
                            <option value="high">Tinggi</option>
                            <option value="medium">Sedang</option>
                            <option value="low">Rendah</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">&nbsp;</label>
                        <button id="reset-filter" class="btn btn-secondary btn-sm btn-block">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div id="bulk-actions-bar" class="alert alert-info mb-3" style="display: none;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <strong><span id="selected-count">0</span> tugas dipilih</strong>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success btn-sm" id="bulk-approve">
                                <i class="fas fa-check mr-1"></i> Approve Semua
                            </button>
                            <button class="btn btn-danger btn-sm" id="bulk-reject">
                                <i class="fas fa-times mr-1"></i> Reject Semua
                            </button>
                            <button class="btn btn-dark btn-sm" id="bulk-delete">
                                <i class="fas fa-trash mr-1"></i> Hapus Semua
                            </button>
                            <button class="btn btn-secondary btn-sm" id="clear-selection">
                                <i class="fas fa-times-circle mr-1"></i> Batal
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="taskTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center" style="width: 40px;">
                                <input type="checkbox" id="select-all-tasks" title="Pilih Semua">
                            </th>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Karyawan</th>
                            <th>Tugas</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="task-body">
                        <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="modalAddTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus mr-2"></i> Tambah Tugas Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-task" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user text-gray-600 mr-1"></i> Karyawan
                            </label>
                            <select name="user_id" class="form-control form-control-lg" required>
                          
                                {{-- Opsi karyawan dimuat via JavaScript function loadEmployees() ketika modal dibuka --}}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tasks text-gray-600 mr-1"></i> Deskripsi Tugas
                            </label>
                            <textarea name="tugas" class="form-control form-control-lg" rows="3" placeholder="Deskripsi tugas..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file text-gray-600 mr-1"></i> File Pendukung (opsional)
                            </label>
                            <input type="file" name="file" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG (max 2MB)</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt text-gray-600 mr-1"></i> Tanggal Mulai
                                </label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check text-gray-600 mr-1"></i> Tanggal Selesai
                                </label>
                                <input type="datetime-local" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag text-gray-600 mr-1"></i> Prioritas
                            </label>
                            <select name="priority" class="form-control">
                                <option value="low">Rendah</option>
                                <option value="medium" selected>Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="modalEditTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i> Edit Tugas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-task">
                    @csrf
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tasks text-gray-600 mr-1"></i> Deskripsi Tugas
                            </label>
                            <textarea id="edit-tugas" name="tugas" class="form-control form-control-lg" rows="3" placeholder="Deskripsi tugas..." required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt text-gray-600 mr-1"></i> Tanggal Mulai
                                </label>
                                <input type="datetime-local" id="edit-tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check text-gray-600 mr-1"></i> Tanggal Selesai
                                </label>
                                <input type="datetime-local" id="edit-tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag text-gray-600 mr-1"></i> Prioritas
                            </label>
                            <select name="priority" id="edit-priority" class="form-control">
                                <option value="low">Rendah</option>
                                <option value="medium">Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt mr-1"></i> Update Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div class="modal fade" id="modalDetailTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle mr-2"></i> Detail Tugas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="task-detail-content">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Task Modal -->
    <div class="modal fade" id="modalRejectTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle mr-2"></i> Tolak Tugas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-reject-task">
                    @csrf
                    <input type="hidden" id="reject-task-id">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Anda akan menolak tugas dari <strong id="reject-task-nama"></strong>: <strong id="reject-task-tugas"></strong>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sticky-note text-gray-600 mr-1"></i> Catatan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="catatan" id="reject-catatan" class="form-control form-control-lg" rows="4" placeholder="Berikan alasan penolakan..." required></textarea>
                            <small class="text-muted">Catatan ini akan dilihat oleh karyawan</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle mr-1"></i> Tolak Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        .table thead th {
            border-top: none;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .form-control-lg {
            border-radius: 0.5rem;
            border: 1px solid #d1d3e2;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }
        .form-control-lg:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 600;
            border-radius: 0.35rem;
        }
        .icon-circle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .task-description {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fc;
            transform: scale(1.01);
        }
        .btn-group .btn {
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
        }
    </style>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const taskTable = $('#taskTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    url: '{{ asset("assets/datatables/i18n/id.json") }}'
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control-sm');
                    $('.dataTables_filter input').attr('placeholder', 'Cari tugas...');
                    $('.dataTables_filter label').contents().filter(function() {
                        return this.nodeType === 3;
                    }).replaceWith('<i class="fas fa-search fa-sm"></i>');
                }
            });

            // --- PERBAIKAN: Manajemen State & Loading yang Lebih Baik ---
            const State = {
                isLoading: false,
                hasError: false,
                lastLoadTime: 0,
                intervalId: null,
            };

            function showLoading() {
                $('#task-body').html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data tugas...</p>
                </td>
            </tr>
        `);
            }

            function showError(message) {
                State.hasError = true;
                $('#task-body').html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger">${message}</p>
                    <button class="btn btn-sm btn-primary" onclick="loadTasks(true)">
                        <i class="fas fa-sync-alt mr-1"></i> Coba Lagi
                    </button>
                </td>
            </tr>
        `);
            }

            // Helper function to format datetime for display
            function formatDateTime(dateString) {
                if (!dateString) return '-';
                
                try {
                    // Parse the date string - handle both "YYYY-MM-DD HH:mm:ss" and ISO format
                    let date;
                    if (dateString.includes('T')) {
                        // ISO format
                        date = new Date(dateString);
                    } else {
                        // MySQL datetime format: "YYYY-MM-DD HH:mm:ss"
                        // Replace space with 'T' to make it ISO compatible
                        date = new Date(dateString.replace(' ', 'T'));
                    }
                    
                    // Check if date is valid
                    if (isNaN(date.getTime())) {
                        console.error('Invalid date:', dateString);
                        return dateString; // Return original string if parsing fails
                    }
                    
                    // Format: "5 Nov 2025, 08:00"
                    const day = date.getDate();
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    
                    return `${day} ${month} ${year}, ${hours}:${minutes}`;
                } catch (error) {
                    console.error('Error formatting date:', error, dateString);
                    return dateString;
                }
            }

            // Helper function to format datetime for input datetime-local
            function formatDateTimeLocal(dateString) {
                if (!dateString) return '';
                
                try {
                    // Parse the date string
                    let date;
                    if (dateString.includes('T')) {
                        date = new Date(dateString);
                    } else {
                        // MySQL datetime format: "YYYY-MM-DD HH:mm:ss"
                        date = new Date(dateString.replace(' ', 'T'));
                    }
                    
                    // Check if date is valid
                    if (isNaN(date.getTime())) {
                        console.error('Invalid date for input:', dateString);
                        return '';
                    }
                    
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    
                    return `${year}-${month}-${day}T${hours}:${minutes}`;
                } catch (error) {
                    console.error('Error formatting date for input:', error, dateString);
                    return '';
                }
            }

            function loadTasks(forceReload = false) {
                // Cegah panggilan ganda kecuali dipaksa
                if (State.isLoading && !forceReload) {
                    console.log('loadTasks() already in progress, skipping...');
                    return;
                }

                // Jika sudah ada error, dan ini bukan reload paksa, jangan coba lagi otomatis
                if (State.hasError && !forceReload) {
                    console.log('Error state detected, skipping automatic reload.');
                    return;
                }

                State.isLoading = true;
                State.hasError = false;
                showLoading();

                $.ajax({
                    url: "/api/tugas?_t=" + Date.now(), // Cache busting
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            // --- PERBAIKAN: Hapus tabel dan bangun ulang DOM untuk sinkronisasi penuh ---
                            taskTable.clear().draw(); // Hapus semua data yang ada di tabel DataTables

                            let stats = { total: 0, selesai: 0, proses: 0, pending: 0 };
                            let dataRows = []; // Siapkan array untuk menampung data baru

                            $.each(res.data, function(index, task) {
                                stats.total++;
                                let statusBadge = '';
                                let statusClass = '';
                                if(task.status === 'approved') { statusBadge = 'Disetujui'; statusClass = 'success'; stats.selesai++; }
                                else if(task.status === 'submitted') { statusBadge = 'Menunggu Persetujuan'; statusClass = 'info'; stats.proses++; }
                                else if(task.status === 'rejected') { statusBadge = 'Ditolak'; statusClass = 'danger'; stats.proses++; }
                                else { statusBadge = 'Pending'; statusClass = 'warning'; stats.pending++; }

                                // Debug: Log tanggal yang diterima dari server (hanya untuk task pertama)
                                if (index === 0) {
                                    console.log('Sample task data:', {
                                        id: task.id,
                                        tanggal_mulai: task.tanggal_mulai,
                                        tanggal_selesai: task.tanggal_selesai,
                                        formatted_mulai: formatDateTime(task.tanggal_mulai),
                                        formatted_selesai: formatDateTime(task.tanggal_selesai)
                                    });
                                }

                                // --- PERBAIKAN: Tambahkan data ke array, bukan langsung ke DOM ---
                                const formattedStartDate = formatDateTime(task.tanggal_mulai);
                                const formattedEndDate = formatDateTime(task.tanggal_selesai);
                                
                                dataRows.push([
                                    `<input type="checkbox" class="task-checkbox" data-id="${task.id}" data-status="${task.status}">`,
                                    index + 1,
                                    `<div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold">${task.user.nama}</div>
                                    <div class="small text-gray-500">${task.user.email}</div>
                                </div>
                            </div>`,
                                    `<div class="task-description">${task.tugas}</div>`,
                                    `<div class="text-center">
                                <div class="small text-gray-600"><i class="fas fa-calendar-alt mr-1"></i> Mulai:</div>
                                <div class="badge badge-info mb-1">${formattedStartDate}</div>
                                <div class="small text-gray-600"><i class="fas fa-calendar-check mr-1"></i> Selesai:</div>
                                <div class="badge badge-dark">${formattedEndDate}</div>
                            </div>`,
                                    `<span class="badge badge-${statusClass}">${statusBadge}</span>`,
                                    `<div class="btn-group" role="group">
                                ${task.status === 'submitted' ? `
                                    <button class="btn btn-success btn-sm shadow-sm btn-approve" data-id="${task.id}" data-nama="${task.user.nama}" title="Setujui"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-danger btn-sm shadow-sm btn-reject" data-id="${task.id}" data-nama="${task.user.nama}" data-tugas="${task.tugas}" title="Tolak"><i class="fas fa-times"></i></button>
                                ` : ''}
                                <button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-warning btn-sm shadow-sm btn-edit" data-id="${task.id}" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm shadow-sm btn-delete" data-id="${task.id}" title="Hapus"><i class="fas fa-trash"></i></button>
                            </div>`
                                ]);
                            });

                            // --- PERBAIKAN: Tambahkan data ke DataTables sekaligus ---
                            taskTable.rows.add(dataRows).draw(); // Tambahkan semua baris sekaligus, lebih efisien

                            // Update statistik
                            $('#total-tugas').text(stats.total);
                            $('#total-selesai').text(stats.selesai);
                            $('#total-proses').text(stats.proses);
                            $('#total-pending').text(stats.pending);
                            State.lastLoadTime = Date.now();
                        } else {
                            showError('Server merespon sukses, tetapi tidak ada data.');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal memuat data tugas.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        console.error('AJAX Error:', xhr);
                        showError(errorMsg);
                    },
                    complete: function() {
                        State.isLoading = false;
                    }
                });
            }

            // --- PERBAIKAN: Manajemen Interval yang Lebih Cerdap ---
            function startAutoRefresh() {
                if (State.intervalId) {
                    clearInterval(State.intervalId);
                }
                // Refresh otomatis setiap 30 detik, hanya jika tidak ada error
                State.intervalId = setInterval(() => {
                    if (!State.hasError) {
                        loadTasks();
                    }
                }, 30000);
            }

            function stopAutoRefresh() {
                if (State.intervalId) {
                    clearInterval(State.intervalId);
                    State.intervalId = null;
                }
            }

            // Load employees for dropdown
            function loadEmployees() {
                $.ajax({
                    url: "/api/tugas/karyawan",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            // For add modal - with placeholder
                            let options = '<option value="" selected disabled>-- Pilih Karyawan --</option>';
                            // For filter - without disabled
                            let filterOptions = '<option value="">Semua Karyawan</option>';
                            
                            $.each(res.data, function(index, employee) {
                                options += `<option value="${employee.id}">${employee.nama}</option>`;
                                filterOptions += `<option value="${employee.id}">${employee.nama}</option>`;
                            });
                            
                            $('select[name="user_id"]').html(options);
                            $('#filter-user').html(filterOptions);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading employees:', xhr);
                        $('select[name="user_id"]').html('<option value="">Gagal memuat data</option>');
                    }
                });
            }

            // Filter functionality
            function applyFilters() {
                let table = $('#taskTable').DataTable();
                
                // Reset search
                table.search('').columns().search('');
                
                // Apply status filter (column 5)
                let statusFilter = $('#filter-status').val();
                if (statusFilter) {
                    table.column(5).search(statusFilter);
                }
                
                // Apply user filter (column 2)
                let userFilter = $('#filter-user').val();
                if (userFilter) {
                    table.column(2).search(userFilter);
                }
                
                table.draw();
            }

            $('#filter-status').on('change', function() {
                applyFilters();
            });

            $('#filter-user').on('change', function() {
                applyFilters();
            });

            $('#filter-priority').on('change', function() {
                // Priority filter - will work on task data
                let priorityFilter = $(this).val();
                let table = $('#taskTable').DataTable();
                
                if (priorityFilter) {
                    // Custom search function for priority
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            let task = taskTable.row(dataIndex).data();
                            if (!task) return true;
                            // Check if the row contains the priority badge
                            return data[3].includes(`priority-${priorityFilter}`) || priorityFilter === '';
                        }
                    );
                } else {
                    $.fn.dataTable.ext.search.pop();
                }
                
                table.draw();
            });

            $('#reset-filter').on('click', function() {
                $('#filter-status').val('');
                $('#filter-user').val('');
                $('#filter-priority').val('');
                
                // Clear custom search functions
                $.fn.dataTable.ext.search = [];
                
                let table = $('#taskTable').DataTable();
                table.search('').columns().search('').draw();
            });

            // --- EVENT HANDLER (DIPERBAIKI DENGAN MANAJEMEN STATE) ---
            // CREATE TASK
            $('#form-add-task').on('submit', function(e) {
                e.preventDefault();
                stopAutoRefresh(); // Hentikan auto-refresh saat user melakukan aksi
                let formData = new FormData(this);
                
                // Debug: Log data yang akan dikirim
                console.log('Submitting new task with datetime:', {
                    tanggal_mulai: formData.get('tanggal_mulai'),
                    tanggal_selesai: formData.get('tanggal_selesai')
                });
                
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...');
                $submitBtn.prop('disabled', true);
                $.ajax({
                    url: "/api/tugas",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            console.log('Task created successfully:', res.data);
                            $('#modalAddTask').modal('hide');
                            $('#form-add-task')[0].reset();
                            // Reload immediately
                            setTimeout(function() {
                                loadTasks(true);
                            }, 100);
                            startAutoRefresh(); // Mulai lagi auto-refresh
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorText = '<ul class="list-unstyled">';
                        for (const [key, value] of Object.entries(errors)) {
                            errorText += `<li><i class="fas fa-times-circle text-danger mr-1"></i> ${value[0]}</li>`;
                        }
                        errorText += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorText || 'Terjadi kesalahan saat menyimpan data'
                        });
                    },
                    complete: function() {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // DELETE TASK
            $(document).on('click', '.btn-delete', function() {
                let taskId = $(this).data('id');
                
                Swal.fire({
                    title: 'Hapus Tugas?',
                    text: 'Apakah Anda yakin ingin menghapus tugas ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        stopAutoRefresh();
                        $.ajax({
                            url: "/api/tugas/" + taskId,
                            type: "DELETE",
                            cache: false,
                            success: function(res) {
                                if (res.success) {
                                    loadTasks(true);
                                    startAutoRefresh();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: res.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    startAutoRefresh();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: res.message || 'Terjadi kesalahan'
                                    });
                                }
                            },
                            error: function(xhr) {
                                startAutoRefresh();
                                let errorMsg = 'Terjadi kesalahan saat menghapus data';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });

            // EDIT TASK - GET DATA
            $(document).on('click', '.btn-edit', function() {
                let taskId = $(this).data('id');
                Swal.fire({
                    title: 'Memuat data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: "/api/tugas/" + taskId,
                    type: "GET",
                    success: function(res) {
                        if(res.success) {
                            let t = res.data;
                            
                            // Debug: Log data yang diterima untuk edit
                            console.log('Loading task for edit:', {
                                id: t.id,
                                tanggal_mulai_raw: t.tanggal_mulai,
                                tanggal_selesai_raw: t.tanggal_selesai,
                                tanggal_mulai_formatted: formatDateTimeLocal(t.tanggal_mulai),
                                tanggal_selesai_formatted: formatDateTimeLocal(t.tanggal_selesai)
                            });
                            
                            $('#edit-id').val(t.id);
                            $('#edit-tugas').val(t.tugas);
                            $('#edit-tanggal_mulai').val(formatDateTimeLocal(t.tanggal_mulai));
                            $('#edit-tanggal_selesai').val(formatDateTimeLocal(t.tanggal_selesai));
                            $('#edit-priority').val(t.priority || 'medium');
                            $('#modalEditTask').modal('show');
                            Swal.close();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat memuat data tugas'
                        });
                    }
                });
            });

            // EDIT TASK - SUBMIT UPDATE
            $('#form-edit-task').on('submit', function(e) {
                e.preventDefault();
                stopAutoRefresh(); // Hentikan auto-refresh
                let taskId = $('#edit-id').val();
                let formData = $(this).serialize();
                
                // Debug: Log data yang akan dikirim
                console.log('Updating task with datetime:', {
                    id: taskId,
                    tanggal_mulai: $('#edit-tanggal_mulai').val(),
                    tanggal_selesai: $('#edit-tanggal_selesai').val(),
                    serialized: formData
                });
                
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Memperbarui...');
                $submitBtn.prop('disabled', true);
                $.ajax({
                    url: "/api/tugas/" + taskId,
                    type: "PUT",
                    data: formData,
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            console.log('Task updated successfully:', res.data);
                            $('#modalEditTask').modal('hide');
                            // Reload immediately
                            setTimeout(function() {
                                loadTasks(true);
                            }, 100);
                            startAutoRefresh(); // Mulai lagi auto-refresh
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorText = '<ul class="list-unstyled">';
                        for (const [key, value] of Object.entries(errors)) {
                            errorText += `<li><i class="fas fa-times-circle text-danger mr-1"></i> ${value[0]}</li>`;
                        }
                        errorText += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorText || 'Terjadi kesalahan saat memperbarui data'
                        });
                    },
                    complete: function() {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // TASK DETAIL
            $(document).on('click', '.btn-detail', function() {
                let taskId = $(this).data('id');
                Swal.fire({
                    title: 'Memuat data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: "/api/tugas/" + taskId,
                    type: "GET",
                    success: function(res) {
                        if(res.success) {
                            let t = res.data;
                            let statusBadge = '';
                            let statusClass = '';
                            if(t.status === 'approved') {
                                statusBadge = 'Disetujui';
                                statusClass = 'success';
                            } else if(t.status === 'submitted') {
                                statusBadge = 'Menunggu Persetujuan';
                                statusClass = 'info';
                            } else if(t.status === 'rejected') {
                                statusBadge = 'Ditolak';
                                statusClass = 'danger';
                            } else {
                                statusBadge = 'Pending';
                                statusClass = 'warning';
                            }
                            let detailHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i> Karyawan</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">${t.user.nama}</div>
                                            <div class="small text-gray-500">${t.user.email}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-id-badge mr-1"></i> Jabatan</h6>
                                    <div>
                                        ${t.user.jabatan === 'Admin' ? '<span class="badge badge-success">Admin</span>' : '<span class="badge badge-warning">Karyawan</span>'}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-tasks mr-1"></i> Deskripsi Tugas</h6>
                                    <div class="p-3 bg-light rounded">${t.tugas}</div>
                                </div>
                                ${t.priority ? `
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-flag mr-1"></i> Prioritas</h6>
                                    <span class="badge badge-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'secondary'}">
                                        ${t.priority === 'high' ? 'Tinggi' : t.priority === 'medium' ? 'Sedang' : 'Rendah'}
                                    </span>
                                </div>
                                ` : ''}
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-calendar-alt mr-1"></i> Periode Tugas</h6>
                                    <div class="p-3 bg-light rounded">
                                        <div class="mb-2">
                                            <small class="text-muted">Mulai:</small>
                                            <div class="font-weight-bold text-info">${formatDateTime(t.tanggal_mulai)}</div>
                                        </div>
                                        <div>
                                            <small class="text-muted">Selesai:</small>
                                            <div class="font-weight-bold text-dark">${formatDateTime(t.tanggal_selesai)}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-info-circle mr-1"></i> Status</h6>
                                    <span class="badge badge-${statusClass}">${statusBadge}</span>
                                </div>
                                ${t.catatan ? `
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-sticky-note mr-1"></i> Catatan</h6>
                                    <div class="p-3 bg-light rounded">${t.catatan}</div>
                                </div>
                                ` : ''}
                                ${t.tugas_file ? `
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-file mr-1"></i> File Tugas</h6>
                                    <a href="/storage/${t.tugas_file}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                </div>
                                ` : ''}
                                ${t.karyawan_file ? `
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600"><i class="fas fa-file-upload mr-1"></i> File Karyawan</h6>
                                    <a href="/storage/${t.karyawan_file}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                            $('#task-detail-content').html(detailHtml);
                            $('#modalDetailTask').modal('show');
                            Swal.close();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat memuat data tugas'
                        });
                    }
                });
            });

            // Date validation
            $('input[name="tanggal_mulai"]').on('change', function() {
                let startDate = $(this).val();
                let endDateInput = $(this).closest('form').find('input[name="tanggal_selesai"]');
                endDateInput.attr('min', startDate);
                
                // If end date is before start date, reset it
                if (endDateInput.val() && endDateInput.val() < startDate) {
                    endDateInput.val('');
                }
            });

            $('input[name="tanggal_selesai"]').on('change', function() {
                let endDate = $(this).val();
                let startDate = $(this).closest('form').find('input[name="tanggal_mulai"]').val();
                
                if (startDate && endDate < startDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai'
                    });
                    $(this).val('');
                }
            });

            // --- INISIALISASI ---
            loadTasks(); // Load awal
            loadEmployees(); // Load employees untuk filter dan modal
            startAutoRefresh(); // Mulai auto-refresh

            // BULK ACTIONS
            let selectedTasks = [];

            function updateBulkActionsBar() {
                if (selectedTasks.length > 0) {
                    $('#selected-count').text(selectedTasks.length);
                    $('#bulk-actions-bar').fadeIn();
                } else {
                    $('#bulk-actions-bar').fadeOut();
                }
            }

            // Select all checkbox
            $(document).on('change', '#select-all-tasks', function() {
                let isChecked = $(this).prop('checked');
                $('.task-checkbox').prop('checked', isChecked);
                
                selectedTasks = [];
                if (isChecked) {
                    $('.task-checkbox').each(function() {
                        selectedTasks.push({
                            id: $(this).data('id'),
                            status: $(this).data('status')
                        });
                    });
                }
                updateBulkActionsBar();
            });

            // Individual checkbox
            $(document).on('change', '.task-checkbox', function() {
                let taskId = $(this).data('id');
                let taskStatus = $(this).data('status');
                
                if ($(this).prop('checked')) {
                    selectedTasks.push({ id: taskId, status: taskStatus });
                } else {
                    selectedTasks = selectedTasks.filter(t => t.id !== taskId);
                }
                
                // Update select all checkbox
                let totalCheckboxes = $('.task-checkbox').length;
                let checkedCheckboxes = $('.task-checkbox:checked').length;
                $('#select-all-tasks').prop('checked', totalCheckboxes === checkedCheckboxes);
                
                updateBulkActionsBar();
            });

            // Clear selection
            $('#clear-selection').on('click', function() {
                selectedTasks = [];
                $('.task-checkbox, #select-all-tasks').prop('checked', false);
                updateBulkActionsBar();
            });

            // Bulk Approve
            $('#bulk-approve').on('click', function() {
                let submittedTasks = selectedTasks.filter(t => t.status === 'submitted');
                
                if (submittedTasks.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Tidak ada tugas dengan status "Menunggu Persetujuan" yang dipilih'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Approve ' + submittedTasks.length + ' Tugas?',
                    text: 'Anda akan menyetujui ' + submittedTasks.length + ' tugas sekaligus',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        stopAutoRefresh();
                        let promises = submittedTasks.map(task => {
                            return $.ajax({
                                url: "/api/tugas/" + task.id + "/approve",
                                type: "POST",
                                cache: false
                            });
                        });
                        
                        Promise.all(promises).then(() => {
                            selectedTasks = [];
                            $('.task-checkbox, #select-all-tasks').prop('checked', false);
                            updateBulkActionsBar();
                            loadTasks(true);
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: submittedTasks.length + ' tugas berhasil disetujui',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }).catch(() => {
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat approve tugas'
                            });
                        });
                    }
                });
            });

            // Bulk Reject
            $('#bulk-reject').on('click', function() {
                let submittedTasks = selectedTasks.filter(t => t.status === 'submitted');
                
                if (submittedTasks.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Tidak ada tugas dengan status "Menunggu Persetujuan" yang dipilih'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Reject ' + submittedTasks.length + ' Tugas?',
                    html: `<p>Anda akan menolak ${submittedTasks.length} tugas sekaligus</p>
                           <textarea id="bulk-reject-catatan" class="form-control" rows="3" placeholder="Catatan penolakan (opsional)"></textarea>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Reject Semua!',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        return $('#bulk-reject-catatan').val() || 'Ditolak oleh admin';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        stopAutoRefresh();
                        let catatan = result.value;
                        let promises = submittedTasks.map(task => {
                            return $.ajax({
                                url: "/api/tugas/" + task.id + "/reject",
                                type: "POST",
                                data: { catatan: catatan },
                                cache: false
                            });
                        });
                        
                        Promise.all(promises).then(() => {
                            selectedTasks = [];
                            $('.task-checkbox, #select-all-tasks').prop('checked', false);
                            updateBulkActionsBar();
                            loadTasks(true);
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: submittedTasks.length + ' tugas berhasil ditolak',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }).catch(() => {
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat reject tugas'
                            });
                        });
                    }
                });
            });

            // Bulk Delete
            $('#bulk-delete').on('click', function() {
                if (selectedTasks.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Tidak ada tugas yang dipilih'
                    });
                    return;
                }
                
                let taskCount = selectedTasks.length; // Store count before clearing
                
                Swal.fire({
                    title: 'Hapus ' + taskCount + ' Tugas?',
                    html: `<p>Anda akan menghapus ${taskCount} tugas sekaligus</p><p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        stopAutoRefresh();
                        let promises = selectedTasks.map(task => {
                            return $.ajax({
                                url: "/api/tugas/" + task.id,
                                type: "DELETE",
                                cache: false
                            });
                        });
                        
                        Promise.all(promises).then(() => {
                            selectedTasks = [];
                            $('.task-checkbox, #select-all-tasks').prop('checked', false);
                            updateBulkActionsBar();
                            loadTasks(true);
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: taskCount + ' tugas berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }).catch(() => {
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus tugas'
                            });
                        });
                    }
                });
            });

            // APPROVE TASK
            $(document).on('click', '.btn-approve', function() {
                let taskId = $(this).data('id');
                let taskNama = $(this).data('nama');
                
                Swal.fire({
                    title: 'Setujui Tugas?',
                    html: `Anda akan menyetujui tugas dari <strong>${taskNama}</strong>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Setujui!',
                    cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        stopAutoRefresh();
                        $.ajax({
                            url: "/api/tugas/" + taskId + "/approve",
                            type: "POST",
                            cache: false,
                            success: function(res) {
                                if (res.success) {
                                    loadTasks(true);
                                    startAutoRefresh();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Disetujui!',
                                        text: res.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                startAutoRefresh();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyetujui tugas'
                                });
                            }
                        });
                    }
                });
            });

            // REJECT TASK - OPEN MODAL
            $(document).on('click', '.btn-reject', function() {
                let taskId = $(this).data('id');
                let taskNama = $(this).data('nama');
                let taskTugas = $(this).data('tugas');
                
                $('#reject-task-id').val(taskId);
                $('#reject-task-nama').text(taskNama);
                $('#reject-task-tugas').text(taskTugas);
                $('#reject-catatan').val('');
                $('#modalRejectTask').modal('show');
            });

            // REJECT TASK - SUBMIT
            $('#form-reject-task').on('submit', function(e) {
                e.preventDefault();
                stopAutoRefresh();
                
                let taskId = $('#reject-task-id').val();
                let catatan = $('#reject-catatan').val();
                
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menolak...');
                $submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: "/api/tugas/" + taskId + "/reject",
                    type: "POST",
                    data: { catatan: catatan },
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            $('#modalRejectTask').modal('hide');
                            setTimeout(function() {
                                loadTasks(true);
                            }, 100);
                            startAutoRefresh();
                            Swal.fire({
                                icon: 'success',
                                title: 'Ditolak!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat menolak tugas';
                        if (xhr.responseJSON?.errors?.catatan) {
                            errorMsg = xhr.responseJSON.errors.catatan[0];
                        } else if (xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                        startAutoRefresh();
                    }
                });
            });
        });
    </script>
@endsection
