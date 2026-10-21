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
                <a href="{{route('users.excel')}}" data-export-type="Excel"
                    class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                </a>
                <a href="{{route('users.pdf')}}" data-export-type="PDF"
                    class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm ml-1">
                    <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Export PDF
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <x-stat-card title="Total User" icon="fa-users" color="primary" id="total-users" />
            <x-stat-card title="Admin" icon="fa-user-shield" color="success" id="total-admin" />
            <x-stat-card title="Karyawan" icon="fa-user" color="info" id="total-karyawan" />
            <x-stat-card title="User Aktif" icon="fa-user-check" color="warning" id="total-aktif" />
        </div>

        <!-- User Table -->
        <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.4s">
            <div class="card-header premium-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data User Sistem</h6>
                <div>
                    @if(auth()->user()->isSuperAdmin())
                        <button class="btn btn-dark btn-sm shadow-sm mr-2" data-toggle="modal" data-target="#modalSuperAdmin"
                            id="btn-open-super-admin">
                            <i class="fas fa-shield-alt fa-sm text-white-50 mr-1"></i> Kelola Super Admin
                        </button>
                    @endif
                    <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddUser">
                        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah User Baru
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="userTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-premium-header">
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>User Profile</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 160px;">Aksi</th>
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

    @include('admin.user.partials.modal-create')
    @include('admin.user.partials.modal-edit')
    @if(auth()->user()->isSuperAdmin())
        @include('admin.user.partials.modal-super-admin')
    @endif
@endsection

