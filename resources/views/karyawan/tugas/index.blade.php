@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks text-primary mr-2"></i>
                Data Tugas Saya
            </h1>
            <div>
                {{-- Desktop Buttons --}}
                <div class="d-none d-md-inline-block">
                    <a href="{{route('tugas.trash')}}" class="btn btn-sm btn-secondary shadow-sm">
                        <i class="fas fa-trash-alt fa-sm text-white-50 mr-1"></i> Sampah
                    </a>
                    <a href="{{route('tugas.excel')}}" data-export-type="Excel"
                        class="btn btn-sm btn-success shadow-sm ml-1">
                        <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                    </a>
                    <a href="{{route('tugas.pdf')}}" data-export-type="PDF" class="btn btn-sm btn-danger shadow-sm ml-1">
                        <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Export PDF
                    </a>
                </div>
                {{-- Mobile Dropdown Menu --}}
                <div class="dropdown d-md-none mt-2">
                    <button class="btn btn-primary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownMenuActions"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog mr-1"></i> Aksi
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuActions">
                        <a class="dropdown-item" href="{{route('tugas.trash')}}">
                            <i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i> Sampah
                        </a>
                        <a class="dropdown-item" href="{{route('tugas.excel')}}" data-export-type="Excel">
                            <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-success"></i> Export Excel
                        </a>
                        <a class="dropdown-item" href="{{route('tugas.pdf')}}" data-export-type="PDF">
                            <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-danger"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row">
            <x-stat-card title="Total Tugas" icon="fa-tasks" color="primary" id="total-tugas" />
            <x-stat-card title="Selesai" icon="fa-check-circle" color="success" id="total-selesai" />
            <x-stat-card title="Proses" icon="fa-spinner" color="info" id="total-proses" />
            <x-stat-card title="Pending" icon="fa-clock" color="warning" id="total-pending" />
        </div>
        <!-- Task Table -->
        <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.4s">
            <div class="card-header premium-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Tugas Saya</h6>
                <div>
                    <button class="btn btn-outline-primary btn-sm shadow-sm mr-2" id="toggle-selection-mode"
                        title="Mode Seleksi">
                        <i class="far fa-check-square mr-1"></i> Pilih
                    </button>
                    <button class="btn btn-info btn-sm shadow-sm" type="button" data-toggle="collapse"
                        data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="collapse mb-4" id="filterCollapse">
                    <div class="card card-body bg-light border-0">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-gray-700">Filter Status:</label>
                                <select id="filter-status" class="form-control form-control-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="submitted">Menunggu Persetujuan</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-gray-700">Filter Prioritas:</label>
                                <select id="filter-priority" class="form-control form-control-sm">
                                    <option value="">Semua Prioritas</option>
                                    <option value="high">Tinggi</option>
                                    <option value="medium">Sedang</option>
                                    <option value="low">Rendah</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-gray-700">&nbsp;</label>
                                <button id="reset-filter" class="btn btn-secondary btn-sm btn-block">
                                    <i class="fas fa-undo mr-1"></i> Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bulk Actions -->
                <div id="bulk-actions-bar" class="card bg-gradient-primary text-white mb-3 shadow-lg border-0"
                    style="display: none; border-radius: 0.5rem;">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="mb-2 mb-md-0 font-weight-bold">
                                <i class="fas fa-check-square mr-2"></i> <span id="selected-count">0</span> tugas dipilih
                            </div>
                            <div>
                                <button class="btn btn-light btn-sm mr-1 text-danger font-weight-bold shadow-sm"
                                    id="bulk-delete">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                                <button class="btn btn-outline-light btn-sm" id="clear-selection">
                                    <i class="fas fa-times-circle mr-1"></i> Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="taskTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-premium-header">
                                <th class="text-center col-no d-none d-md-table-cell" style="width: 50px;">No</th>
                                <th class="text-center col-checkbox" style="width: 40px;">
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="select-all-tasks">
                                        <label class="custom-control-label" for="select-all-tasks"></label>
                                    </div>
                                </th>
                                <th class="col-tugas">Tugas</th>
                                <th class="text-center col-periode" style="width: 150px;">Periode</th>
                                <th class="text-center col-file-admin" style="width: 80px;">File Admin</th>
                                <th class="text-center col-file-employee" style="width: 80px;">File Saya</th>
                                <th class="text-center col-status" style="width: 120px;">Status</th>
                                <th class="text-center col-aksi" style="width: 100px;">Aksi</th>
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
    <!-- Upload File Modal -->
    <div class="modal fade" id="modalUploadFile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-upload mr-2"></i> Upload File Tugas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-upload-file" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="upload-task-id" name="task_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-file text-gray-600 mr-1"></i> Pilih File
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="file" required>
                                <label class="custom-file-label" for="customFile">Pilih file</label>
                            </div>
                            <small class="form-text text-muted">Semua format file didukung (max 50MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Task Detail Modal -->
    <div class="modal fade" id="modalDetailTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom bg-white py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light p-2 rounded-circle mr-3">
                            <i class="fas fa-tasks text-primary"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-dark font-weight-bold mb-0">Detail Tugas</h5>
                            <p class="text-xs text-muted mb-0">Informasi lengkap mengenai pelaksanaan tugas</p>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="task-detail-content">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <!-- Comments Section -->
                <div class="modal-body border-top pt-3" id="comments-section" style="display: none;">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-comments mr-2"></i>Komentar
                        <span class="badge badge-primary ml-1" id="comments-count">0</span>
                    </h6>
                    <!-- Comments List -->
                    <div id="comments-list" style="max-height: 250px; overflow-y: auto;">
                        <div class="text-center text-muted py-3" id="no-comments">
                            <i class="fas fa-comment-slash fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada komentar</p>
                        </div>
                    </div>
                    <!-- Add Comment Form -->
                    <form id="add-comment-form" class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="comment-input" placeholder="Tulis komentar..."
                                maxlength="1000">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="submit-comment">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .card {
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        /* Fix table layout */
        #taskTable {
            table-layout: fixed !important;
            width: 100% !important;
        }

        #taskTable tbody td {
            vertical-align: middle !important;
            padding: 0.6rem 0.5rem !important;
        }

        .btn {
            border-radius: 0.35rem;
            font-weight: 500;
        }

        .badge {
            padding: 0.4em 0.65em;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .icon-circle {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .task-link {
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none !important;
            display: block;
            width: 100%;
        }

        .task-link:hover .task-title {
            color: var(--primary) !important;
            text-decoration: underline !important;
        }

        .task-title {
            color: #4e73df;
            font-weight: 600;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Card Styling */
        .premium-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Table Styling */
        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            white-space: nowrap;
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        /* Fix table column widths */
        #taskTable {
            table-layout: fixed !important;
            width: 100% !important;
        }

        #taskTable tbody td {
            vertical-align: middle !important;
            padding: 0.6rem 0.5rem !important;
        }

        /* Column Specifics */
        .col-no {
            width: 50px !important;
            text-align: center !important;
        }

        .col-checkbox {
            width: 40px !important;
            text-align: center !important;
            display: none;
        }

        #taskTable.selection-mode .col-checkbox {
            display: table-cell !important;
        }

        .col-tugas {
            /* Auto width */
        }

        .col-periode {
            width: 150px !important;
            text-align: center !important;
        }

        .col-file-admin {
            width: 80px !important;
            text-align: center !important;
        }

        .col-file-employee {
            width: 80px !important;
            text-align: center !important;
        }

        .col-status {
            width: 120px !important;
            text-align: center !important;
        }

        .col-aksi {
            width: 100px !important;
            text-align: center !important;
        }

        /* Custom File Input */
        .custom-file-label::after {
            content: "Browse";
        }

        /* Badge Styling */
        .badge {
            padding: 0.4em 0.65em;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.7rem;
            display: inline-block;
        }

        /* Preview Toggle Styles */
        .preview-toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 0.6rem 0.8rem;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
            margin-top: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .preview-toggle-container:hover {
            border-color: #e2e8f0;
            background: #f1f5f9;
        }

        .btn-preview-toggle {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
        }

        .btn-preview-toggle:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-preview-toggle.active {
            background: #f0fdf4 !important;
            color: #166534 !important;
            border-color: #bbf7d0 !important;
        }

        .preview-collapse {
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            max-height: 0;
            opacity: 0;
        }

        .preview-collapse.show {
            max-height: 2000px;
            opacity: 1;
            margin-bottom: 1.5rem;
        }

        /* Dark Mode Adjustments */
        html.dark-mode .preview-toggle-container {
            background: #1e293b;
            border-color: #334155;
        }

        html.dark-mode .btn-preview-toggle {
            background: #0f172a;
            border-color: #334155;
            color: #94a3b8;
        }

        html.dark-mode .btn-preview-toggle.active {
            background: #064e3b !important;
            color: #6ee7b7 !important;
            border-color: #065f46 !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/export-handler.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Custom File Input Label
            $(".custom-file-input").on("change", function () {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
            // Initialize DataTable
            const taskTable = $('#taskTable').DataTable({
                scrollX: true,
                responsive: false, // Force horizontal scroll instead of collapsing
                autoWidth: false, // Respect explicit widths
                pageLength: 10,
                language: {
                    url: '{{ asset("json/datatables-indonesian.json") }}'
                },
                columnDefs: [
                    { targets: 0, orderable: false, className: 'col-no d-none d-md-table-cell text-center', width: '50px' },
                    { targets: 1, orderable: false, className: 'col-checkbox text-center', width: '40px' },
                    { targets: 2, className: 'col-tugas' }, // Flexible width
                    { targets: 3, className: 'col-periode text-center', width: '160px' },
                    { targets: 4, className: 'col-file-admin text-center', width: '100px' },
                    { targets: 5, className: 'col-file-employee text-center', width: '100px' },
                    { targets: 6, className: 'col-status text-center', width: '130px' },
                    { targets: 7, className: 'col-aksi text-center', width: '120px', orderable: false }
                ],
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                initComplete: function () {
                    $('.dataTables_filter input').addClass('form-control-sm');
                    $('.dataTables_filter input').attr('placeholder', 'Cari tugas...');
                    $('.dataTables_filter label').contents().filter(function () {
                        return this.nodeType === 3;
                    }).replaceWith('<i class="fas fa-search fa-sm"></i>');
                }
            });
            // Connect Global Search Input to DataTable
            $('#globalSearchInput').on('keyup', function () {
                taskTable.search(this.value).draw();
            });
            // Helper function to format datetime
            function formatDateTime(dateString) {
                if (!dateString) return '-';
                try {
                    let date;
                    if (dateString.includes('T')) {
                        date = new Date(dateString);
                    } else {
                        date = new Date(dateString.replace(' ', 'T'));
                    }
                    if (isNaN(date.getTime())) return dateString;
                    const day = date.getDate();
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${day} ${month} ${year}, ${hours}:${minutes}`;
                } catch (error) {
                    return dateString;
                }
            }
            // Load Tasks
            function loadTasks() {
                $.ajax({
                    url: "/api/tugas",
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    success: function (res) {
                        if (res.success) {
                            taskTable.clear();
                            let stats = { total: 0, selesai: 0, proses: 0, pending: 0 };
                            let dataRows = [];
                            $.each(res.data, function (index, task) {
                                stats.total++;
                                let statusBadge = '';
                                let statusClass = '';
                                let actionBtn = '';
                                if (task.status === 'approved') {
                                    statusBadge = 'Disetujui'; statusClass = 'success'; stats.selesai++;
                                    iconClass = 'fa-check-circle';
                                    actionBtn = `<button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail"><i class="fas fa-eye"></i></button>`;
                                } else if (task.status === 'submitted') {
                                    statusBadge = 'Menunggu Persetujuan'; statusClass = 'info'; stats.proses++;
                                    iconClass = 'fa-clock';
                                    actionBtn = `<button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail"><i class="fas fa-eye"></i></button>`;
                                } else if (task.status === 'rejected') {
                                    statusBadge = 'Ditolak'; statusClass = 'danger'; stats.proses++;
                                    iconClass = 'fa-times-circle';
                                    actionBtn = `<button class="btn btn-primary btn-sm shadow-sm btn-upload" data-id="${task.id}" title="Upload Ulang"><i class="fas fa-upload"></i></button>
                                                                                                                                                                                                          <button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail"><i class="fas fa-eye"></i></button>`;
                                } else {
                                    statusBadge = 'Pending'; statusClass = 'warning'; stats.pending++;
                                    iconClass = 'fa-exclamation-circle';
                                    actionBtn = `<button class="btn btn-primary btn-sm shadow-sm btn-upload" data-id="${task.id}" title="Upload"><i class="fas fa-upload"></i></button>
                                                                                                                                                                                                          <button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail"><i class="fas fa-eye"></i></button>`;
                                }
                                const formattedStartDate = formatDateTime(task.tanggal_mulai);
                                const formattedEndDate = formatDateTime(task.tanggal_selesai);
                                // Checkbox cell for selection mode
                                const checkboxCell = `<div class="custom-control custom-checkbox d-inline-block">
                                                                                                                                                                                                                <input type="checkbox" class="custom-control-input task-checkbox" id="task-${task.id}" data-id="${task.id}">
                                                                                                                                                                                                                <label class="custom-control-label" for="task-${task.id}"></label>
                                                                                                                                                                                                              </div>`;
                                dataRows.push([
                                    index + 1,
                                    checkboxCell,
                                    `<a href="javascript:void(0)" class="task-link btn-detail" data-id="${task.id}">
                                                                                                                                                                                                <div class="task-title" title="${task.tugas}">${task.tugas}</div>
                                                                                                                                                                                            </a>`,
                                    `<div class="text-center small">
                                                                                                                                                                                                <div class="text-nowrap"><i class="fas fa-calendar-alt text-gray-400 mr-1"></i> ${formattedStartDate}</div>
                                                                                                                                                                                                <div class="text-nowrap"><i class="fas fa-calendar-check text-gray-400 mr-1"></i> ${formattedEndDate}</div>
                                                                                                                                                                                            </div>`,
                                    `<div class="text-center">${task.tugas_file ? `<a href="/storage/${task.tugas_file}" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fas fa-file-download"></i></a>` : '<span class="text-muted small">-</span>'}</div>`,
                                    `<div class="text-center">${task.karyawan_file ? `<a href="/storage/${task.karyawan_file}" target="_blank" class="btn btn-sm btn-outline-success shadow-sm"><i class="fas fa-check-double mr-1"></i> Terkirim</a>` : '<span class="text-muted small">Belum ada</span>'}</div>`,
                                    `<span class="status-pill-premium status-pill-${statusClass}"><i class="fas ${iconClass}"></i> ${statusBadge}</span>`,
                                    actionBtn
                                ]);
                            });
                            taskTable.rows.add(dataRows).draw();
                            $('#total-tugas').text(stats.total);
                            $('#total-selesai').text(stats.selesai);
                            $('#total-proses').text(stats.proses);
                            $('#total-pending').text(stats.pending);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error loading tasks:', xhr);
                    }
                });
            }
            // Filter Logic
            $('#filter-status').on('change', function () {
                let status = $(this).val();
                let table = $('#taskTable').DataTable();
                table.column(6).search(status ? (
                    status === 'pending' ? 'Pending' :
                        status === 'submitted' ? 'Menunggu Persetujuan' :
                            status === 'approved' ? 'Disetujui' :
                                status === 'rejected' ? 'Ditolak' : ''
                ) : '').draw();
            });
            $('#reset-filter').on('click', function () {
                $('#filter-status, #filter-priority').val('');
                let table = $('#taskTable').DataTable();
                table.search('').columns().search('').draw();
            });
            // UPLOAD FILE
            $(document).on('click', '.btn-upload', function () {
                let taskId = $(this).data('id');
                $('#upload-task-id').val(taskId);
                $('#modalUploadFile').modal('show');
            });
            $('#form-upload-file').on('submit', function (e) {
                e.preventDefault();
                let taskId = $('#upload-task-id').val();
                let formData = new FormData(this);
                const $submitBtn = $(this).find('button[type="submit"]');
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> Mengupload...');
                $.ajax({
                    url: "/api/tugas/" + taskId + "/upload",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#modalUploadFile').modal('hide');
                        $('#form-upload-file')[0].reset();
                        $(".custom-file-label").html("Pilih file");
                        loadTasks();
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html('Upload');
                    }
                });
            });
            // TASK DETAIL
            $(document).on('click', '.btn-detail', function () {
                let taskId = $(this).data('id');
                Swal.showLoading();
                $.ajax({
                    url: "/api/tugas/" + taskId,
                    type: "GET",
                    success: function (res) {
                        if (res.success) {
                            let t = res.data;
                            let statusBadge = t.status === 'approved' ? '<span class="status-pill-premium status-approved">Disetujui</span>' :
                                t.status === 'submitted' ? '<span class="status-pill-premium status-submitted">Menunggu Persetujuan</span>' :
                                    t.status === 'rejected' ? '<span class="status-pill-premium status-rejected">Ditolak</span>' :
                                        '<span class="status-pill-premium status-pending">Pending</span>';
                            // Helper to generate file preview
                            function getFilePreview(fileName, label) {
                                if (!fileName) return '';
                                let fileExt = fileName.split('.').pop().toLowerCase();
                                let filePath = '/storage/' + fileName;
                                let previewId = 'preview-' + Math.random().toString(36).substr(2, 9);
                                let content = '';
                                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                                    content = `<img src="${filePath}" class="img-fluid " style="max-height: 500px; width: 100%; object-fit: contain; background: #0f172a;" alt="Preview">`;
                                } else if (fileExt === 'pdf') {
                                    content = `<div class="embed-responsive embed-responsive-16by9" style="height: 500px;"><iframe class="embed-responsive-item" src="${filePath}" allowfullscreen></iframe></div>`;
                                }
                                if (!content) return '';
                                return `
                                                                                                                                                    <div class="row align-items-center mb-3 animate-in">
                                                                                                                                                        <div class="col-12">
                                                                                                                                                            <div class="p-3 bg-light rounded border-dashed d-flex align-items-center justify-content-between">
                                                                                                                                                                <div class="d-flex align-items-center">
                                                                                                                                                                    <div class="bg-primary-light p-2 rounded mr-3">
                                                                                                                                                                        <i class="fas fa-eye text-primary"></i>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <div class="small font-weight-bold text-dark">${label}</div>
                                                                                                                                                                        <div class="text-xs text-muted">Pratinjau Dokumen</div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <button class="btn btn-preview-toggle" type="button" data-toggle-id="${previewId}">
                                                                                                                                                                    <i class="fas fa-eye"></i> <span>Lihat Pratinjau</span>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            <div id="${previewId}" class="preview-collapse">
                                                                                                                                                                <div class="preview-card mt-3 shadow-sm">
                                                                                                                                                                    <div class="preview-header ">
                                                                                                                                                             <span><i class="fas fa-search mr-2"></i>Pratinjau File</span>
                                                                                                                                                             <div class="mode-label">Mode Tampilan</div>
                                                                                                                                                         </div>
                                                                                                                                                                    <div class="preview-body p-0 bg-dark">
                                                                                                                                                                        ${content}
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                `;
                            }
                            // Generate Previews
                            let adminPreview = getFilePreview(t.tugas_file, 'File Instruksi Admin');
                            let employeePreview = getFilePreview(t.karyawan_file, 'Laporan Saya');
                            let filePreview = adminPreview + employeePreview;
                            function getFileCard(fileName, label, colorClass, iconClass) {
                                if (!fileName) return '';
                                return `
                                                                                                                                                    <div class="attachment-card-v2 p-3 rounded border bg-white shadow-sm hover-lift mb-3 animate-in">
                                                                                                                                                        <div class="attachment-icon-v2 bg-light text-${colorClass} mr-3">
                                                                                                                                                            <i class="${iconClass}"></i>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                                                                                                                                                            <div class="small font-weight-bold text-dark text-truncate">${label}</div>
                                                                                                                                                            <div class="text-xs text-muted text-truncate">${fileName.split('/').pop()}</div>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="ml-2">
                                                                                                                                                            <a href="/storage/${fileName}" target="_blank" class="btn btn-icon-only btn-light shadow-sm" title="Download">
                                                                                                                                                                <i class="fas fa-download text-primary"></i>
                                                                                                                                                            </a>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                `;
                            }
                            let adminFileCard = getFileCard(t.tugas_file, 'Instruksi Admin', 'primary', 'fas fa-file-alt');
                            let employeeFileCard = getFileCard(t.karyawan_file, 'Laporan Saya', 'success', 'fas fa-file-upload');
                            let detailHtml = `
                                                                                                                                                <div class="px-4 py-4">
                                                                                                                                                    <!-- Task Header Info -->
                                                                                                                                                    <div class="mb-4 pb-3 border-bottom">
                                                                                                                                                        <div class="d-flex align-items-center mb-2">
                                                                                                                                                            <span class="badge badge-primary-light text-primary px-2 py-1 mr-2" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">ID: #${t.id}</span>
                                                                                                                                                            ${t.priority ? `
                                                                                                                                                                <span class="badge badge-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'secondary'}-light text-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'secondary'} px-2 py-1" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                                                                                                                                                                    <i class="fas fa-flag mr-1"></i> Prioritas ${t.priority === 'high' ? 'Tinggi' : t.priority === 'medium' ? 'Sedang' : 'Rendah'}
                                                                                                                                                                </span>
                                                                                                                                                            ` : ''}
                                                                                                                                                        </div>
                                                                                                                                                        <h4 class="font-weight-bold text-dark mb-1" title="${t.tugas}">${t.tugas.length > 100 ? t.tugas.substring(0, 100) + '...' : t.tugas}</h4>
                                                                                                                                                <p class="text-xs text-muted mb-0"><i class="far fa-calendar-alt mr-1"></i> Dibuat pada ${formatDateTime(t.created_at)}</p>
                                                                                                                                            </div>
                                                                                                                                            <!-- Top Section: Creator & Status -->
                                                                                                                                            <div class="row mb-5">
                                                                                                                                                <div class="col-md-7 mb-3 mb-md-0">
                                                                                                                                                    <span class="detail-section-label">Pembuat Tugas</span>
                                                                                                                                                    <div class="info-card p-3 d-flex align-items-center h-100">
                                                                                                                                                        <div class="mr-3 position-relative">
                                                                                                                                                            <img class="img-profile rounded-circle shadow-sm border" 
                                                                                                                                                                 style="width: 54px; height: 54px; object-fit: cover;"
                                                                                                                                                                 src="${t.creator ? (t.creator.profile_photo_path ? '/storage/' + t.creator.profile_photo_path : '{{ asset('sbadmin2/img/undraw_profile.svg') }}') : '{{ asset('sbadmin2/img/undraw_profile.svg') }}'
                                }"
                                                                                                                                                                 alt = "Profile" >
                                        <div class="position-absolute" style="bottom: -2px; right: -2px;">
                                            <div class="bg-primary rounded-circle border border-white" style="width: 12px; height: 12px;"></div>
                                        </div>
                                                                                                                                                        </div >
                                        <div class="overflow-hidden">
                                            <div class="font-weight-bold text-dark text-truncate" style="font-size: 1.05rem;">${t.creator ? t.creator.nama : 'N/A'}</div>
                                            <div class="small text-muted text-truncate mb-1">${t.creator ? t.creator.email : '-'}</div>
                                            <span class="badge badge-primary-light text-primary border text-xs px-2 py-1" style="border-radius: 4px;">Pemberi Tugas</span>
                                        </div>
                                                                                                                                                    </div >
                                                                                                                                                </div >
                                        <div class="col-md-5">
                                            <span class="detail-section-label">Periode & Status</span>
                                            <div class="info-card p-3 h-100">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="text-xs font-weight-bold text-muted"><i class="far fa-clock mr-1"></i> Mulai</div>
                                                    <div class="small font-weight-bold text-dark">${formatDateTime(t.tanggal_mulai)}</div>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                                    <div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-check mr-1"></i> Selesai</div>
                                                    <div class="small font-weight-bold text-dark">${formatDateTime(t.tanggal_selesai)}</div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-2">
                                                    <div class="text-xs font-weight-bold text-muted">Status</div>
                                                    ${statusBadge}
                                                </div>
                                            </div>
                                        </div>
                                                                                                                                            </div >
                                                                                                                                            <div class="row mb-5">
                                                                                                                                                <div class="col-12">
                                                                                                                                                    <span class="detail-section-label">Deskripsi Tugas</span>
                                                                                                                                                    <div class="description-box shadow-sm">${(t.tugas ? t.tugas.trim() : '') || '<span class="text-muted italic">Tidak ada deskripsi tugas.</span>'}</div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div class="row mb-2">
                                                                                                                                                <div class="col-12">
                                                                                                                                                    <span class="detail-section-label">File Lampiran</span>
                                                                                                                                                    <div class="row">
                                                                                                                                                        <div class="col-md-6 mb-3 mb-md-0">
                                                                                                                                                            ${adminFileCard ? adminFileCard : `
                                                                                                                                                                <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                                                                                                                                                    <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file instruksi</div>
                                                                                                                                                                </div>
                                                                                                                                                            `}
                                                                                                                                                        </div>
                                                                                                                                                        <div class="col-md-6">
                                                                                                                                                            ${employeeFileCard ? employeeFileCard : `
                                                                                                                                                                <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                                                                                                                                                    <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file laporan</div>
                                                                                                                                                                </div>
                                                                                                                                                            `}
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                        ${t.catatan ? `
                                                                                                                                            <div class="row mt-3">
                                                                                                                                                <div class="col-12">
                                                                                                                                                    <div class="p-3 border-left border-danger bg-danger-light rounded shadow-sm" style="border-left-width: 4px !important;">
                                                                                                                                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Catatan Penolakan</div>
                                                                                                                                                        <div class="text-dark small"><i class="fas fa-exclamation-circle mr-2"></i>${t.catatan}</div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>` : ''
                                }
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                ${filePreview ? `
                                                                                                                                                        <div class="preview-section">
                                                                                                                                                            <div class="preview-header-wrapper d-flex align-items-center mb-3">
                                                                                                                                                                <span class="detail-section-label mb-0 mr-auto">Pratinjau File</span>
                                                                                                                                                            </div>
                                                                                                                                                            ${filePreview}
                                                                                                                                                        </div>
                                                                                                                                                    ` : ''}
                                            </div>
                                        </div>
                                                                                                                                        </div >
                                        `;
                            $('#task-detail-content').html(detailHtml);
                            $('#modalDetailTask').modal('show');
                            Swal.close();
                            // Load comments
                            loadComments(taskId);
                        }
                    }
                });
            });
            loadTasks();
            // Selection Mode Variables
            let selectionMode = false;
            let selectedTasks = [];
            // Toggle Selection Mode
            $('#toggle-selection-mode').on('click', function () {
                selectionMode = !selectionMode;
                if (selectionMode) {
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                    $('#taskTable').addClass('selection-mode');
                    $('#bulk-actions-bar').slideDown();
                } else {
                    $(this).removeClass('btn-primary').addClass('btn-outline-primary');
                    $('#taskTable').removeClass('selection-mode');
                    $('#bulk-actions-bar').slideUp();
                    clearSelection();
                }
                // No need for loadTasks() here as the class handles visibility
                taskTable.columns.adjust().draw();
            });
            // Clear Selection
            function clearSelection() {
                selectedTasks = [];
                $('#select-all-tasks').prop('checked', false);
                $('.task-checkbox').prop('checked', false);
                updateSelectedCount();
            }
            $('#clear-selection').on('click', function () {
                clearSelection();
            });
            // Update Selected Count
            function updateSelectedCount() {
                $('#selected-count').text(selectedTasks.length);
            }
            // Select All Tasks
            $(document).on('change', '#select-all-tasks', function () {
                const isChecked = $(this).prop('checked');
                $('.task-checkbox').prop('checked', isChecked);
                selectedTasks = [];
                if (isChecked) {
                    $('.task-checkbox').each(function () {
                        selectedTasks.push($(this).data('id'));
                    });
                }
                updateSelectedCount();
            });
            // Individual Task Checkbox
            $(document).on('change', '.task-checkbox', function () {
                const taskId = $(this).data('id');
                if ($(this).prop('checked')) {
                    if (!selectedTasks.includes(taskId)) {
                        selectedTasks.push(taskId);
                    }
                } else {
                    selectedTasks = selectedTasks.filter(id => id !== taskId);
                }
                updateSelectedCount();
            });
            // Bulk Delete
            $('#bulk-delete').on('click', function () {
                if (selectedTasks.length === 0) {
                    Swal.fire('Perhatian', 'Pilih tugas terlebih dahulu', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Hapus Tugas?',
                    text: `${selectedTasks.length} tugas akan dipindahkan ke sampah.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let completed = 0;
                        selectedTasks.forEach(taskId => {
                            $.ajax({
                                url: `/api/tugas/${taskId}`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function () {
                                    completed++;
                                    if (completed === selectedTasks.length) {
                                        Swal.fire('Berhasil!', 'Tugas dipindahkan ke sampah', 'success');
                                        clearSelection();
                                        loadTasks();
                                    }
                                }
                            });
                        });
                    }
                });
            });
            // ==================== COMMENTS FUNCTIONALITY ====================
            let currentTaskId = null;
            let replyingToId = null;
            function loadComments(taskId) {
                currentTaskId = taskId;
                replyingToId = null;
                $('#comments-section').show();
                $('#comments-list').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
                $.ajax({
                    url: `/api/tugas/${taskId}/comments`,
                    type: 'GET',
                    cache: false,
                    success: function (res) {
                        if (res.success) {
                            renderComments(res.data);
                        }
                    },
                    error: function () {
                        $('#comments-list').html('<div class="text-center text-danger py-3"><i class="fas fa-exclamation-circle"></i> Gagal memuat komentar</div>');
                    }
                });
            }
            function countAllComments(comments) {
                let count = comments.length;
                comments.forEach(c => {
                    if (c.replies && c.replies.length > 0) {
                        count += countAllComments(c.replies);
                    }
                });
                return count;
            }
            function renderComments(comments) {
                const totalCount = countAllComments(comments);
                $('#comments-count').text(totalCount);
                if (comments.length === 0) {
                    $('#comments-list').html(`
                                                                                                                                                                                                                    <div class="text-center text-muted py-3" id="no-comments">
                                                                                                                                                                                                                        <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                                                                                                                                                                                                        <p class="mb-0">Belum ada komentar</p>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                `);
                    return;
                }
                let html = renderCommentItems(comments, 0);
                $('#comments-list').html(html);
                $('#comments-list').scrollTop($('#comments-list')[0].scrollHeight);
            }
            function renderCommentItems(comments, depth) {
                let html = '';
                const marginLeft = depth > 0 ? (depth * 20) : 0;
                const bgClass = depth > 0 ? 'comment-reply' : '';
                comments.forEach(function (c) {
                    const initial = c.user.nama.charAt(0).toUpperCase();
                    const avatarHtml = c.user.photo
                        ? `<img src="${c.user.photo}" alt="${c.user.nama}">`
                        : initial;
                    html += `
                                                                                                                                                                                                                    <div class="comment-item ${bgClass}" data-id="${c.id}" style="margin-left: ${marginLeft}px;">
                                                                                                                                                                                                                        <div class="comment-header">
                                                                                                                                                                                                                            <div class="comment-avatar">${avatarHtml}</div>
                                                                                                                                                                                                                            <div class="comment-meta">
                                                                                                                                                                                                                                <div class="comment-author">${c.user.nama}</div>
                                                                                                                                                                                                                                <div class="comment-time">${c.created_at_human}</div>
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            <div class="comment-actions">
                                                                                                                                                                                                                                <button class="btn btn-sm btn-link text-primary btn-reply-comment p-0 mr-2" 
                                                                                                                                                                                                                                        data-id="${c.id}" data-name="${c.user.nama}" title="Balas">
                                                                                                                                                                                                                                    <i class="fas fa-reply"></i>
                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                ${c.is_own ? `
                                                                                                                                                                                                                                    <button class="btn btn-sm btn-link text-danger btn-delete-comment p-0" data-id="${c.id}" title="Hapus">
                                                                                                                                                                                                                                        <i class="fas fa-trash-alt"></i>
                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                ` : ''}
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                        <div class="comment-content">${c.content}</div>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                `;
                    // Render nested replies recursively
                    if (c.replies && c.replies.length > 0 && depth < 2) {
                        html += renderCommentItems(c.replies, depth + 1);
                    }
                });
                return html;
            }
            // Handle reply button click
            $(document).on('click', '.btn-reply-comment', function () {
                const commentId = $(this).data('id');
                const userName = $(this).data('name');
                replyingToId = commentId;
                // Update input placeholder and show indicator
                $('#comment-input')
                    .attr('placeholder', `Balas ke ${userName}...`)
                    .focus();
                // Add cancel reply button if not already present
                if ($('#cancel-reply').length === 0) {
                    $('#add-comment-form .input-group').prepend(`
                                                                                                                                                                                                                    <div class="input-group-prepend">
                                                                                                                                                                                                                        <button type="button" class="btn btn-outline-secondary" id="cancel-reply" title="Batal balas">
                                                                                                                                                                                                                            <i class="fas fa-times"></i>
                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                `);
                }
            });
            // Cancel reply
            $(document).on('click', '#cancel-reply', function () {
                replyingToId = null;
                $('#comment-input').attr('placeholder', 'Tulis komentar...').val('');
                $(this).parent().remove();
            });
            // Add comment form submit (with reply support)
            $('#add-comment-form').on('submit', function (e) {
                e.preventDefault();
                const content = $('#comment-input').val().trim();
                if (!content || !currentTaskId) return;
                const $btn = $('#submit-comment');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                const postData = { content: content };
                if (replyingToId) {
                    postData.parent_id = replyingToId;
                }
                $.ajax({
                    url: `/api/tugas/${currentTaskId}/comments`,
                    type: 'POST',
                    data: postData,
                    success: function (res) {
                        if (res.success) {
                            $('#comment-input').val('').attr('placeholder', 'Tulis komentar...');
                            $('#cancel-reply').parent().remove();
                            replyingToId = null;
                            loadComments(currentTaskId);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 419 || xhr.status === 401) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sesi Berakhir',
                                text: 'Sesi anda telah berakhir. Halaman akan dimuat ulang.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message || 'Gagal menambah komentar'
                            });
                        }
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                    }
                });
            });
            // Delete comment
            $(document).on('click', '.btn-delete-comment', function () {
                const commentId = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Komentar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/tugas/comments/${commentId}`,
                            type: 'DELETE',
                            success: function () {
                                loadComments(currentTaskId);
                            },
                            error: function (xhr) {
                                if (xhr.status === 404) {
                                    // If 404, comment is already deleted, just reload
                                    loadComments(currentTaskId);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: xhr.responseJSON?.message || 'Gagal menghapus komentar'
                                    });
                                }
                            }
                        });
                    }
                });
            });
            // Reset comments when modal closes
            $('#modalDetailTask').on('hidden.bs.modal', function () {
                currentTaskId = null;
                replyingToId = null;
                $('#comments-section').hide();
                $('#comments-list').html('');
                $('#comment-input').val('').attr('placeholder', 'Tulis komentar...');
                $('#cancel-reply').parent().remove();
            });
            // Hook into detail modal show
            $('#modalDetailTask').on('shown.bs.modal', function () {
                const taskId = $('.btn-detail:focus').data('id') || currentTaskId;
                if (taskId) {
                    loadComments(taskId);
                }
            });
            // Preview Toggle Logic
            $(document).on('click', '.btn-preview-toggle', function () {
                const targetId = $(this).data('toggle-id');
                const $target = $('#' + targetId);
                const $btn = $(this);
                const $icon = $btn.find('i');
                const $text = $btn.find('span');
                const $containerIcon = $btn.closest('.preview-toggle-container').find('.text-primary');
                if ($target.hasClass('show')) {
                    $target.removeClass('show');
                    $btn.removeClass('active');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    $text.text('Tampilkan');
                    $containerIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $target.addClass('show');
                    $btn.addClass('active');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    $text.text('Sembunyikan');
                    $containerIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e0;
        }

        /* Table Layout Fix */
        #taskTable {
            table-layout: fixed !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .col-tugas {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .task-title {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: normal !important;
            color: #4e73df;
            font-weight: 500;
            display: block;
            width: 100%;
        }

        .task-link:hover .task-title {
            text-decoration: underline;
            color: #224abe;
        }

        /* Detail Modal Styles - Premium */
        .detail-section-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #64748b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .detail-section-label::after {
            content: "";
            height: 1px;
            flex: 1;
            background: radial-gradient(circle, #e2e8f0 0%, transparent 100%);
        }

        .info-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .info-card:hover {
            border-color: #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .description-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            line-height: 1.5 !important;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #4e73df;
            white-space: pre-line;
            word-wrap: break-word;
            overflow-wrap: break-word;
            position: relative;
        }

        html.dark-mode .description-box {
            background: rgba(30, 41, 59, 0.5);
            border-color: #334155;
            color: #cbd5e0;
        }

        .status-pill-premium {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-submitted {
            background: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        html.dark-mode .status-approved {
            background: rgba(22, 101, 52, 0.2);
            color: #4ade80;
            border-color: rgba(74, 222, 128, 0.2);
        }

        html.dark-mode .status-submitted {
            background: rgba(7, 89, 133, 0.2);
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.2);
        }

        html.dark-mode .status-rejected {
            background: rgba(153, 27, 27, 0.2);
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.2);
        }

        html.dark-mode .status-pending {
            background: rgba(146, 64, 14, 0.2);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.2);
        }

        .attachment-card-v2 {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }

        .attachment-card-v2:hover {
            background: #f8fafc;
            border-color: #cbd5e0;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .attachment-icon-v2 {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .preview-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .preview-header {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-header span {
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
        }

        .preview-header .mode-label {
            font-size: 0.7rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 800;
        }

        /* Comment Styles - Unified Design */
        .comment-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
        }

        .comment-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .comment-item.comment-reply {
            background: #ffffff;
            border-left: 3px solid #4e73df;
            border-radius: 0 10px 10px 0;
        }

        .comment-item .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .comment-item .comment-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .comment-item .comment-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .comment-item .comment-meta {
            flex: 1;
            min-width: 0;
        }

        .comment-item .comment-author {
            font-weight: 600;
            font-size: 13px;
            color: #3a3b45;
        }

        .comment-item .comment-time {
            font-size: 11px;
            color: #858796;
        }

        .comment-item .comment-actions {
            display: flex;
            align-items: center;
            opacity: 0.4;
            transition: opacity 0.2s;
        }

        .comment-item:hover .comment-actions {
            opacity: 1;
        }

        .comment-item .comment-content {
            font-size: 14px;
            color: #5a5c69;
            line-height: 1.5;
            word-wrap: break-word;
        }

        /* Dark Mode Support */
        html.dark-mode .detail-section-label {
            color: #94a3b8;
        }

        html.dark-mode .info-card,
        html.dark-mode .preview-card,
        html.dark-mode .attachment-card-v2 {
            background: #1e293b;
            border-color: #334155;
        }

        html.dark-mode .attachment-card-v2:hover {
            background: #334155;
            border-color: #475569;
        }

        html.dark-mode .attachment-icon-v2 {
            background: #0f172a !important;
        }

        html.dark-mode .info-card .text-dark,
        html.dark-mode .attachment-card-v2 .text-dark {
            color: #f1f5f9 !important;
        }

        html.dark-mode .preview-header {
            background: #1e293b;
            border-bottom: 1px solid #334155;
        }

        html.dark-mode .preview-body {
            background: #0f172a !important;
        }

        /* Dark Mode Support for Comments */
        html.dark-mode .comment-item {
            background: #2d3748 !important;
        }

        html.dark-mode .comment-item.comment-reply {
            background: #1a202c !important;
            border-left-color: #667eea !important;
        }

        html.dark-mode .comment-item .comment-author {
            color: #e2e8f0 !important;
        }

        html.dark-mode .comment-item .comment-time {
            color: #a0aec0 !important;
        }

        html.dark-mode .comment-item .comment-content {
            color: #cbd5e0 !important;
        }

        html.dark-mode .comment-item .comment-avatar {
            background: linear-gradient(135deg, #667eea 0%, #4c51bf 100%);
        }

        /* Preview Toggle Styles */
        .preview-toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 0.6rem 0.8rem;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
            margin-top: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .preview-toggle-container:hover {
            border-color: #e2e8f0;
            background: #f1f5f9;
        }

        .btn-preview-toggle {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
        }

        .btn-preview-toggle:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-preview-toggle.active {
            background: #f0fdf4 !important;
            color: #166534 !important;
            border-color: #bbf7d0 !important;
        }

        .preview-collapse {
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            max-height: 0;
            opacity: 0;
        }

        .preview-collapse.show {
            max-height: 2000px;
            opacity: 1;
            margin-bottom: 1.5rem;
        }

        /* Utility Classes - Premium System */
        .bg-primary-light {
            background-color: rgba(78, 115, 223, 0.1) !important;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .bg-success-light {
            background-color: rgba(34, 197, 94, 0.1) !important;
        }

        .text-success {
            color: #16a34a !important;
        }

        .bg-danger-light {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        .text-danger {
            color: #dc2626 !important;
        }

        .bg-warning-light {
            background-color: rgba(245, 158, 11, 0.1) !important;
        }

        .text-warning {
            color: #d97706 !important;
        }

        .bg-info-light {
            background-color: rgba(8, 145, 178, 0.1) !important;
        }

        .text-info {
            color: #0891b2 !important;
        }

        .bg-secondary-light {
            background-color: #f1f5f9 !important;
        }

        .opacity-5 {
            opacity: 0.5;
        }

        .border-dashed {
            border: 1px dashed #e2e8f0;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Overrides for Premium Detail */
        html.dark-mode .detail-section-label {
            color: #94a3b8;
        }

        html.dark-mode .info-card,
        html.dark-mode .preview-card,
        html.dark-mode .attachment-card-v2 {
            background: #1e293b;
            border-color: #334155;
        }

        html.dark-mode .attachment-card-v2:hover {
            background: #334155;
            border-color: #475569;
        }

        html.dark-mode .attachment-icon-v2 {
            background: #0f172a !important;
        }

        html.dark-mode .border-dashed {
            border-color: #334155;
        }

        html.dark-mode .bg-primary-light {
            background: rgba(78, 115, 223, 0.2) !important;
        }

        html.dark-mode .bg-success-light {
            background: rgba(34, 197, 9 green, 0.2) !important;
        }

        html.dark-mode .bg-danger-light {
            background: rgba(239, 68, 68, 0.2) !important;
        }

        html.dark-mode .bg-warning-light {
            background: rgba(245, 158, 11, 0.2) !important;
        }

        html.dark-mode .bg-info-light {
            background: rgba(8, 145, 178, 0.2) !important;
        }

        html.dark-mode .info-card .text-dark,
        html.dark-mode .info-card .text-muted:not(.badge),
        html.dark-mode .attachment-card-v2 .text-dark,
        html.dark-mode .attachment-card-v2 .text-muted {
            color: #f1f5f9 !important;
        }

        html.dark-mode .attachment-card-v2 .text-xs.text-muted {
            color: #94a3b8 !important;
        }
    </style>
@endsection