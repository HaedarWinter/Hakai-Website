<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Glitch</title>

    <style>
        body {
            background: #0d0d0d;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Glitch Container */
        .glitch {
            font-size: 120px;
            font-weight: 900;
            position: relative;
            color: #fff;
            animation: glitch-skew 1s infinite linear alternate-reverse;
        }

        /* RGB split clones */
        .glitch:before,
        .glitch:after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Red shadow */
        .glitch:before {
            left: 2px;
            text-shadow: -2px 0 red;
            animation: glitch-anim 2s infinite ease-in-out alternate-reverse;
        }

        /* Blue shadow */
        .glitch:after {
            left: -2px;
            text-shadow: -2px 0 #00eaff;
            animation: glitch-anim2 2s infinite ease-in-out alternate-reverse;
        }

        /* Subtext */
        .sub {
            margin-top: -10px;
            font-size: 20px;
            color: #b3b3b3;
        }

        /* Button */
        a {
            color: #00eaff;
            margin-top: 20px;
            font-size: 18px;
            text-decoration: none;
        }

        /* Scanlines */
        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: repeating-linear-gradient(to bottom,
                    rgba(255, 255, 255, 0.03) 0px,
                    rgba(255, 255, 255, 0.03) 1px,
                    transparent 1px,
                    transparent 4px);
            animation: scanline 6s linear infinite;
        }

        /* ANIMATIONS */
        @keyframes glitch-anim {
            0% {
                clip-path: inset(0 0 80% 0);
            }

            20% {
                clip-path: inset(10% 0 60% 0);
            }

            40% {
                clip-path: inset(40% 0 20% 0);
            }

            60% {
                clip-path: inset(80% 0 0 0);
            }

            80% {
                clip-path: inset(20% 0 50% 0);
            }

            100% {
                clip-path: inset(0 0 80% 0);
            }
        }

        @keyframes glitch-anim2 {
            0% {
                clip-path: inset(80% 0 0 0);
            }

            20% {
                clip-path: inset(40% 0 40% 0);
            }

            40% {
                clip-path: inset(20% 0 60% 0);
            }

            60% {
                clip-path: inset(0 0 80% 0);
            }

            80% {
                clip-path: inset(30% 0 40% 0);
            }

            100% {
                clip-path: inset(80% 0 0 0);
            }
        }

        @keyframes glitch-skew {
            0% {
                transform: skew(0deg);
            }

            20% {
                transform: skew(5deg);
            }

            40% {
                transform: skew(2deg);
            }

            60% {
                transform: skew(8deg);
            }

            80% {
                transform: skew(-5deg);
            }

            100% {
                transform: skew(0deg);
            }
        }

        @keyframes scanline {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }
    </style>
</head>

<body>

    <div class="glitch" data-text="404">404</div>
    <p class="sub">Halaman Tidak Ditemukan</p>

    @php
        $isAuthenticated = auth()->check();
        $returnUrl = $isAuthenticated ? route('dashboard') : route('login');
        $returnText = $isAuthenticated ? 'Halaman Utama' : 'Login';
    @endphp

    <a href="{{ $returnUrl }}" class="text-primary">
        ← Kembali ke {{ $returnText }}
    </a>

    <div class="scanline"></div>

</body>

</html>