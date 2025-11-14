@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog text-primary mr-2"></i>
                Manajemen User
            </h1>
            <div>
                <a href="{{route('users.excel')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                </a>
                <a href="{{route('users.pdf')}}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm ml-1">
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
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total User</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-users">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Admin</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-admin">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Karyawan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-karyawan">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">User Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-aktif">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data User Sistem</h6>
                <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddUser">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah User Baru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="userTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="user-body">
                        <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-add-user">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user text-gray-600 mr-1"></i> Nama Lengkap
                            </label>
                            <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama lengkap" required>
                            <div class="invalid-feedback" id="nama-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="contoh@email.com" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-briefcase text-gray-600 mr-1"></i> Jabatan
                            </label>
                            <select name="jabatan" class="form-control form-control-lg" required>
                                <option value="" selected disabled>-- Pilih Jabatan --</option>
                                <option value="Admin">Administrator</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                            <div class="invalid-feedback" id="jabatan-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock text-gray-600 mr-1"></i> Password
                            </label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Minimal 8 karakter" required>
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock text-gray-600 mr-1"></i> Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Ulangi password" required>
                            <div class="invalid-feedback" id="password_confirmation-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit mr-2"></i> Edit User
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-user">
                    @csrf
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user text-gray-600 mr-1"></i> Nama Lengkap
                            </label>
                            <input type="text" id="edit-nama" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama lengkap" required>
                            <div class="invalid-feedback" id="edit-nama-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                            </label>
                            <input type="email" id="edit-email" name="email" class="form-control form-control-lg" placeholder="contoh@email.com" required>
                            <div class="invalid-feedback" id="edit-email-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-briefcase text-gray-600 mr-1"></i> Jabatan
                            </label>
                            <select id="edit-jabatan" name="jabatan" class="form-control form-control-lg" required>
                                <option value="Admin">Administrator</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                            <div class="invalid-feedback" id="edit-jabatan-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock text-gray-600 mr-1"></i> Password (opsional)
                            </label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Kosongkan jika tidak ingin mengubah">
                            <div class="invalid-feedback" id="edit-password-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock text-gray-600 mr-1"></i> Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Ulangi password baru">
                            <div class="invalid-feedback" id="edit-password_confirmation-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt mr-1"></i> Update User
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
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        /* Fix for modal backdrop issue */
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
        
        /* SweetAlert2 Button Fix - Prevent Bootstrap Override */
        .swal2-confirm.btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }
        .swal2-confirm.btn-danger:hover,
        .swal2-confirm.btn-danger:focus {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5) !important;
        }
        .swal2-cancel.btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
        }
        .swal2-cancel.btn-secondary:hover,
        .swal2-cancel.btn-secondary:focus {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            // Indonesian language configuration for DataTables
            const indonesianLanguage = {
                "processing": "Sedang memproses...",
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "loadingRecords": "Sedang memuat...",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "emptyTable": "Tidak ada data yang tersedia",
                "paginate": {
                    "first": "Pertama",
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya",
                    "last": "Terakhir"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom secara ascending",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom secara descending"
                }
            };

            // Variable to store DataTable instance
            let userTable;

            // Function to initialize DataTable
            function initializeDataTable() {
                userTable = $('#userTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    language: indonesianLanguage,
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    initComplete: function() {
                        $('.dataTables_filter input').addClass('form-control-sm');
                        $('.dataTables_filter input').attr('placeholder', 'Cari user...');
                        $('.dataTables_filter label').contents().filter(function() {
                            return this.nodeType === 3;
                        }).replaceWith('<i class="fas fa-search fa-sm"></i>');
                    }
                });
            }

            // Load users data
            function loadUsers() {
                $.ajax({
                    url: "/api/users",
                    type: "GET",
                    dataType: "json",
                    cache: false, // Disable caching
                    beforeSend: function() {
                        $('#user-body').html(`
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat data user...</p>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(res) {
                        if (res.success) {
                            let tbody = '';
                            let totalAdmin = 0;
                            let totalKaryawan = 0;
                            let totalAktif = 0;

                            $.each(res.data, function(index, user) {
                                let jabatanBadge = user.jabatan === 'Admin'
                                    ? '<span class="badge badge-success shadow-sm">Admin</span>'
                                    : '<span class="badge badge-warning shadow-sm">Karyawan</span>';

                                let statusBadge = '';
                                if(user.jabatan === 'Admin') {
                                    statusBadge = '<span class="badge badge-info shadow-sm">Administrator</span>';
                                    totalAdmin++;
                                } else {
                                    statusBadge = user.is_tugas
                                        ? '<span class="badge badge-success shadow-sm">Sudah Ditugaskan</span>'
                                        : '<span class="badge badge-danger shadow-sm">Belum Ditugaskan</span>';
                                    totalKaryawan++;
                                    if(user.is_tugas) totalAktif++;
                                }

                                tbody += `
                                    <tr id="row-${user.id}">
                                        <td class="text-center">${index+1}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-primary">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">${user.nama}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-dark shadow-sm">${user.email}</span>
                                        </td>
                                        <td class="text-center">${jabatanBadge}</td>
                                        <td class="text-center">${statusBadge}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-warning btn-sm shadow-sm btn-edit" data-id="${user.id}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm shadow-sm btn-delete" data-id="${user.id}" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });

                            // Check if DataTable exists and destroy first
                            if ($.fn.DataTable.isDataTable('#userTable')) {
                                userTable.destroy();
                            }

                            // Update DOM
                            $('#user-body').html(tbody);

                            // Update statistics with animation
                            $('#total-users').fadeOut(200, function() {
                                $(this).text(res.data.length).fadeIn(200);
                            });
                            $('#total-admin').fadeOut(200, function() {
                                $(this).text(totalAdmin).fadeIn(200);
                            });
                            $('#total-karyawan').fadeOut(200, function() {
                                $(this).text(totalKaryawan).fadeIn(200);
                            });
                            $('#total-aktif').fadeOut(200, function() {
                                $(this).text(totalAktif).fadeIn(200);
                            });

                            // Reinitialize DataTable
                            initializeDataTable();
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal memuat data user';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        $('#user-body').html(`
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <p class="text-danger">${errorMsg}</p>
                                    <button class="btn btn-sm btn-primary" onclick="loadUsers()">
                                        <i class="fas fa-sync-alt mr-1"></i> Coba Lagi
                                    </button>
                                </td>
                            </tr>
                        `);
                    }
                });
            }

            // Initial load
            loadUsers();

            // CREATE USER
            $('#form-add-user').on('submit', function(e) {
                e.preventDefault();

                // Reset error states
                $(this).find('.form-control').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');

                let formData = new FormData(this);

                // Show loading state
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...');
                $submitBtn.prop('disabled', true);

                $.ajax({
                    url: "/api/users",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(res) {
                        if (res.success) {
                            $('#modalAddUser').modal('hide');
                            $('#form-add-user')[0].reset();

                            // Reload data immediately
                            setTimeout(function() {
                                loadUsers();
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
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;

                            // Show error messages
                            if (errors.nama) {
                                $('#form-add-user input[name="nama"]').addClass('is-invalid');
                                $('#nama-error').text(errors.nama[0]);
                            }

                            if (errors.email) {
                                $('#form-add-user input[name="email"]').addClass('is-invalid');
                                $('#email-error').text(errors.email[0]);
                            }

                            if (errors.jabatan) {
                                $('#form-add-user select[name="jabatan"]').addClass('is-invalid');
                                $('#jabatan-error').text(errors.jabatan[0]);
                            }

                            if (errors.password) {
                                $('#form-add-user input[name="password"]').addClass('is-invalid');
                                $('#password-error').text(errors.password[0]);
                            }

                            if (errors.password_confirmation) {
                                $('#form-add-user input[name="password_confirmation"]').addClass('is-invalid');
                                $('#password_confirmation-error').text(errors.password_confirmation[0]);
                            }
                        } else {
                            // Other errors
                            let errorMsg = 'Terjadi kesalahan saat menyimpan data';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg
                            });
                        }
                    },
                    complete: function() {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // DELETE USER
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const userId = $(this).data('id');
                const $row = $('#row-' + userId);
                const userEmail = $row.find('td:nth-child(3)').text().trim();
                // Pada baris ini, kita mengambil elemen nama user dari baris tabel. 
                // Penggunaan 'div' di sini karena pada kolom ke-2 (td:nth-child(2)) kemungkinan ada elemen <div>
                // yang membungkus nama user, sehingga untuk mendapatkan teks nama, kita seleksi elemen div di dalam td tersebut.
                const userName = $row.find('td:nth-child(2) div').first().text().trim();
                const userJabatan = $row.find('td:nth-child(4)').first().text().trim();

                Swal.fire({
                    title: 'Hapus User?',
                    html: `Apakah Anda yakin ingin menghapus dengan nama <strong>${userName}</strong> dan email <strong>${userEmail}</strong> dengan jabatan <strong>${userJabatan}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/api/users/" + userId,
                            type: "DELETE",
                            cache: false,
                            success: function(res) {
                                if (res.success) {
                                    // Reload data immediately
                                    loadUsers();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: res.message,
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: res.message || 'Terjadi kesalahan'
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Terjadi kesalahan saat menghapus data';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });

            // EDIT USER - GET DATA
            $(document).on('click', '.btn-edit', function(e) {
                e.preventDefault();
                const userId = $(this).data('id');

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
                    url: "/api/users/" + userId,
                    type: "GET",
                    success: function(res) {
                        if(res.success) {
                            const user = res.data;
                            $('#edit-id').val(user.id);
                            $('#edit-nama').val(user.nama);
                            $('#edit-email').val(user.email);
                            $('#edit-jabatan').val(user.jabatan);

                            // Reset error states
                            $('#form-edit-user .form-control').removeClass('is-invalid');
                            $('#form-edit-user .invalid-feedback').text('');

                            $('#modalEditUser').modal('show');
                            Swal.close();
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Tidak dapat memuat data user';

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
            });

            // EDIT USER - SUBMIT UPDATE
            $('#form-edit-user').on('submit', function(e) {
                e.preventDefault();

                // Reset error states
                $(this).find('.form-control').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');

                const userId = $('#edit-id').val();

                // Create FormData object
                const formData = new FormData(this);

                // Check if password is empty and remove it from FormData
                const password = formData.get('password');
                const passwordConfirmation = formData.get('password_confirmation');

                if (!password || password.trim() === '') {
                    formData.delete('password');
                    formData.delete('password_confirmation');
                }

                // Show loading state
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Memperbarui...');
                $submitBtn.prop('disabled', true);

                $.ajax({
                    url: "/api/users/" + userId,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT' // Method spoofing for PUT
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#modalEditUser').modal('hide');

                            // Reload data immediately
                            setTimeout(function() {
                                loadUsers();
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
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;

                            // Show error messages
                            if (errors.nama) {
                                $('#form-edit-user input[name="nama"]').addClass('is-invalid');
                                $('#edit-nama-error').text(errors.nama[0]);
                            }

                            if (errors.email) {
                                $('#form-edit-user input[name="email"]').addClass('is-invalid');
                                $('#edit-email-error').text(errors.email[0]);
                            }

                            if (errors.jabatan) {
                                $('#form-edit-user select[name="jabatan"]').addClass('is-invalid');
                                $('#edit-jabatan-error').text(errors.jabatan[0]);
                            }

                            if (errors.password) {
                                $('#form-edit-user input[name="password"]').addClass('is-invalid');
                                $('#edit-password-error').text(errors.password[0]);
                            }

                            if (errors.password_confirmation) {
                                $('#form-edit-user input[name="password_confirmation"]').addClass('is-invalid');
                                $('#edit-password_confirmation-error').text(errors.password_confirmation[0]);
                            }
                        } else {
                            // Other errors
                            let errorMsg = 'Terjadi kesalahan saat memperbarui data';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg
                            });
                        }
                    },
                    complete: function() {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // RESET FORM WHEN MODAL IS CLOSED
            $('#modalAddUser, #modalEditUser').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');

                // Fix for modal backdrop issue
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

            // Fix for modal backdrop issue
            $(document).on('show.bs.modal', '.modal', function () {
                // Check if there's already a modal backdrop
                if ($('.modal-backdrop').length) {
                    $('.modal-backdrop').not(':first').remove();
                }
            });

            // Fix for buttons not clickable
            $(document).on('shown.bs.modal', function() {
                // Make sure modals are properly positioned
                $('.modal').css('z-index', '1050');
            });

            // Fix for dropdowns and buttons
            $(document).on('click', '[data-toggle="dropdown"]', function(e) {
                e.preventDefault();
                $(this).dropdown('toggle');
            });

            // Ensure all buttons are clickable
            $(document).on('click', '.btn', function(e) {
                if (!$(this).prop('disabled')) {
                    return true;
                }
                e.preventDefault();
                return false;
            });

            // Fix for DataTables buttons
            $(document).on('init.dt', function(e, settings) {
                var api = new $.fn.dataTable.Api(settings);
                $(api.table().container()).find('.btn').css('pointer-events', 'auto');
            });
        });
    </script>
@endsection
