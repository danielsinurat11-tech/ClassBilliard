<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Billiard Class')</title>

        {{-- Google Font Poppins (mirip Next.js layout) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Remix Icon untuk icon menu --}}
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css"
        />

        @stack('styles')

        <style>
            :root {
                --container-width: 1024px;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background-color: #000;
                color: #fff;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            img {
                max-width: 100%;
                height: auto;
                display: block;
            }

            .container {
                max-width: var(--container-width);
                margin: 0 auto;
                padding-inline: 1rem;
            }

            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                width: 100%;
                z-index: 50;
                padding-block: 1.5rem;
                background-color: #000;
            }

            .navbar-box {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .navbar .logo h1 {
                font-size: 1.75rem;
                font-weight: 700;
            }

            .menu {
                list-style: none;
                display: flex;
                gap: 3rem;
                align-items: center;
            }

            .menu li a {
                font-size: 0.95rem;
                font-weight: 500;
                transition: color 0.15s ease;
            }

            .menu li a:hover {
                color: #fbbf24;
            }

            .menu-toggle {
                display: none;
                cursor: pointer;
            }

            .menu-toggle i {
                font-size: 1.5rem;
            }

            main.page {
                padding-top: 6rem; /* offset navbar */
                padding-bottom: 3rem;
            }

            /* home */

            .hero-title {
                padding-block: 3rem 2rem;
            }

            .hero-title h1 {
                font-size: clamp(15rem);
                line-height: 1.2;
                font-weight: 700;
                text-align: left;
            }



            .hero-layout {
                display: flex;
                flex-direction: column;
                gap: 2.5rem;
                align-items: center;
            }

            .hero-image-wrapper {
                max-width: 28rem;
                width: 100%;
                flex-shrink: 0;
            }

            .hero-copy {
                width: 100%;
            }

            .hero-copy p {
                padding-block: 1rem;
                font-size: 1.125rem;
                line-height: 1.7;
                text-align: center;
            }

            .section-fasilitas {
                padding-block: 1rem;
            }


            .fasilitas h1{
                text-align: center;
            }

            .fasilitas p{
                padding: 20px;
                text-align: center;
                font-size: 13px
            }
            /* home */

            @media (min-width: 1024px) {
                .hero-title h1 {
                    text-align: center;
                }

                .hero-layout {
                    flex-direction: row;
                    align-items: center;
                }

                .hero-copy p {
                    text-align: left;
                }
            }

            /* Responsive menu */
            @media (max-width: 768px) {
                .menu {
                    position: fixed;
                    top: 4rem;
                    right: 0;
                    flex-direction: column;
                    width: 11rem;
                    height: 100vh;
                    background-color: #000;
                    padding: 5rem 2rem 3rem;
                    gap: 1.5rem;
                    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
                    opacity: 0;
                    visibility: hidden;
                    transform: translateX(16px);
                    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
                }

                .menu.menu--active {
                    opacity: 1;
                    visibility: visible;
                    transform: translateX(0);
                }

                .menu-toggle {
                    display: block;
                }
            }
        </style>
    </head>
    <body>
        <header class="navbar">
            <div class="container">
                <div class="navbar-box">
                    <div class="logo">
                        <h1>Class Billiard</h1>
                    </div>

                    <ul class="menu" id="navbar-menu">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('menu') }}">Menu</a></li>
                        <li><a href="{{ route('event') }}">Event</a></li>
                        <li><a href="{{ route('layanan') }}">Reservasi</a></li>
                        <li><a href="{{ route('kontak') }}">Kontak</a></li>
                        
                    </ul>

                    <div class="menu-toggle" id="navbar-toggle">
                        <i class="ri-menu-3-line"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="page">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <script>
            (function () {
                const toggle = document.getElementById('navbar-toggle');
                const menu = document.getElementById('navbar-menu');

                if (toggle && menu) {
                    toggle.addEventListener('click', () => {
                        menu.classList.toggle('menu--active');
                    });
                }
            })();
        </script>

        @stack('scripts')
    </body>
</html>


