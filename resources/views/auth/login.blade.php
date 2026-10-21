<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Hakai | Welcome Back</title>

    <!-- [Favicon] -->
    <link href="{{asset('enno/assets/img/logo-hakai.png')}}" rel="icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheets -->
    <link href="{{asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('hakai/dist/assets/fonts/fontawesome.css')}}" rel="stylesheet">
    <link href="{{ asset('css/enhancements.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">

    <style>
        :root {
            --hakai-primary: #6C3AF0;
            --hakai-secondary: #9B6DFF;
            --hakai-gradient: linear-gradient(135deg, #6C3AF0 0%, #9B6DFF 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f8fafc;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
        }

        /* Left Split - Branding & Illustration */
        .auth-side-branding {
            flex: 1.1;
            background: linear-gradient(135deg, #1e1e3f 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 80px 60px;
            position: relative;
            overflow: hidden;
        }

        .auth-side-branding::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--hakai-gradient);
            filter: blur(150px);
            opacity: 0.15;
            top: -250px;
            left: -250px;
        }

        .branding-content {
            z-index: 10;
            text-align: center;
            max-width: 500px;
        }

        .branding-content h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            color: #fff;
            margin: 24px 0 16px;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .branding-content p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Right Split - Form */
        .auth-side-form {
            flex: 0.9;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            /* Reduced for better focus */
        }

        .login-header {
            margin-bottom: 35px;
        }

        .login-header h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-weight: 500;
            font-size: 1.05rem;
        }

        /* Form Styling */
        .auth-input-group {
            margin-bottom: 22px;
        }

        .auth-input-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
            font-size: 14px;
            color: #334155;
            text-align: left;
        }

        .auth-input-wrapper {
            position: relative;
        }

        .auth-input-wrapper i.fa-user,
        .auth-input-wrapper i.fa-lock {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.3s ease;
            font-size: 18px;
        }

        .auth-control {
            width: 100%;
            padding: 14px 20px 14px 52px;
            /* Adjusted left padding */
            border-radius: 14px;
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .auth-control:focus {
            outline: none;
            border-color: var(--hakai-primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(108, 58, 240, 0.1);
        }

        .auth-control:focus+i {
            color: var(--hakai-primary);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 5px;
            z-index: 5;
        }

        .btn-auth-primary {
            width: 100%;
            background: var(--hakai-gradient);
            color: #fff;
            border: none;
            padding: 16px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.05rem;
            box-shadow: 0 10px 20px -10px rgba(108, 58, 240, 0.4);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-auth-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -10px rgba(108, 58, 240, 0.5);
        }

        .btn-auth-primary:active {
            transform: translateY(0);
        }

        /* Hero Illustration Glass Card */
        .hero-illus-container {
            position: relative;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 20px;
            margin-top: 3rem !important;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .auth-side-branding img.branding-hero {
            max-width: 100%;
            height: auto;
            max-height: 380px;
            object-fit: contain;
            border-radius: 20px;
        }

        .form-check.custom-check {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 24px;
            /* Aligned with input text starting position */
            margin-bottom: 0;
        }

        .form-check-input {
            margin: 0;
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
            border-radius: 6px;
            border-color: #cbd5e1;
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
            color: #64748b !important;
            font-size: 0.9rem !important;
        }

        /* Dark Mode Support Updates */
        html.dark-mode body {
            background-color: #0f172a;
        }

        html.dark-mode .auth-side-form {
            background: #12122b;
        }

        html.dark-mode .login-header h1 {
            color: #fff;
        }

        html.dark-mode .auth-input-group label {
            color: #cbd5e1;
        }

        html.dark-mode .auth-control {
            background: #1e1e3f;
            border-color: #2e2e4f;
            color: #fff;
        }

        html.dark-mode .auth-control:focus {
            background: #1a1a35;
        }

        html.dark-mode .form-check-label {
            color: #94a3b8 !important;
        }

        html.dark-mode .text-muted {
            color: #94a3b8 !important;
        }

        html.dark-mode .hero-illus-container {
            background: rgba(255, 255, 255, 0.01);
            border-color: rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 991px) {
            .auth-side-branding {
                display: none;
            }

            .auth-side-form {
                flex: 1;
                padding: 30px;
            }

            .login-box {
                max-width: 400px;
            }
        }
    </style>

    <!-- Prevent flash -->
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>

<body>
    <div class="auth-container">
        <!-- Brand Side -->
        <div class="auth-side-branding">
            <div class="branding-content">
                <a href="{{ route('welcome') }}" id="logo-branding-link">
                    <img src="{{ asset('enno/assets/img/logo-hakai.png') }}" alt="Hakai"
                        style="height: 70px; filter: drop-shadow(0 0 20px rgba(108, 58, 240, 0.5));">
                </a>
                <h2>Mulai Perjalanan<br>Produktif Anda</h2>
                <p>Masuk ke ekosistem Hakai dan kelola tugas tim Anda dengan cara yang paling menyenangkan.</p>
                <div class="hero-illus-container">
                    <img src="{{ asset('images/welcome_hero.png') }}" class="img-fluid branding-hero"
                        alt="Illustration">
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="auth-side-form">
            <div class="login-box">
                <div class="login-header">
                    <a href="{{ route('welcome') }}" class="d-lg-none mb-4 d-inline-block">
                        <img src="{{ asset('enno/assets/img/logo-hakai.png') }}" alt="Hakai" style="height: 42px;">
                    </a>
                    <h1>Selamat Datang</h1>
                    <p>Senang melihat Anda kembali!</p>
                </div>

                <form method="POST" action="{{ route('loginProses') }}" id="login-form-main">
                    @csrf

                    <div class="auth-input-group">
                        <label for="login">Email atau Username</label>
                        <div class="auth-input-wrapper">
                            <input type="text" id="login" name="login"
                                class="auth-control @error('login') is-invalid @enderror" value="{{ old('login') }}"
                                placeholder="haedar@hakai.io" required autofocus>
                            <i class="fas fa-user"></i>
                        </div>
                        @error('login')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="auth-input-group">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="password-input" class="mb-0">Password</label>
                            <a href="{{ route('password.request') }}"
                                class="text-primary small fw-bold text-decoration-none">Lupa Password?</a>
                        </div>
                        <div class="auth-input-wrapper">
                            <input type="password" id="password-input" name="password"
                                class="auth-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                required>
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" id="password-toggle-btn">
                                <i class="fas fa-eye" id="toggle-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check custom-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small fw-bold" for="remember">Biarkan saya tetap
                                masuk</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-primary" id="submit-btn-login">
                        <span class="btn-text">Masuk Sekarang</span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-circle-notch fa-spin me-2"></i>Memproses...
                        </span>
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-muted small">Kembali ke <a href="{{ route('welcome') }}"
                            class="text-primary fw-bold text-decoration-none">Beranda</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{asset('hakai/dist/assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{asset('sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Toggle
            const toggleBtn = document.getElementById('password-toggle-btn');
            const passwordInput = document.getElementById('password-input');
            const toggleIcon = document.getElementById('toggle-icon');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    toggleIcon.classList.toggle('fa-eye');
                    toggleIcon.classList.toggle('fa-eye-slash');
                });
            }

            // Form Loading
            const form = document.getElementById('login-form-main');
            if (form) {
                form.addEventListener('submit', function () {
                    const btn = document.getElementById('submit-btn-login');
                    const text = btn.querySelector('.btn-text');
                    const loading = btn.querySelector('.btn-loading');

                    btn.disabled = true;
                    text.classList.add('d-none');
                    loading.classList.remove('d-none');
                });
            }
        });
    </script>

    @if(session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Swal.fire({ title: "Yeay!", text: "{{ session('success') }}", icon: "success", customClass: { confirmButton: 'btn btn-primary rounded-pill px-4' } });
                @elseif(session('error'))
                    Swal.fire({ title: "Yahh!", text: "{{ session('error') }}", icon: "error", customClass: { confirmButton: 'btn btn-primary rounded-pill px-4' } });
                @endif
                            });
        </script>
    @endif
</body>

</html>