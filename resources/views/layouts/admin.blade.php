<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin - Billiard Class')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css"/>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        <style>
            :root { --accent: #fa9a08; --panel: #1A1A1A; --bg: #0d0d0d; }
        </style>
    </head>
    <body class="font-['Poppins',system-ui,-apple-system,BlinkMacSystemFont,'Segoe_UI',sans-serif] bg-[var(--bg)] text-white">
        <div class="min-h-screen grid grid-cols-1 md:grid-cols-[240px_1fr]">
            <aside class="hidden md:block bg-[var(--panel)] border-r border-[var(--accent)]/20">
                <div class="px-4 py-5 border-b border-white/5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <i class="ri-shield-star-line text-[var(--accent)] text-2xl"></i>
                        <span class="font-semibold">Admin Class Billiard</span>
                    </a>
                </div>
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.dashboard')) bg-white/10 @endif"><i class="ri-dashboard-fill text-[var(--accent)]"></i><span>Dashboard</span></a>
                    <a href="{{ route('admin.hero') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.hero')) bg-white/10 @endif"><i class="ri-image-fill text-[var(--accent)]"></i><span>Hero Section</span></a>
                    <a href="{{ route('admin.tentang-kami') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.tentang-kami')) bg-white/10 @endif"><i class="ri-information-fill text-[var(--accent)]"></i><span>Tentang Kami</span></a>
                    <a href="{{ route('admin.about-founder') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.about-founder')) bg-white/10 @endif"><i class="ri-user-star-fill text-[var(--accent)]"></i><span>About Founder</span></a>
                    <a href="{{ route('admin.keunggulan-fasilitas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.keunggulan-fasilitas')) bg-white/10 @endif"><i class="ri-star-fill text-[var(--accent)]"></i><span>Keunggulan & Fasilitas</span></a>
                    <a href="{{ route('admin.portfolio-achievement') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.portfolio-achievement')) bg-white/10 @endif"><i class="ri-trophy-fill text-[var(--accent)]"></i><span>Portfolio & Achievement</span></a>
                    <a href="{{ route('admin.tim-kami') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.tim-kami')) bg-white/10 @endif"><i class="ri-team-fill text-[var(--accent)]"></i><span>Tim Kami</span></a>
                    <a href="{{ route('admin.testimoni-pelanggan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.testimoni-pelanggan')) bg-white/10 @endif"><i class="ri-chat-quote-fill text-[var(--accent)]"></i><span>Testimoni Pelanggan</span></a>
                    <a href="{{ route('admin.event') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.event')) bg-white/10 @endif"><i class="ri-calendar-event-fill text-[var(--accent)]"></i><span>Event</span></a>
                    <a href="{{ route('admin.footer') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 @if(request()->routeIs('admin.footer')) bg-white/10 @endif"><i class="ri-footer-fill text-[var(--accent)]"></i><span>Footer</span></a>
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5"><i class="ri-arrow-left-line text-white"></i><span>Kembali ke Home</span></a>
                </nav>
            </aside>

            <div class="flex flex-col min-h-screen">
                <header class="md:hidden sticky top-0 z-50 bg-[var(--panel)] border-b border-white/5">
                    <div class="px-4 py-3 flex items-center justify-between">
                        <button id="admin-menu-toggle" class="text-white"><i class="ri-menu-3-line text-2xl"></i></button>
                        <div class="flex items-center gap-2">
                            <i class="ri-shield-star-line text-[var(--accent)] text-xl"></i>
                            <span class="font-semibold">Admin</span>
                        </div>
                    </div>
                </header>
                <main class="p-4 md:p-8">@yield('content')</main>
            </div>
        </div>

        <script>
            (function(){
                const toggle = document.getElementById('admin-menu-toggle');
                if(!toggle) return;
                toggle.addEventListener('click', () => {
                    // Simple offcanvas menu for mobile
                    alert('Buka menu admin pada layar kecil belum diimplementasikan penuh. Gunakan layar lebar untuk navigasi.');
                });
            })();
        </script>

        @stack('scripts')
    </body>
</html>

