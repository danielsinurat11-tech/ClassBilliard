<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Billiard Class')</title>

        {{-- Typography: Plus Jakarta Sans (selaras dengan admin dashboard) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        {{-- Hina Mincho untuk subtitle --}}
        <link href="https://fonts.googleapis.com/css2?family=Hina+Mincho&display=swap" rel="stylesheet">

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
    <body class="font-['Plus_Jakarta_Sans',system-ui,-apple-system,BlinkMacSystemFont,'Segoe_UI',sans-serif] antialiased bg-black text-white">
        @if(!request()->routeIs('dapur'))
        <header class="fixed top-0 left-0 right-0 w-full z-50 bg-[#1A1A1A]/95 backdrop-blur-md border-b border-white/10 shadow-lg">
            <div class="max-w-[1400px] mx-auto px-4 md:px-6">
                <div class="flex items-center justify-between py-4 md:py-5">
                    <div class="logo">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <h1 class="text-xl md:text-2xl font-bold bg-gradient-to-r from-white to-[#fa9a08] bg-clip-text text-transparent">Class Billiard</h1>
                        </a>
                    </div>

                    <nav class="hidden md:flex items-center gap-8">
                        <a href="{{ route('home') }}" class="text-sm font-semibold transition-colors duration-200 hover:text-[#fa9a08] {{ request()->routeIs('home') ? 'text-[#fa9a08]' : 'text-gray-300' }}">Beranda</a>
                        <a href="#tentang-kami" class="text-sm font-semibold transition-colors duration-200 hover:text-[#fa9a08] text-gray-300">Tentang Kami</a>
                        <a href="#fasilitas" class="text-sm font-semibold transition-colors duration-200 hover:text-[#fa9a08] text-gray-300">Fasilitas</a>
                        <a href="#portfolio" class="text-sm font-semibold transition-colors duration-200 hover:text-[#fa9a08] text-gray-300">Portfolio</a>
                        <a href="#event" class="text-sm font-semibold transition-colors duration-200 hover:text-[#fa9a08] text-gray-300">Event</a>
                        <a href="{{ route('menu') }}" class="bg-gradient-to-r from-[#fa9a08] to-[#e19e2b] text-black px-6 py-2.5 rounded-xl font-bold text-sm transition-all hover:shadow-lg hover:shadow-[#fa9a08]/30 hover:scale-105">
                            <i class="ri-shopping-cart-line mr-1"></i> Pesan
                        </a>
                    </nav>

                    <div class="md:hidden cursor-pointer" id="navbar-toggle">
                        <i class="ri-menu-3-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>
            
            {{-- Mobile Menu --}}
            <div class="md:hidden fixed top-[73px] left-0 right-0 bg-[#1A1A1A]/98 backdrop-blur-md border-b border-white/10 shadow-xl opacity-0 invisible translate-y-[-20px] transition-all duration-300" id="navbar-menu">
                <nav class="px-4 py-6 space-y-4">
                    <a href="{{ route('home') }}" class="block py-3 px-4 rounded-lg transition-colors duration-200 {{ request()->routeIs('home') ? 'bg-[#fa9a08]/20 text-[#fa9a08]' : 'text-gray-300 hover:bg-white/5' }} font-semibold">
                        <i class="ri-home-line mr-2"></i> Beranda
                    </a>
                    <a href="#tentang-kami" class="block py-3 px-4 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-white/5 font-semibold">
                        <i class="ri-information-line mr-2"></i> Tentang Kami
                    </a>
                    <a href="#fasilitas" class="block py-3 px-4 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-white/5 font-semibold">
                        <i class="ri-star-line mr-2"></i> Fasilitas
                    </a>
                    <a href="#portfolio" class="block py-3 px-4 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-white/5 font-semibold">
                        <i class="ri-trophy-line mr-2"></i> Portfolio
                    </a>
                    <a href="#event" class="block py-3 px-4 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-white/5 font-semibold">
                        <i class="ri-calendar-event-line mr-2"></i> Event
                    </a>
                    <a href="{{ route('menu') }}" class="block py-3 px-4 rounded-lg bg-gradient-to-r from-[#fa9a08] to-[#e19e2b] text-black font-bold text-center mt-4">
                        <i class="ri-shopping-cart-line mr-2"></i> Pesan Sekarang
                    </a>
                </nav>
            </div>
        </header>
        @endif

        <main class="@if(!request()->routeIs('dapur'))pt-[73px] @endif bg-black">
            @yield('content')
        </main>

        <script>
            (function () {
                const toggle = document.getElementById('navbar-toggle');
                const menu = document.getElementById('navbar-menu');

                if (toggle && menu) {
                    toggle.addEventListener('click', () => {
                        const isVisible = menu.classList.contains('opacity-0');
                        if (isVisible) {
                            menu.classList.remove('opacity-0', 'invisible', 'translate-y-[-20px]');
                            menu.classList.add('opacity-100', 'visible', 'translate-y-0');
                        } else {
                            menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                            menu.classList.add('opacity-0', 'invisible', 'translate-y-[-20px]');
                        }
                    });
                    
                    // Close menu when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                            menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                            menu.classList.add('opacity-0', 'invisible', 'translate-y-[-20px]');
                        }
                    });
                    
                    // Close menu when clicking on a link
                    menu.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', () => {
                            menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                            menu.classList.add('opacity-0', 'invisible', 'translate-y-[-20px]');
                        });
                    });
                }
            })();
            
            // Smooth scroll untuk anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerOffset = 100;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
