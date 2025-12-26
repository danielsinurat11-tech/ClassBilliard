<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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

        {{-- SweetAlert2 untuk popup --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-['Poppins',system-ui,-apple-system,BlinkMacSystemFont,'Segoe_UI',sans-serif] bg-black text-white">
        @if(!request()->routeIs('dapur'))
        <header class="fixed top-0 left-0 right-0 w-full z-50 py-4 md:py-6 bg-[#1A1A1A] border-b border-white/5">
            <div class="max-w-[1024px] mx-auto px-4 md:px-6">
                <div class="flex items-center justify-between">
                    <div class="logo">
                        <h1 class="text-xl md:text-[1.75rem] font-bold">Class Billiard</h1>
                    </div>

                    <ul class="list-none flex gap-8 md:gap-12 items-center max-md:fixed max-md:top-14 max-md:right-0 max-md:flex-col max-md:w-48 max-md:h-screen max-md:bg-black max-md:p-[5rem_2rem_3rem] max-md:gap-6 max-md:shadow-[0_0_40px_rgba(0,0,0,0.5)] max-md:opacity-0 max-md:invisible max-md:translate-x-4 max-md:transition-[opacity,transform,visibility] max-md:duration-200 max-md:ease-linear" id="navbar-menu">
                        <li><a href="{{ route('home') }}" class="text-sm md:text-[0.95rem] font-medium transition-colors duration-150 ease-linear hover:text-amber-400">Beranda</a></li>
                        <li><a href="{{ route('menu') }}" class="text-sm md:text-[0.95rem] font-medium transition-colors duration-150 ease-linear hover:text-amber-400">Orders</a></li>
                    </ul>

                    <div class="hidden max-md:block cursor-pointer" id="navbar-toggle">
                        <i class="ri-menu-3-line text-2xl"></i>
                    </div>
                </div>
            </div>
        </header>
        @endif

        <main class="@if(!request()->routeIs('dapur'))pt-0 @endif bg-black">
            @yield('content')
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


