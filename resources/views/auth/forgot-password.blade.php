<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hakai | Lupa Password</title>
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
                <div class="card my-5 auth-card">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="mb-4">
                            <h4 class="fw-bold mb-1" style="color: #333;">Lupa Password?</h4>
                            <p class="text-muted small mb-0">Pilih metode untuk menerima link reset password Anda</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            @if(session('reset_link'))
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-link me-2"></i>Link Reset Password untuk {{ session('user_name') }}:
                                        </h6>
                                        
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="reset-link-input" 
                                                value="{{ session('reset_link') }}" readonly>
                                            <button class="btn btn-primary" type="button" id="copy-link-btn">
                                                <i class="fas fa-copy me-1"></i>Salin
                                            </button>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ session('whatsapp_url') }}" target="_blank" class="btn btn-success btn-lg">
                                                <i class="fab fa-whatsapp me-2"></i>Buka WhatsApp & Paste Link
                                            </a>
                                        </div>
                                        
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Klik "Salin" → Buka WhatsApp → Paste link di chat
                                        </small>
                                    </div>
                                </div>
                            @elseif(session('whatsapp_url'))
                                <div class="text-center mb-3">
                                    <a href="{{ session('whatsapp_url') }}" target="_blank" class="btn btn-success btn-lg">
                                        <i class="fab fa-whatsapp me-2"></i>Buka WhatsApp
                                    </a>
                                </div>
                            @endif
                        @endif

                        <!-- Form Forgot Password -->
                        <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                            @csrf

                            <!-- Delivery Method Selection -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-dark">Pilih Metode Pengiriman</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery_method"
                                            id="method_email" value="email" checked>
                                        <label class="form-check-label" for="method_email">
                                            <i class="fas fa-envelope text-primary me-1"></i> Email
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery_method"
                                            id="method_whatsapp" value="whatsapp">
                                        <label class="form-check-label" for="method_whatsapp">
                                            <i class="fab fa-whatsapp text-success me-1"></i> WhatsApp
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Input (shown by default) -->
                            <div class="mb-3" id="email-input-group">
                                <label class="form-label small fw-semibold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="email-input" value="{{ old('email') }}"
                                        placeholder="Masukkan email Anda">
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone Input (hidden by default) -->
                            <div class="mb-3 d-none" id="phone-input-group">
                                <label class="form-label small fw-semibold text-dark">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fab fa-whatsapp text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                        name="no_hp" id="phone-input" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                                </div>
                                @error('no_hp')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                                <small class="text-muted mt-1 d-block">Contoh: 081234567890</small>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i><span id="submit-text">Kirim Link via Email</span>
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none">
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
                    <div class="col-auto my-1">
                        <ul class="list-inline footer-link mb-0">
                            <li class="list-inline-item"><a href="{{ route('welcome') }}">Home</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Auth Styles -->
    <style>
        .auth-card {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: #fff;
        }
        
        .auth-card .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        
        .auth-card .form-control {
            border-left: none;
        }
        
        .auth-card .input-group:focus-within .input-group-text,
        .auth-card .input-group:focus-within .form-control {
            border-color: #6C3AF0;
        }
        
        .auth-card .input-group:focus-within .input-group-text i {
            color: #6C3AF0 !important;
        }
        
        .btn-primary {
            background-color: #6C3AF0;
            border-color: #6C3AF0;
        }
        
        .btn-primary:hover {
            background-color: #5a2dcf;
            border-color: #5a2dcf;
        }
        
        .form-check-input:checked {
            background-color: #6C3AF0;
            border-color: #6C3AF0;
        }
        
        /* Dark mode */
        .dark-mode .auth-card {
            background: #1e1e2d;
            border-color: #2a2a3d;
        }
        
        .dark-mode .auth-card .input-group-text,
        .dark-mode .auth-card .form-control {
            background: #2a2a3d;
            border-color: #3a3a4d;
            color: #fff;
        }
        
        .dark-mode .auth-card h4 {
            color: #fff !important;
        }
    </style>

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

    <script>
        // Toggle between Email and WhatsApp input
        document.addEventListener('DOMContentLoaded', function () {
            const methodEmail = document.getElementById('method_email');
            const methodWhatsapp = document.getElementById('method_whatsapp');
            const emailGroup = document.getElementById('email-input-group');
            const phoneGroup = document.getElementById('phone-input-group');
            const emailInput = document.getElementById('email-input');
            const phoneInput = document.getElementById('phone-input');
            const submitText = document.getElementById('submit-text');

            function toggleInputs() {
                if (methodEmail.checked) {
                    emailGroup.classList.remove('d-none');
                    phoneGroup.classList.add('d-none');
                    emailInput.required = true;
                    phoneInput.required = false;
                    submitText.textContent = 'Kirim Link via Email';
                } else {
                    emailGroup.classList.add('d-none');
                    phoneGroup.classList.remove('d-none');
                    emailInput.required = false;
                    phoneInput.required = true;
                    submitText.textContent = 'Kirim Link via WhatsApp';
                }
            }

            methodEmail.addEventListener('change', toggleInputs);
            methodWhatsapp.addEventListener('change', toggleInputs);

            // Initial state
            toggleInputs();

            // Copy to clipboard functionality
            const copyBtn = document.getElementById('copy-link-btn');
            const linkInput = document.getElementById('reset-link-input');
            
            if (copyBtn && linkInput) {
                copyBtn.addEventListener('click', function() {
                    linkInput.select();
                    linkInput.setSelectionRange(0, 99999); // For mobile
                    
                    navigator.clipboard.writeText(linkInput.value).then(function() {
                        // Success - change button text temporarily
                        const originalHtml = copyBtn.innerHTML;
                        copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Tersalin!';
                        copyBtn.classList.remove('btn-primary');
                        copyBtn.classList.add('btn-success');
                        
                        setTimeout(function() {
                            copyBtn.innerHTML = originalHtml;
                            copyBtn.classList.remove('btn-success');
                            copyBtn.classList.add('btn-primary');
                        }, 2000);
                    }).catch(function(err) {
                        // Fallback for older browsers
                        document.execCommand('copy');
                        alert('Link berhasil disalin!');
                    });
                });
            }
        });
    </script>

</body>

</html>