<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hakai | Reset Password</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- [Favicon] -->
    <link href="{{asset('enno/assets/img/logo-hakai.png')}}" rel="icon">

    <!-- Vendor CSS Files (Enno) -->
    <link href="{{asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{asset('enno/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/css/style.css')}}" rel="stylesheet">

    <!-- [Fonts & CSS] -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/tabler-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/css/style.css')}}" id="main-style-link">
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/css/style-preset.css')}}">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">

    <!-- Prevent flash -->
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <!-- Logo + Judul -->
                <div class="auth-header">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('enno/assets/img/logo-hakai.png') }}" alt="Logo Hakai"
                            style="height: 60px; width: auto; margin-right: 10px;">
                        <span style="font-size: 24px; font-weight: bold; color: #6C3AF0;">HAKAI</span>
                    </div>
                </div>

                <!-- Card Form -->
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Reset Password</b></h3>
                            <img class="text-center" src="{{ asset('enno/assets/img/logo-hakai.png') }}"
                                alt="Logo Hakai" style="height: 30px; width: auto; margin-right: 10px;">
                        </div>

                        <p class="text-muted mb-4">
                            Masukkan password baru Anda.
                        </p>

                        <!-- Form Reset Password -->
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                            <div class="form-group mb-3">
                                <span class="text-danger">
                                    <label class="form-label">Password Baru</label>
                                    *
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Password Baru (min 8 karakter)" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <span class="text-danger">
                                    <label class="form-label">Konfirmasi Password</label>
                                    *
                                </span>
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Ulangi Password Baru" required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-hakai">
                                    <i class="fas fa-key me-2"></i>Reset Password
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">Hakai</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- [ JS ] -->
    <script src="{{asset('hakai/dist/assets/js/plugins/popper.min.js')}}"></script>
    <script src="{{asset('hakai/dist/assets/js/plugins/simplebar.min.js')}}"></script>
    <script src="{{asset('hakai/dist/assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{asset('hakai/dist/assets/js/pcoded.js')}}"></script>
    <script src="{{asset('sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Gagal!",
                        html: `
                                <ul style="text-align: center; list-style: none; padding: 0;">
                                    @foreach($errors->all() as $error)
                                        <li style="margin-bottom: 8px;">
                                            <i class="fas fa-exclamation-circle text-danger"></i> {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            `,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        </script>
    @endif

</body>

</html>