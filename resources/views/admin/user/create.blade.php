@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus text-primary mr-2"></i>
                {{ $title }}
            </h1>
        </div>

        <!-- Notifikasi -->
        <div id="notification-container"></div>

        <!-- Card Form -->
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <!-- Illustration Section -->
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="fas fa-user-plus fa-5x text-white mb-3"></i>
                                <h4 class="text-white">Tambah User Baru</h4>
                                <p class="text-white-50">Isi form di samping untuk menambahkan user baru ke sistem</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="col-lg-7">
                        <div class="p-5">
                            <form class="user" action="{{ route('user.store') }}" method="POST">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label class="form-label">
                                            <i class="fas fa-user text-gray-600 mr-1"></i> Nama Lengkap
                                        </label>
                                        <input type="text" name="nama" id="nama" class="form-control form-control-user"
                                               placeholder="Contoh: John Doe" value="{{ old('nama') }}">
                                        @error('nama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">
                                            <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control form-control-user"
                                               placeholder="contoh@email.com" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-briefcase text-gray-600 mr-1"></i> Jabatan
                                    </label>
                                    <select name="jabatan" id="jabatan" class="form-select form-select-lg">
                                        <option selected disabled>-- Pilih Jabatan --</option>
                                        <option value="Admin" {{ old('jabatan') == 'Admin' ? 'selected' : '' }}>
                                            <i class="fas fa-user-shield mr-1"></i> Administrator
                                        </option>
                                        <option value="Karyawan" {{ old('jabatan') == 'Karyawan' ? 'selected' : '' }}>
                                            <i class="fas fa-user mr-1"></i> Karyawan
                                        </option>
                                    </select>
                                    @error('jabatan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label class="form-label">
                                            <i class="fas fa-lock text-gray-600 mr-1"></i> Password
                                        </label>
                                        <input type="password" name="password" id="password" class="form-control form-control-user"
                                               placeholder="Minimal 8 karakter">
                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">
                                            <i class="fas fa-lock text-gray-600 mr-1"></i> Konfirmasi Password
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-user"
                                               placeholder="Ulangi password">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mt-4">
                                    <div class="col-sm-6">
                                        <a href="{{ route('user.index') }}" class="btn btn-user btn-secondary btn-block">
                                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-user btn-primary btn-block" id="btn-save">
                                            <i class="fas fa-save mr-2"></i> Simpan User
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-register-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-control-user {
            font-size: 0.8rem;
            border-radius: 10rem;
            padding: 1rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.2s ease-in-out;
        }

        .form-control-user:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-select-lg {
            font-size: 0.8rem;
            border-radius: 10rem;
            padding: 1rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.2s ease-in-out;
        }

        .form-select-lg:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-user {
            font-size: 0.8rem;
            border-radius: 10rem;
            padding: 0.75rem 1rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-user:active {
            transform: translateY(1px);
        }

        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }

        .card-body {
            padding: 0;
        }

        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .invalid-feedback {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Animation for form inputs */
        .form-control-user, .form-select-lg {
            position: relative;
        }

        .form-control-user:focus, .form-select-lg:focus {
            animation: inputPulse 0.3s ease-in-out;
        }

        @keyframes inputPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        /* Loading state for button */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spinner 0.6s linear infinite;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Tampilkan notifikasi jika ada
            @if(session('success'))
            showNotification('success', '{{ session('success') }}');
            @endif

            @if(session('error'))
            showNotification('danger', '{{ session('error') }}');
            @endif

            // Fungsi untuk menampilkan notifikasi
            function showNotification(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                $('#notification-container').html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas ${icon} fa-2x mr-3"></i>
                        <div>
                            <h5 class="alert-heading">${type === 'success' ? 'Berhasil!' : 'Gagal!'}</h5>
                            <p class="mb-0">${message}</p>
                        </div>
                        <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            `);

                // Auto hide after 5 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Form submission dengan loading state
            $('#form-user').on('submit', function (e) {
                // Tambahkan loading state pada tombol
                const $btn = $('#btn-save');
                $btn.addClass('btn-loading');
                $btn.prop('disabled', true);

                // Validasi sederhana sebelum submit
                let isValid = true;
                $('.form-control-user, .form-select-lg').each(function() {
                    if ($(this).val() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    $btn.removeClass('btn-loading');
                    $btn.prop('disabled', false);
                    e.preventDefault();
                }
            });

            // Auto remove invalid class saat user mulai mengetik
            $('.form-control-user, .form-select-lg').on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Password strength indicator (opsional)
            $('#password').on('input', function() {
                const password = $(this).val();
                let strength = 0;

                // Cek panjang password
                if (password.length >= 8) strength += 1;
                if (password.match(/[a-z]+/)) strength += 1;
                if (password.match(/[A-Z]+/)) strength += 1;
                if (password.match(/[0-9]+/)) strength += 1;
                if (password.match(/[$@#&!]+/)) strength += 1;

                // Tampilkan indikator (bisa dikembangkan lebih lanjut)
            });
        });
    </script>
@endsection
