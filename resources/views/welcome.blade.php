<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Hakai | Task Management Redefined</title>
    <meta name="description" content="Manage your tasks with Hakai - The ultimate task management platform for teams.">

    <!-- Favicons -->
    <link href="{{asset('enno/assets/img/logo-hakai.png')}}" rel="icon">
    <link href="{{asset('enno/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Main CSS -->
    <link href="{{asset('enno/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{ asset('css/enhancements.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">

    <style>
        :root {
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-secondary: 'Outfit', sans-serif;
            --hakai-primary: #6C3AF0;
            --hakai-secondary: #9B6DFF;
            --hakai-gradient: linear-gradient(135deg, #6C3AF0 0%, #9B6DFF 100%);
        }

        body {
            font-family: var(--font-primary);
            color: #1a1a2e;
            background-color: #fff;
            overflow-x: hidden;
        }

        /* Navbar Floating Glass */
        .header {
            background: transparent !important;
            padding: 20px 0;
            transition: all 0.4s ease;
            z-index: 1000;
        }

        .header.scrolled {
            padding: 12px 0;
        }

        .header .container-fluid {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            padding: 8px 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sitename {
            font-family: var(--font-secondary);
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -0.5px;
            background: var(--hakai-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .navmenu ul {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navmenu ul li a {
            font-weight: 600;
            font-size: 15px;
            color: #4a4a6a !important;
            padding: 10px 18px !important;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .navmenu ul li a.active,
        .navmenu ul li a:hover {
            color: var(--hakai-primary) !important;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-getstarted {
            background: var(--hakai-gradient);
            color: #fff !important;
            border-radius: 50px;
            padding: 10px 22px !important;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 8px 20px rgba(108, 58, 240, 0.2);
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-getstarted:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(108, 58, 240, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 140px 0 60px;
            position: relative;
            min-height: auto;
        }

        .hero-title {
            font-family: var(--font-secondary);
            font-size: 4.2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            letter-spacing: -2px;
            color: #0f172a;
        }

        .hero-title span {
            background: var(--hakai-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 550px;
        }

        .hero-img {
            max-height: 500px;
            filter: drop-shadow(0 30px 60px rgba(108, 58, 240, 0.25));
            transition: all 0.5s ease;
        }

        /* Features Section */
        .features {
            padding: 60px 0 100px;
            background: #f8fafc;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title span {
            color: var(--hakai-primary);
            text-transform: uppercase;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 10px;
            display: block;
        }

        .section-title h2 {
            font-family: var(--font-secondary);
            font-weight: 800;
            font-size: 36px;
            color: #0f172a;
        }

        .feature-item {
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
        }

        .feature-item:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 60px -12px rgba(108, 58, 240, 0.15);
            border-color: rgba(108, 58, 240, 0.1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: rgba(108, 58, 240, 0.08);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            background: var(--hakai-gradient);
        }

        .feature-icon i {
            font-size: 28px;
            color: var(--hakai-primary);
        }

        .feature-item:hover .feature-icon i {
            color: #fff;
        }

        .feature-item h3 {
            font-family: var(--font-secondary);
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 15px;
            color: #0f172a;
        }

        .feature-item p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* About Section */
        .about {
            padding: 100px 0;
            background: #fff;
            position: relative;
            overflow: hidden;
        }

        .about::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 300px;
            background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
            z-index: 0;
        }

        .about .container {
            position: relative;
            z-index: 1;
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(108, 58, 240, 0.1);
            border-radius: 24px;
            padding: 35px 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(108, 58, 240, 0.15);
            border-color: rgba(108, 58, 240, 0.3);
        }

        .stats-number {
            font-family: var(--font-secondary);
            font-size: 3rem;
            font-weight: 800;
            background: var(--hakai-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            line-height: 1;
        }

        .stats-label {
            color: #64748b;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .about-content {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(108, 58, 240, 0.1);
            border-radius: 24px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .about-content h3 {
            font-family: var(--font-secondary);
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .about-content p {
            color: #64748b;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .about-content .highlight {
            background: rgba(108, 58, 240, 0.08);
            border-left: 4px solid var(--hakai-primary);
            padding: 20px 25px;
            border-radius: 12px;
            margin-top: 30px;
        }

        .about-content .highlight p {
            margin: 0;
            font-weight: 600;
            color: #0f172a;
        }

        /* Contact Section */
        .contact {
            padding: 100px 0;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        }

        .contact-info-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(108, 58, 240, 0.1);
            border-color: rgba(108, 58, 240, 0.2);
        }

        .contact-icon {
            width: 56px;
            height: 56px;
            background: rgba(108, 58, 240, 0.08);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-icon i {
            font-size: 24px;
            color: var(--hakai-primary);
        }

        .contact-info-card h4 {
            font-family: var(--font-secondary);
            font-weight: 700;
            font-size: 18px;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .contact-info-card p {
            color: #64748b;
            font-size: 15px;
            margin: 0;
            line-height: 1.6;
        }

        .contact-form {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .contact-form .form-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .contact-form .form-control,
        .contact-form .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .contact-form .form-control:focus,
        .contact-form .form-select:focus {
            border-color: var(--hakai-primary);
            box-shadow: 0 0 0 4px rgba(108, 58, 240, 0.1);
        }

        .contact-form textarea.form-control {
            min-height: 140px;
            resize: vertical;
        }

        .btn-submit {
            background: var(--hakai-gradient);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(108, 58, 240, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(108, 58, 240, 0.3);
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Dark Mode Enhancements */
        html.dark-mode body {
            background-color: #0f172a;
            color: #f8fafc;
        }

        html.dark-mode .header .container-fluid {
            background: rgba(15, 23, 42, 0.8);
            border-color: rgba(255, 255, 255, 0.08);
        }

        html.dark-mode .navmenu ul li a {
            color: #94a3b8 !important;
        }

        html.dark-mode .navmenu ul li a.active,
        html.dark-mode .navmenu ul li a:hover {
            color: #fff !important;
        }

        html.dark-mode .hero-title {
            color: #fff;
        }

        html.dark-mode .hero-subtitle {
            color: #94a3b8;
        }

        html.dark-mode .features {
            background: #12122b;
        }

        html.dark-mode .feature-item {
            background: #1e1e3f;
            border-color: #2e2e4f;
        }

        html.dark-mode .feature-item h3 {
            color: #fff;
        }

        html.dark-mode .feature-item p {
            color: #94a3b8;
        }

        html.dark-mode .section-title h2 {
            color: #fff;
        }

        html.dark-mode .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
        }

        html.dark-mode .about {
            background: #0f172a;
        }

        html.dark-mode .about::before {
            background: linear-gradient(180deg, #12122b 0%, #0f172a 100%);
        }

        html.dark-mode .stats-card {
            background: rgba(30, 30, 63, 0.8);
            border-color: rgba(155, 109, 255, 0.2);
        }

        html.dark-mode .stats-label {
            color: #94a3b8;
        }

        html.dark-mode .about-content {
            background: rgba(30, 30, 63, 0.9);
            border-color: rgba(155, 109, 255, 0.2);
        }

        html.dark-mode .about-content h3 {
            color: #fff;
        }

        html.dark-mode .about-content p {
            color: #94a3b8;
        }

        html.dark-mode .about-content .highlight {
            background: rgba(108, 58, 240, 0.15);
            border-color: var(--hakai-secondary);
        }

        html.dark-mode .about-content .highlight p {
            color: #f8fafc;
        }

        html.dark-mode .contact {
            background: linear-gradient(180deg, #0f172a 0%, #12122b 100%);
        }

        html.dark-mode .contact-info-card {
            background: #1e1e3f;
            border-color: #2e2e4f;
        }

        html.dark-mode .contact-info-card h4 {
            color: #fff;
        }

        html.dark-mode .contact-info-card p {
            color: #94a3b8;
        }

        html.dark-mode .contact-form {
            background: #1e1e3f;
            border-color: #2e2e4f;
        }

        html.dark-mode .contact-form .form-label {
            color: #f8fafc;
        }

        html.dark-mode .contact-form .form-control,
        html.dark-mode .contact-form .form-select {
            background: #12122b;
            border-color: #2e2e4f;
            color: #f8fafc;
        }

        html.dark-mode .contact-form .form-control:focus,
        html.dark-mode .contact-form .form-select:focus {
            background: #1a1a3a;
            border-color: var(--hakai-secondary);
        }

        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
                letter-spacing: -1px;
            }

            .hero {
                padding: 120px 0 40px;
            }

            .navmenu {
                display: none !important;
            }

            .header .container-fluid {
                padding: 8px 15px;
                margin: 0 15px;
                width: calc(100% - 30px);
            }

            .btn-getstarted {
                padding: 8px 16px !important;
                font-size: 13px;
            }

            .about,
            .contact {
                padding: 60px 0;
            }

            .about-content {
                padding: 35px;
                margin-top: 30px;
            }

            .contact-form {
                padding: 30px;
                margin-top: 30px;
            }

            .stats-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 575px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .btn-getstarted {
                font-size: 12px;
            }

            .about-content h3 {
                font-size: 1.5rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .about-content,
            .contact-form {
                padding: 25px;
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

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid position-relative">
            <a href="#beranda" class="logo">
                <img src="{{asset('enno/assets/img/logo-hakai.png')}}" alt="Hakai" style="height: 32px;" class="me-2">
                <h1 class="sitename">Hakai</h1>
            </a>

            <nav id="navmenu" class="navmenu d-none d-xl-block">
                <ul>
                    <li><a href="#beranda" class="active">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <a href="#" id="darkModeToggle" class="nav-link p-0 me-2" style="font-size: 20px;">
                    <i class="bi bi-moon-stars-fill text-primary"></i>
                </a>
                @auth
                    <a class="btn-getstarted" href="{{route('dashboard')}}">Dashboard <i
                            class="bi bi-arrow-right ms-1"></i></a>
                @else
                    <a class="btn-getstarted" href="{{route('login')}}">Sign In <i
                            class="bi bi-box-arrow-in-right ms-1"></i></a>
                @endauth
                <i class="mobile-nav-toggle d-xl-none bi bi-list"
                    style="font-size: 28px; cursor: pointer; color: var(--hakai-primary);"></i>
            </div>
        </div>
    </header>

    <main class="main">
        <!-- Hero Section -->
        <section id="beranda" class="hero">
            <div class="mesh-gradient-aura"></div>
            <div class="container">
                <div class="row g-lg-5 g-4 align-items-center">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-4 fw-bold">🚀
                            Platform Manajemen Tugas Pro</span>
                        <h1 class="hero-title">
                            Kelola Semua<br>Tugas <span>Tanpa Batas</span>
                        </h1>
                        <p class="hero-subtitle">
                            Hakai hadir untuk membantu tim Anda tetap produktif dengan fitur manajemen tugas berbasis
                            cloud yang intuitif, aman, dan kolaboratif.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('login') }}" class="btn-getstarted px-4 py-3 fs-6">
                                Mulai Sekarang <i class="bi bi-stars ms-2"></i>
                            </a>
                            <a href="#about"
                                class="btn btn-outline-dark border-2 rounded-pill px-4 py-3 fw-bold dark-text-white"
                                style="border-color: #e2e8f0; color: #475569;">
                                Pelajari Fitur
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-delay="200" data-aos-duration="1000">
                        <div class="position-relative">
                            <img src="{{ asset('images/welcome_hero.png') }}" class="img-fluid hero-img"
                                alt="Hakai Hero">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <span>Fitur Unggulan</span>
                    <h2>Didesain Untuk Produktivitas</h2>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-stack"></i></div>
                            <h3>Smart Kanban</h3>
                            <p>Visualisasikan workflow tim Anda dengan papan Kanban yang dinamis dan fleksibel.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                            <h3>Optimasi Real-time</h3>
                            <p>Sinkronisasi data instan memastikan setiap anggota tim selalu mendapatkan update terbaru.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                            <h3>Keamanan Ketat</h3>
                            <p>Data bisnis Anda terlindungi dengan enkripsi tingkat lanjut dan audit log sistem.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
                            <h3>Analitik Cerdas</h3>
                            <p>Pantau performa tim melalui laporan statistik yang detail dan mudah dipahami.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <span>Tentang Hakai</span>
                    <h2>Platform Manajemen Tugas Terpercaya</h2>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="stats-card">
                            <div class="stats-number">0</div>
                            <div class="stats-label">Pengguna Aktif</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="stats-card">
                            <div class="stats-number">0</div>
                            <div class="stats-label">Tugas Selesai</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="stats-card">
                            <div class="stats-number">0</div>
                            <div class="stats-label">Kepuasan (%)</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="stats-card">
                            <div class="stats-number">0</div>
                            <div class="stats-label">Support 24/7</div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10" data-aos="fade-up" data-aos-delay="500">
                        <div class="about-content">
                            <h3>Tentang Aplikasi</h3>
                            <p>
                                Penulis sebagai siswa SMK Yadika Soreang melaksanakan Praktik Kerja Lapangan (PKL) di
                                PT Zahir Accounting Divisi Sysforeach. Terpilih untuk membuat aplikasi pengelolaan atau
                                manajemen penugasan berbasis <em>website</em>. <em>Website</em> ini dirancang untuk
                                membantu
                                karyawan dalam mengelola tugas dengan lebih terstruktur, cepat, dan mudah diakses.
                            </p>
                            <p>
                                Selama kegiatan PKL, penulis mendapat kesempatan untuk pembuatan aplikasi pengelolaan
                                tugas/ manajemen penugasan berbasis <em>website</em>. <em>Website</em> ini dirancang
                                untuk
                                membantu karyawan dalam mengelola jadwal kelas secara lebih efisien dan mudah diakses.
                            </p>
                            <p>
                                Sistem penugasan yang masih dilakukan secara manual seringkali membutuhkan kendala,
                                seperti bentroknya jadwal, pembatuan mendadak, maupun kesulitan dalam mendistribusikan
                                informasi jadwal kepada Instruktur dan Peserta pelatihan. Oleh karena itu, dibutuhkan
                                sebuah
                                aplikasi berbasis web yang dapat mengelola jadwal kelas secara terstruktur, cepat, dan
                                mudah
                                diakses.
                            </p>
                            <div class="highlight">
                                <p>
                                    <i class="bi bi-quote me-2"></i>
                                    Dengan adanya aplikasi pengelolaan jadwal kelas ini, diharapkan proses administrasi
                                    pendidikan dapat berjalan lebih lancar, meminimalisir kesalahan pendataan, serta
                                    meningkatkan
                                    efisiensi kerja. Selain itu, penulis juga dapat mengasah keterampilan dalam
                                    pengembangan
                                    aplikasi <em>fullstack</em> dengan memanfaatkan teknologi <em>website</em> modern.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <span>Hubungi Kami</span>
                    <h2>Mari Terhubung Dengan Kami</h2>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h4>Alamat</h4>
                                <p>Jl. Teknologi No. 123<br>Hakai tekno Selatan, 12345</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <h4>Email</h4>
                                <p>support@hakai.com<br>info@hakai.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="contact-info-card">
                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <h4>Telepon</h4>
                                <p>+62 21 1234 5678<br>+62 812 3456 7890</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="400">
                        <div class="contact-form">
                            <form id="contactForm" action="#" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subjek</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Pesan</label>
                                        <textarea class="form-control" id="message" name="message" rows="5"
                                            required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-submit">
                                            <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{asset('enno/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('enno/assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('enno/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('enno/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('enno/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Main JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Scroll Animation Initialize
            AOS.init({ once: true, mirror: false, duration: 800 });

            // Navbar Scrolled State
            const header = document.querySelector('#header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;

            function updateIcon() {
                const icon = darkModeToggle.querySelector('i');
                if (html.classList.contains('dark-mode')) {
                    icon.className = 'bi bi-sun-fill text-warning';
                } else {
                    icon.className = 'bi bi-moon-stars-fill text-primary';
                }
            }

            if (darkModeToggle) {
                updateIcon();
                darkModeToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    html.classList.toggle('dark-mode');
                    localStorage.setItem('darkMode', html.classList.contains('dark-mode') ? 'enabled' : 'disabled');
                    updateIcon();
                });
            }

            // Smooth Scroll for Navigation Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerHeight = header.offsetHeight;
                        const targetPosition = target.offsetTop - headerHeight - 20;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Active Navigation State on Scroll
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.navmenu a[href^="#"]');

            function updateActiveNav() {
                const scrollY = window.pageYOffset;

                sections.forEach(section => {
                    const sectionHeight = section.offsetHeight;
                    const sectionTop = section.offsetTop - 150;
                    const sectionId = section.getAttribute('id');

                    if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                        navLinks.forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === `#${sectionId}`) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            }

            window.addEventListener('scroll', updateActiveNav);
            updateActiveNav();

            // Statistics are now static (showing real values: 0)
            // Animation removed for presentation authenticity

            // Contact Form Handling
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Get form data
                    const formData = new FormData(contactForm);
                    const data = Object.fromEntries(formData);

                    // Simple validation
                    if (!data.name || !data.email || !data.subject || !data.message) {
                        alert('Mohon lengkapi semua field!');
                        return;
                    }

                    // Email validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(data.email)) {
                        alert('Format email tidak valid!');
                        return;
                    }

                    // Show success message (in production, this would send to backend)
                    const submitBtn = contactForm.querySelector('.btn-submit');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Pesan Terkirim!';
                    submitBtn.disabled = true;

                    // Reset form after 2 seconds
                    setTimeout(() => {
                        contactForm.reset();
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        alert('Terima kasih! Pesan Anda telah diterima. Kami akan segera menghubungi Anda.');
                    }, 2000);

                    // In production, you would send this to your backend:
                    // fetch('/api/contact', {
                    //     method: 'POST',
                    //     headers: { 'Content-Type': 'application/json' },
                    //     body: JSON.stringify(data)
                    // });
                });
            }

            // Mobile Menu Toggle
            const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
            const navmenu = document.querySelector('.navmenu');

            if (mobileNavToggle && navmenu) {
                mobileNavToggle.addEventListener('click', function () {
                    navmenu.classList.toggle('d-none');
                    navmenu.classList.toggle('d-block');
                    this.classList.toggle('bi-list');
                    this.classList.toggle('bi-x');
                });

                // Close mobile menu when clicking on a link
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 1200) {
                            navmenu.classList.add('d-none');
                            navmenu.classList.remove('d-block');
                            mobileNavToggle.classList.add('bi-list');
                            mobileNavToggle.classList.remove('bi-x');
                        }
                    });
                });
            }
        });
    </script>

</body>

</html>
