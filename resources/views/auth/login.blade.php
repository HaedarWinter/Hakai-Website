<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hakai | {{$title}}</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- [Favicon] -->
    <link href="{{asset('enno/assets/img/logo-hakai.png')}}" rel="icon">

    <!-- Vendor CSS Files (Enno) -->
    <link href="{{asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('enno/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{asset('enno/assets/css/main.css')}}" rel="stylesheet">


    <!-- [Fonts & CSS] -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/tabler-icons.min.css')}}" >
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/feather.css')}}" >
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/fontawesome.css')}}" >
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/fonts/material.css')}}" >
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/css/style.css')}}" id="main-style-link" >
    <link rel="stylesheet" href="{{asset('hakai/dist/assets/css/style-preset.css')}}" >
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
                    <img src="{{ asset('enno/assets/img/logo-hakai.png') }}" alt="Logo Hakai" style="height: 60px; width: auto; margin-right: 10px;">
                    <span style="font-size: 24px; font-weight: bold; color: #6C3AF0;">HAKAI</span>
                </div>
            </div>

            <!-- Card Form -->
            <div class="card my-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h3 class="mb-0"><b>Masuk</b></h3>
                        <img class="text-center" src="{{ asset('enno/assets/img/logo-hakai.png') }}" alt="Logo Hakai" style="height: 30px; width: auto; margin-right: 10px;">
                    </div>

                    <!-- Form Login -->
                    <form method="POST" action="{{ route('loginProses') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <span class="text-danger">
                                <label class="form-label">Email Address</label>
                                *
                            </span>

                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Email Address">
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <span class="text-danger">
                                <label class="form-label">Password</label>
                                *</span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   placeholder="Password">
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-getstarted">Masuk</button>
                        </div>
                    </form>



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
                        <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="#">Contact us</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- [ JS ] -->
<script src="{{asset('hakai/dist/assets/js/plugins/popper.min.js')}}"></script>
<script src="{{asset('hakai/dist/assets/js/plugins/simplebar.min.js')}}"></script>
<script src="{{asset('hakai/dist/assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('hakai/dist/assets/js/fonts/custom-font.js')}}"></script>
<script src="{{asset('hakai/dist/assets/js/pcoded.js')}}"></script>
<script src="{{asset('hakai/dist/assets/js/plugins/feather.min.js')}}"></script>
<script src="{{asset('sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

@session('success')
<script>
    Swal.fire({
        title: "Yeay!",
        text: "{{session('success')}}!",
        icon: "success"
    });
</script>
@endsession

@session('error')
<script>
    Swal.fire({
        title: "Yahh!",
        text: "{{session('error')}}!",
        icon: "error"
    });
</script>
@endsession

</body>
</html>
