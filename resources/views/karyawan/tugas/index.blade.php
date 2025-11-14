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
                <a href="{{route('tugas.pdf')}}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
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
                <h6 class="m-0 font-weight-bold text-primary">Daftar Tugas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="taskTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Tugas</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">File Admin</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
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
            <div class="modal-content">
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
                            <label class="form-label">
                                <i class="fas fa-file text-gray-600 mr-1"></i> Pilih File
                            </label>
                            <input type="file" name="file" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.png" required>
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG (max 2MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i> Upload
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
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
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
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // Base URL for assets
        const baseUrl = "{{ url('storage') }}";
        
        // DataTable instance
        let taskTable;
        
        // Initialize DataTable function
        function initDataTable() {
            if (taskTable) {
                taskTable.destroy();
            }
            
            taskTable = $('#taskTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: { url: "{{ asset('assets/datatables/i18n/id.json') }}?v={{ time() }}" 
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
        }
        
        $(document).ready(function() {
            // Load tasks data
            function loadTasks() {
                $.ajax({
                    url: "/api/tugas",
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    beforeSend: function() {
                        // Destroy DataTable if exists
                        if (taskTable) {
                            taskTable.destroy();
                            taskTable = null;
                        }
                        
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
                    },
                    success: function(res) {
                        if (res.success) {
                            let tbody = '';
                            let totalTugas = 0;
                            let totalSelesai = 0;
                            let totalProses = 0;
                            let totalPending = 0;

                            $.each(res.data, function(index, task) {
                                totalTugas++;

                                // Status badge
                                let statusBadge = '';
                                let statusClass = '';
                                let actionBtn = '';

                                if(task.status === 'approved') {
                                    statusBadge = 'Disetujui';
                                    statusClass = 'success';
                                    totalSelesai++;
                                    actionBtn = `<button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                     </button>`;
                                } else if(task.status === 'submitted') {
                                    statusBadge = 'Menunggu Persetujuan';
                                    statusClass = 'info';
                                    totalProses++;
                                    actionBtn = `<button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                     </button>`;
                                } else if(task.status === 'rejected') {
                                    statusBadge = 'Ditolak';
                                    statusClass = 'danger';
                                    totalProses++;
                                    actionBtn = `<button class="btn btn-primary btn-sm shadow-sm btn-upload" data-id="${task.id}" title="Upload Ulang">
                                        <i class="fas fa-upload"></i>
                                     </button>
                                     <button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                     </button>`;
                                } else {
                                    statusBadge = 'Pending';
                                    statusClass = 'warning';
                                    totalPending++;
                                    actionBtn = `<button class="btn btn-primary btn-sm shadow-sm btn-upload" data-id="${task.id}" title="Upload">
                                        <i class="fas fa-upload"></i>
                                     </button>
                                     <button class="btn btn-info btn-sm shadow-sm btn-detail" data-id="${task.id}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                     </button>`;
                                }

                                tbody += `
                                <tr id="row-${task.id}">
                                    <td class="text-center">${index+1}</td>
                                    <td>${task.tugas}</td>
                                    <td class="text-center">
                                        <span class="badge badge-dark">${task.tanggal_mulai}</span>
                                        <i class="fas fa-arrow-right text-gray-400 mx-1"></i>
                                        <span class="badge badge-dark">${task.tanggal_selesai}</span>
                                    </td>
                                    <td class="text-center">
                                        ${task.tugas_file
                                    ? `<a href="${baseUrl}/${task.tugas_file}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file"></i>
                                               </a>`
                                    : '<span class="text-muted">-</span>'}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-${statusClass}">${statusBadge}</span>
                                    </td>
                                    <td class="text-center">
                                        ${actionBtn}
                                    </td>
                                </tr>
                            `;
                            });

                            // Update table HTML and reinitialize DataTable
                            $('#task-body').html(tbody);
                            initDataTable();

                            // Update statistics
                            $('#total-tugas').text(totalTugas);
                            $('#total-selesai').text(totalSelesai);
                            $('#total-proses').text(totalProses);
                            $('#total-pending').text(totalPending);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal memuat data tugas';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        $('#task-body').html(`
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <p class="text-danger">${errorMsg}</p>
                                <button class="btn btn-sm btn-primary" onclick="location.reload()">
                                    <i class="fas fa-sync-alt mr-1"></i> Coba Lagi
                                </button>
                            </td>
                        </tr>
                    `);
                        initDataTable();
                    }
                });
            }

            // Initial load
            loadTasks();

            // UPLOAD FILE
            $(document).on('click', '.btn-upload', function() {
                let taskId = $(this).data('id');
                $('#upload-task-id').val(taskId);
                $('#modalUploadFile').modal('show');
            });

            $('#form-upload-file').on('submit', function(e) {
                e.preventDefault();
                let taskId = $('#upload-task-id').val();
                let formData = new FormData(this);

                // Show loading state
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Mengupload...');
                $submitBtn.prop('disabled', true);

                $.ajax({
                    url: "/api/tugas/" + taskId + "/upload",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            $('#modalUploadFile').modal('hide');
                            $('#form-upload-file')[0].reset();
                            // Reload immediately
                            setTimeout(function() {
                                loadTasks();
                            }, 100);
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
                            html: errorText || 'Terjadi kesalahan saat mengupload file'
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

                // Show loading
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

                            // Status badge
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
                                        <h6 class="text-sm text-gray-600">Deskripsi Tugas</h6>
                                        <div class="p-3 bg-light rounded">${t.tugas}</div>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-sm text-gray-600">Periode Tugas</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-info">
                                                    <i class="fas fa-calendar-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">${t.tanggal_mulai}</div>
                                                <div class="small text-gray-500">s/d ${t.tanggal_selesai}</div>
                                            </div>
                                        </div>
                                    </div>
                                    ${t.catatan ? `
                                        <div class="mb-3">
                                            <h6 class="text-sm text-gray-600">Catatan</h6>
                                            <div class="p-3 bg-warning-light rounded">${t.catatan}</div>
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-sm text-gray-600">Status</h6>
                                        <span class="badge badge-${statusClass}">${statusBadge}</span>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-sm text-gray-600">File Admin</h6>
                                        ${t.tugas_file
                                ? `<a href="${baseUrl}/${t.tugas_file}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-file mr-1"></i> Lihat File
                                                </a>`
                                : '<span class="text-muted">Tidak ada file</span>'}
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-sm text-gray-600">File Saya</h6>
                                        ${t.karyawan_file
                                ? `<a href="${baseUrl}/${t.karyawan_file}" target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fas fa-file mr-1"></i> Lihat File
                                                </a>`
                                : '<span class="text-muted">Belum upload file</span>'}
                                    </div>
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

            // RESET FORM WHEN MODAL IS CLOSED
            $('#modalUploadFile').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
            });
        });
    </script>
@endsection
