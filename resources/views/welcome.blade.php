<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Hakai | </title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{asset('enno/assets/img/logo-hakai.png')}}" rel="icon">
    <link href="{{asset('enno/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{asset('enno/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/css/style.css')}}" rel="stylesheet">
</head>

<body class="index-page">


<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="#beranda" class="logo d-flex align-items-center me-auto">

            <img src="{{asset('enno/assets/img/logo-hakai.png')}}" alt="">
            <h1 class="sitename">Hakai</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#beranda" class="active">Beranda</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        @auth
            <a class="btn-getstarted" href="{{route('dashboard')}}">Kembali ke Dashboard</a>
        @else
            <a class="btn-getstarted" href="{{route('login')}}">Masuk</a>
        @endauth



    </div>
</header>

<main class="main">

    <!-- Beranda Section -->
    <section id="beranda" class="hero section">

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
                    <h1 class="mb-5 container-fluid animated">
                        <img src="{{ asset('enno/assets/img/logo-hakai.png') }}"
                             alt="Logo Hakai"
                             class="img-fluid"
                             style="width: 100px;">
                        Hakai
                    </h1>
                    <p>Platform manajemen tugas yang membantu Anda bekerja lebih efektif</p>
                   

                    @if(auth()->check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-hakai px-4 rounded-pill">Logout</button>
                        </form>
                    @else
                        <div class="d-flex">
                            <a href="#about" class="btn-getstarted">Ayo Cari Tahu Lebih Lanjut!</a>
                        </div>
                    @endif
                </div>
                <div class=" col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
                    <img src="{{asset('enno/assets/img/hero-img.png')}}" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container col-lg-8 mx-auto section-title self-start" data-aos="fade-up">
            <span>Tentang Kami<br></span>
            <h2>Tentang</h2>
            <p>Hakai merupakan platform manajemen tugas yang dibangun untuk membantu Anda bekerja secara lebih terstruktur dan profesional. Melalui sistem yang sederhana dan terencana, Hakai memudahkan Anda dalam mengatur setiap pekerjaan, memonitor perkembangan, dan memastikan setiap target tercapai tepat waktu. Dengan Hakai, seluruh aktivitas kerja menjadi lebih terorganisir sehingga produktivitas dapat meningkat secara konsisten.</p>
        </div>
        <!-- End Section Title -->

        <div class="container py-5">
            <div class="row align-items-center gy-4">
                <!-- Kiri: Konten -->
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                    <h2 class="fw-bold mb-3">
                        Mewujudkan manajemen tugas yang lebih efisien dan terstruktur
                    </h2>
                    <p class="fst-italic text-muted mb-4">
                        Kami percaya bahwa produktivitas berasal dari sistem yang sederhana namun tepat sasaran.
                        Karena itu, <strong>Hakai</strong> dirancang untuk mendukung alur kerja Anda sehari-hari,
                        mulai dari perencanaan hingga penyelesaian tugas.
                    </p>

                    <ul class="list-unstyled mb-4">
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check2-all text-primary me-2 fs-5"></i>
                            <span>Kelola tugas dan jadwal dalam satu tempat</span>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check2-all text-primary me-2 fs-5"></i>
                            <span>Pantau progres secara real time dan kolaborasi dengan lebih baik</span>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check2-all text-primary me-2 fs-5"></i>
                            <span>Sesuaikan workflow dengan kebutuhan tim atau proyek Anda</span>
                        </li>
                    </ul>

                    <div class="mt-5">
                        <h5 class="mb-3 fw-bold">Siap lebih produktif? Yuk!</h5>
                        @auth
                            <div class="d-flex  gap-3">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-hakai px-4 rounded-pill">
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        @else
                            <div class="d-flex  gap-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-hakai px-4 rounded-pill">
                                    Masuk
                                </a>
                            </div>
                        @endauth

                    </div>
                </div>

                <!-- Kanan: Ilustrasi -->
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-delay="200">
                    <img src="{{asset('enno/assets/img/produktivitas.png')}}"
                         alt="Ilustrasi produktivitas"
                         class="img-fluid rounded-3 shadow-sm" />
                </div>
            </div>
        </div>

    </section>
    <!-- End About Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Kontak Kami</span>
            <h2>Kontak</h2>
            <p></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <div class="col-xl-12">
                    <div class="info-wrap row">
                        <!-- Kolom (alamat, telp, email) -->

                        <div class="col-md-8">
                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h3>Alamat</h3>
                                    <p>Kampung karang anyar, Cingcin, Kec. Soreang, Kabupaten Bandung, Jawa Barat 14093</p>
                                </div>
                            </div>

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                <i class="bi bi-telephone flex-shrink-0"></i>
                                <div>
                                    <h3>Telp Kami</h3>
                                    <p>+62 851-7169-9066</p>
                                </div>
                            </div>

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h3>Email Kami</h3>
                                    <p>radeah96@gmail.com</p>
                                </div>
                            </div>
                        </div>


                        <!-- End Info Item -->

                        <!-- Kolom kanan (gambar) -->
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-center justify-content-center my-4"  data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('enno/assets/img/logo-hakai.png') }}"
                                 class="img-fluid flex-shrink-0"
                                 alt="Logo Hakai"
                                 style="max-width: 100%; height: auto; width: auto; max-height: 200px;">
                        </div>

                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.8767251734394!2d107.53376217563765!3d-7.023773668809473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68ed90a0920677%3A0xcbe1e25a21f08095!2sSMK%20YADIKA%20SOREANG!5e0!3m2!1sid!2sid!4v1755670460952!5m2!1sid!2sid" width="100%" height="270px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /Contact Section -->

</main>


<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{asset('enno/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('enno/assets/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('enno/assets/vendor/aos/aos.js')}}"></script>
<script src="{{asset('enno/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('enno/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('enno/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('enno/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('enno/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

<!-- Main JS File -->
<script src="{{asset('enno/assets/js/main.js')}}"></script>

</body>

</html>