@section('styles')
    <style>
        .premium-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            transform: translateY(-5px);
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        /* Enhanced Profile Link Styles */
        .user-profile-link:hover {
            background-color: #f8f9fc !important;
            text-decoration: none;
        }

        .user-profile-link:hover .text-primary {
            text-decoration: underline;
        }

        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* ... rest of styles ... */
        .form-control-lg {
            border-radius: 0.5rem;
            border: 1px solid #d1d3e2;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
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

@section('scripts')
    <script src="{{ asset('js/export-handler.js') }}"></script>
    <script>
        $(document).ready(function () {
            const currentId = {{ auth()->id() }};            // ID User yang sedang login
            const isSuperAdmin = {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }};  // Status Super Admin

            // ... (rest of the code)

            // Function to initialize DataTable
            function initializeDataTable() {
                userTable = $('#userTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    language: {
                        url: "{{ asset('json/datatables-indonesian.json') }}"
                    },
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    initComplete: function () {
                        $('.dataTables_filter input').addClass('form-control-sm');
                        $('.dataTables_filter input').attr('placeholder', 'Cari user...');
                        $('.dataTables_filter label').contents().filter(function () {
                            return this.nodeType === 3;
                        }).replaceWith('<i class="fas fa-search fa-sm"></i>');
                    }
                });
            }

            // Load users data
            function loadUsers() {
                $.ajax({
                    url: "/api/users?per_page=1000",
                    type: "GET",
                    dataType: "json",
                    cache: false, // Disable caching
                    beforeSend: function () {
                        $('#user-body').html(`
                                                                                                                                                                <tr>
                                                                                                                                                                    <td colspan="5" class="text-center py-4">
                                                                                                                                                                        <div class="spinner-border text-primary" role="status">
                                                                                                                                                                            <span class="sr-only">Loading...</span>
                                                                                                                                                                        </div>
                                                                                                                                                                        <p class="mt-2">Memuat data user...</p>
                                                                                                                                                                    </td>
                                                                                                                                                                </tr>
                                                                                                                                                            `);
                    },
                    success: function (res) {
                        if (res.success) {
                            let tbody = '';
                            let totalAdmin = 0;
                            let totalKaryawan = 0;
                            let totalAktif = 0;

                            $.each(res.data, function (index, user) {
                                // Super Admin badge logic
                                let superAdminBadge = user.is_super_admin ? '<span class="status-pill-premium status-pill-primary ml-1" title="Super Admin"><i class="fas fa-shield-alt"></i> SA</span>' : '';

                                let jabatanBadge = user.jabatan === 'Admin'
                                    ? `<span class="status-pill-premium status-pill-success">Admin</span> ${superAdminBadge}`
                                    : '<span class="status-pill-premium status-pill-warning">Karyawan</span>';

                                let isOnline = false;
                                if (user.last_seen) {
                                    const lastSeen = new Date(user.last_seen);
                                    const now = new Date();
                                    const diff = (now - lastSeen) / 1000 / 60; // difference in minutes
                                    if (diff < 5) isOnline = true;
                                }

                                let statusBadge = '';
                                if (user.jabatan === 'Admin') {
                                    statusBadge = '<span class="status-pill-premium status-pill-info"><i class="fas fa-user-shield"></i> Terverifikasi</span>';
                                    totalAdmin++;
                                } else {
                                    statusBadge = isOnline
                                        ? '<span class="status-pill-premium status-pill-success"><i class="fas fa-laptop-code"></i> Aktif</span>'
                                        : '<span class="status-pill-premium status-pill-secondary"><i class="fas fa-clock"></i> Idle</span>';
                                    totalKaryawan++;
                                    if (isOnline) totalAktif++;
                                }

                                let onlineStatus = isOnline
                                    ? '<span class="status-dot online"></span><small class="text-success font-weight-bold">Online</small>'
                                    : '<span class="status-dot offline"></span><small class="text-muted">Offline</small>';

                                // Determine if action buttons should be disabled
                                let isTargetAdmin = user.jabatan === 'Admin';
                                let canEdit = isSuperAdmin || !isTargetAdmin || user.id === currentId;
                                let canDelete = isSuperAdmin || !isTargetAdmin;

                                let actionDropdown = `
                                                <div class="dropdown no-arrow">
                                                    <button class="btn btn-sm btn-light btn-circle shadow-sm dropdown-toggle" type="button" 
                                                            id="userAction-${user.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v text-gray-600"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="userAction-${user.id}">
                                                        <div class="dropdown-header">Kelola User:</div>
                                                        <a class="dropdown-item btn-edit ${canEdit ? '' : 'disabled'}" href="#" data-id="${user.id}">
                                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Edit Profil
                                                        </a>
                                                        <a class="dropdown-item btn-reset-password ${canEdit ? '' : 'disabled'}" href="#" data-id="${user.id}">
                                                            <i class="fas fa-key fa-sm fa-fw mr-2 text-info"></i> Reset Password (WA)
                                                        </a>
                                                        ${user.id !== currentId && canDelete ? `
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item btn-delete text-danger" href="#" data-id="${user.id}">
                                                                <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Hapus User
                                                            </a>
                                                        ` : ''}
                                                    </div>
                                                </div>
                                            `;

                                // Row highlight for Super Admin
                                let rowClass = user.is_super_admin ? 'style="border-left: 4px solid #f6c23e;"' : '';

                                // Profile Photo Logic
                                let photoUrl = user.profile_photo_path ? '/storage/' + user.profile_photo_path : null;
                                let avatarHtml = photoUrl
                                    ? `<img class="img-profile rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #fff;" src="${photoUrl}" alt="Profile">`
                                    : `<div class="icon-circle bg-primary shadow-sm text-white" style="width: 42px; height: 42px;"><i class="fas fa-user"></i></div>`;

                                tbody += `
                                                <tr id="row-${user.id}" ${rowClass}>
                                                    <td class="text-center align-middle">${index + 1}</td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center p-1 user-profile-link" 
                                                             style="cursor: pointer; transition: all 0.2s; border-radius: 8px;" 
                                                             onclick="window.location.href='/profile/${user.id}'" 
                                                             title="Klik untuk lihat profil lengkap ${user.nama}">
                                                            <div class="mr-3">
                                                                ${avatarHtml}
                                                            </div>
                                                            <div class="min-width-0">
                                                                <div class="font-weight-bold text-primary text-truncate mb-0" style="max-width: 180px;">
                                                                    ${user.nama} 
                                                                </div>
                                                                <div class="small text-muted text-truncate" style="max-width: 180px;">${user.email}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">${jabatanBadge}</td>
                                                    <td class="text-center align-middle">
                                                        <div class="d-flex flex-column align-items-center">
                                                            ${statusBadge}
                                                            <div class="d-flex align-items-center mt-1">
                                                                ${onlineStatus}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        ${actionDropdown}
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
                            $('#total-users').fadeOut(200, function () {
                                $(this).text(res.data.length).fadeIn(200);
                            });
                            $('#total-admin').fadeOut(200, function () {
                                $(this).text(totalAdmin).fadeIn(200);
                            });
                            $('#total-karyawan').fadeOut(200, function () {
                                $(this).text(totalKaryawan).fadeIn(200);
                            });
                            $('#total-aktif').fadeOut(200, function () {
                                $(this).text(totalAktif).fadeIn(200);
                            });

                            // Reinitialize DataTable
                            initializeDataTable();
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Gagal memuat data user';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        $('#user-body').html(`
                                                                                                                                                                <tr>
                                                                                                                                                                    <td colspan="5" class="text-center py-4">
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
            $('#form-add-user').on('submit', function (e) {
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
                    success: function (res) {
                        if (res.success) {
                            $('#modalAddUser').modal('hide');
                            $('#form-add-user')[0].reset();

                            // Reload data immediately
                            setTimeout(function () {
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
                    error: function (xhr) {
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
                    complete: function () {
                        $submitBtn.html(originalText);
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // RESET PASSWORD VIA WHATSAPP logic
            $(document).on('click', '.btn-reset-password', function (e) {
                e.preventDefault();
                const userId = $(this).data('id');
                const $row = $('#row-' + userId);
                const userName = $row.find('td:nth-child(2) div.font-weight-bold').text().trim();

                Swal.fire({
                    title: 'Reset Password & Kirim ke WA?',
                    text: `Buat link reset password untuk ${userName} dan kirim via WhatsApp?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Buat Link',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#36b9cc'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: `/api/users/${userId}/generate-reset-token`,
                            type: 'POST',
                            success: function (res) {
                                Swal.close();
                                if (res.success) {
                                    const link = res.data.reset_link;
                                    const noHp = res.data.no_hp;
                                    const name = res.data.user;

                                    // Check valid phone number
                                    if (!noHp) {
                                        Swal.fire('Info', 'User ini belum memiliki nomor HP (WhatsApp). Link telah dibuat tapi tidak bisa otomatis buka WA.', 'warning');
                                        // Still show the link to copy
                                        prompt('Salin Link Reset Password:', link);
                                        return;
                                    }

                                    // Format Message
                                    const message = `Halo ${name},%0A%0ABerikut adalah link untuk mereset password akun Hakai Anda:%0A${link}%0A%0Link ini valid untuk 60 menit.%0AJangan bagikan link ini ke siapapun.`;

                                    // Use user's phone number as is (assuming it is stored correctly, or add formatting logic if needed)
                                    // Ideally format 08 -> 628
                                    let formattedHp = noHp.replace(/\D/g, '');
                                    if (formattedHp.startsWith('0')) {
                                        formattedHp = '62' + formattedHp.substring(1);
                                    }

                                    const waUrl = `https://wa.me/${formattedHp}?text=${message}`;

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Link Dibuat!',
                                        text: 'Membuka WhatsApp...',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.open(waUrl, '_blank');
                                    });
                                }
                            },
                            error: function (xhr) {
                                let msg = 'Gagal membuat token.';
                                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });

            // DELETE USER
            $(document).on('click', '.btn-delete', function (e) {
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
                        confirmButton: 'btn btn-danger px-4 mr-2',
                        cancelButton: 'btn btn-outline-dark px-4'
                    },
                    buttonsStyling: false,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/api/users/" + userId,
                            type: "DELETE",
                            cache: false,
                            success: function (res) {
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
                            error: function (xhr) {
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
            $(document).on('click', '.btn-edit', function (e) {
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
                    cache: false,
                    success: function (res) {
                        if (res.success) {
                            const user = res.data;

                            // Store original data for comparison
                            originalUserData = {
                                id: user.id,
                                nama: user.nama,
                                email: user.email,
                                jabatan: user.jabatan
                            };

                            $('#edit-id').val(user.id);
                            $('#edit-nama').val(user.nama);
                            $('#edit-email').val(user.email);
                            $('#edit-jabatan').val(user.jabatan);

                            // SECURITY: Disable 'Jabatan' dropdown if editing own account
                            if (user.id === currentId) {
                                $('#edit-jabatan').prop('disabled', true);
                                if ($('#self-edit-warning').length === 0) {
                                    $('#edit-jabatan').after('<small id="self-edit-warning" class="text-muted"><i class="fas fa-lock mr-1"></i>Demi keamanan, Anda tidak dapat mengubah jabatan sendiri.</small>');
                                }
                            } else {
                                $('#edit-jabatan').prop('disabled', false);
                                $('#self-edit-warning').remove();
                            }

                            // Reset error states
                            $('#form-edit-user .form-control').removeClass('is-invalid');
                            $('#form-edit-user .invalid-feedback').text('');

                            $('#modalEditUser').modal('show');
                            Swal.close();
                        }
                    },
                    error: function (xhr) {
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
            $('#form-edit-user').on('submit', function (e) {
                e.preventDefault();

                // Re-enable jabatan field temporarily so it gets included in FormData
                // The backend will still validate and prevent changes to 'Karyawan' for self
                $('#edit-jabatan').prop('disabled', false);

                // Reset error states
                $(this).find('.form-control').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');

                const userId = $('#edit-id').val();

                // Get current form values
                const currentData = {
                    nama: $('#edit-nama').val(),
                    email: $('#edit-email').val(),
                    jabatan: $('#edit-jabatan').val()
                };

                // Get password values
                const password = $('input[name="password"]').val();
                const passwordConfirmation = $('input[name="password_confirmation"]').val();

                // Check if any data has changed
                const hasDataChanged =
                    currentData.nama !== originalUserData.nama ||
                    currentData.email !== originalUserData.email ||
                    currentData.jabatan !== originalUserData.jabatan ||
                    (password && password.trim() !== '');

                // If no changes detected, show message and return
                if (!hasDataChanged) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Perubahan',
                        text: 'Tidak ada data yang diubah. Silakan ubah data terlebih dahulu atau tutup form ini.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Create FormData object
                const formData = new FormData(this);

                // Check if password is empty and remove it from FormData
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
                    success: function (res) {
                        if (res.success) {
                            $('#modalEditUser').modal('hide');

                            // Check if user updated themselves
                            if (userId == currentId) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Profil berhasil diperbarui. Halaman akan dimuat ulang...',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    willClose: () => {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                // Reload data immediately
                                setTimeout(function () {
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
                        }


                    },
                    error: function (xhr) {
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
                    complete: function () {
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
            $(document).on('shown.bs.modal', function () {
                // Make sure modals are properly positioned
                $('.modal').css('z-index', '1050');
            });

            // Fix for dropdowns and buttons
            $(document).on('click', '[data-toggle="dropdown"]', function (e) {
                e.preventDefault();
                $(this).dropdown('toggle');
            });

            // Ensure all buttons are clickable
            $(document).on('click', '.btn', function (e) {
                if (!$(this).prop('disabled')) {
                    return true;
                }
                e.preventDefault();
                return false;
            });

            // Fix for DataTables buttons
            $(document).on('init.dt', function (e, settings) {
                var api = new $.fn.dataTable.Api(settings);
                $(api.table().container()).find('.btn').css('pointer-events', 'auto');
            });

            // ==================== SUPER ADMIN MANAGEMENT ====================
            // Load Super Admin modal when opened
            $('#modalSuperAdmin').on('show.bs.modal', function () {
                loadSuperAdminTable();
            });

            function loadSuperAdminTable() {
                $.ajax({
                    url: "/api/users",
                    type: "GET",
                    cache: false,
                    success: function (res) {
                        if (res.success) {
                            let tbody = '';
                            let counter = 1;

                            // Filter only Admins
                            const admins = res.data.filter(u => u.jabatan === 'Admin');

                            admins.forEach(function (user) {
                                let statusBadge = user.is_super_admin
                                    ? '<span class="badge badge-success">Aktif</span>'
                                    : '<span class="badge badge-secondary">Tidak Aktif</span>';

                                let isSelf = user.id === currentId;
                                let buttonText = user.is_super_admin ? 'Cabut Hak' : 'Berikan Hak';
                                let buttonClass = user.is_super_admin ? 'btn-danger' : 'btn-success';
                                let disabled = isSelf && user.is_super_admin ? 'disabled title="Tidak dapat mencabut hak sendiri"' : '';

                                tbody += `
                                                                                        <tr>
                                                                                            <td class="text-center">${counter++}</td>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <img class="img-profile rounded-circle mr-2"
                                                                                                        style="width: 2rem; height: 2rem; object-fit: cover;"
                                                                                                        src="${user.profile_photo_path ? '/storage/' + user.profile_photo_path : '{{ asset('sbadmin2/img/undraw_profile.svg') }}'}"
                                                                                                        alt="Profile Photo">
                                                                                                    <span>${user.nama}${isSelf ? ' <small class="text-muted">(Anda)</small>' : ''}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">${user.email}</td>
                                                                                            <td class="text-center">${statusBadge}</td>
                                                                                            <td class="text-center">
                                                                                                <button class="btn ${buttonClass} btn-sm btn-toggle-super-admin" data-id="${user.id}" ${disabled}>
                                                                                                    ${buttonText}
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    `;
                            });

                            if (admins.length === 0) {
                                tbody = '<tr><td colspan="5" class="text-center py-3">Tidak ada Admin ditemukan.</td></tr>';
                            }

                            $('#super-admin-body').html(tbody);
                        }
                    },
                    error: function () {
                        $('#super-admin-body').html('<tr><td colspan="5" class="text-center py-3 text-danger">Gagal memuat data.</td></tr>');
                    }
                });
            }

            // Toggle Super Admin status
            $(document).on('click', '.btn-toggle-super-admin', function () {
                const userId = $(this).data('id');
                const $btn = $(this);
                const originalText = $btn.text();

                $btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

                $.ajax({
                    url: "/api/users/" + userId + "/toggle-super-admin",
                    type: "POST",
                    success: function (res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Reload both tables
                            loadSuperAdminTable();
                            loadUsers();
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                        $btn.html(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection