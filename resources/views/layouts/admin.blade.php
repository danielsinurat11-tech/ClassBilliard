<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Billiard Class')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        :root {
            --accent: #fa9a08;
            --sidebar-w: 260px;
            --sidebar-c: 80px;
            --panel: #111111;
            --bg: #0d0d0d;
        }

        body {
            background-color: var(--bg);
            color: #efefef;
            overflow-x: hidden;
        }

        /* Sidebar Base */
        #sidebar {
            width: var(--sidebar-w);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--panel);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar.collapsed {
            width: var(--sidebar-c);
        }

        #sidebar.collapsed:hover {
            width: var(--sidebar-w);
            box-shadow: 20px 0 40px rgba(0, 0, 0, 0.4);
        }

        #main-wrapper {
            margin-left: var(--sidebar-w);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 0;
        }

        #sidebar.collapsed~#main-wrapper {
            margin-left: var(--sidebar-c);
        }

        .sidebar-text {
            transition: opacity 0.2s;
            white-space: nowrap;
        }

        #sidebar.collapsed:not(:hover) .sidebar-text,
        #sidebar.collapsed:not(:hover) .dropdown-arrow,
        #sidebar.collapsed:not(:hover) .group-label {
            opacity: 0;
            visibility: hidden;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.02);
        }

        .submenu.show {
            max-height: 800px;
        }

        .active-link {
            color: var(--accent);
            background: rgba(250, 154, 8, 0.05);
            border-right: 3px solid var(--accent);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Custom SweetAlert Style */
        .swal2-popup-custom {
            border-radius: 24px !important;
            background: #161616 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }

        .swal2-title-custom {
            color: #fff !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .swal2-confirm-custom {
            border-radius: 12px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
        }
    </style>
</head>

<body class="font-['Poppins'] antialiased">

    <aside id="sidebar" class="fixed top-0 left-0 h-screen z-50 flex flex-col">
        <div class="h-20 flex items-center px-6 shrink-0 border-b border-white/5 overflow-hidden">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-[var(--accent)] rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-orange-500/20">
                    <i class="ri-shield-star-fill text-black text-xl"></i>
                </div>
                <span class="sidebar-text font-bold text-lg tracking-tight">Billiard<span
                        class="text-[var(--accent)]">Admin</span></span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto no-scrollbar py-6 px-3 space-y-1">
            <p class="group-label text-[10px] font-bold text-gray-500 uppercase tracking-widest px-4 mb-2 text-nowrap">
                Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/5 transition-all @if(request()->routeIs('admin.dashboard')) active-link @endif">
                <i class="ri-dashboard-2-line text-xl shrink-0"></i>
                <span class="sidebar-text font-medium">Dashboard</span>
            </a>

            <div class="pt-2">
                <p
                    class="group-label text-[10px] font-bold text-gray-500 uppercase tracking-widest px-4 mb-2 text-nowrap">
                    Website CMS</p>
                <button onclick="toggleSubmenu(event)"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-white/5 transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ri-stack-line text-xl shrink-0 text-[var(--accent)]"></i>
                        <span class="sidebar-text font-medium text-nowrap">Manajemen Konten</span>
                    </div>
                    <i class="ri-arrow-down-s-line dropdown-arrow transition-transform"></i>
                </button>

                <div id="cms-submenu" class="submenu mt-1 ml-2 border-l border-white/10 space-y-1">
                    @php
                        $menus = [
                            ['r' => 'admin.hero', 'i' => 'ri-image-line', 'l' => 'Hero Section'],
                            ['r' => 'admin.tentang-kami', 'i' => 'ri-information-line', 'l' => 'Tentang Kami'],
                            ['r' => 'admin.about-founder', 'i' => 'ri-user-star-line', 'l' => 'About Founder'],
                            ['r' => 'admin.keunggulan-fasilitas', 'i' => 'ri-star-line', 'l' => 'Keunggulan'],
                            ['r' => 'admin.portfolio-achievement', 'i' => 'ri-trophy-line', 'l' => 'Portfolio'],
                            ['r' => 'admin.tim-kami', 'i' => 'ri-team-line', 'l' => 'Tim Kami'],
                            ['r' => 'admin.testimoni-pelanggan', 'i' => 'ri-chat-3-line', 'l' => 'Testimoni'],
                            ['r' => 'admin.event', 'i' => 'ri-calendar-event-line', 'l' => 'Event'],
                            ['r' => 'admin.footer', 'i' => 'ri-layout-bottom-line', 'l' => 'Footer'],
                        ];
                    @endphp
                    @foreach($menus as $m)
                        <a href="{{ route($m['r']) }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all hover:text-[var(--accent)] @if(request()->routeIs($m['r'])) text-[var(--accent)] font-semibold @else text-gray-400 @endif">
                            <i class="{{ $m['i'] }} text-lg"></i>
                            <span class="sidebar-text text-nowrap">{{ $m['l'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('admin.manage-users.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/5 transition-all @if(request()->routeIs('admin.manage-users.*')) active-link @endif">
                <i class="ri-dashboard-2-line text-xl shrink-0"></i>
                <span class="sidebar-text font-medium">Manajemen User</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/5 space-y-2">
            <a href="{{ route('home') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/5 text-gray-400 transition-all">
                <i class="ri-arrow-left-circle-line text-xl shrink-0"></i>
                <span class="sidebar-text font-medium">Kembali ke Situs</span>
            </a>
            <button onclick="toggleSidebar()"
                class="w-full h-10 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-lg transition-colors">
                <i id="collapse-icon" class="ri-side-bar-fill text-gray-400"></i>
            </button>
        </div>
    </aside>

    <div id="main-wrapper" class="min-h-screen flex flex-col">

        <header
            class="h-20 bg-[var(--bg)]/80 backdrop-blur-md border-b border-white/5 sticky top-0 z-40 flex items-center justify-between px-6 lg:px-10">
            <div class="flex flex-col min-w-0">
                <span class="text-[10px] uppercase tracking-widest text-[var(--accent)] font-bold">Admin Panel</span>
                <h1 class="text-xl font-bold truncate">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-3 p-1.5 pr-4 rounded-2xl bg-[#1a1a1a] border border-white/5 hover:border-orange-500/30 transition-all duration-300 shadow-xl group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-600 to-yellow-400 p-[2px]">
                        <div
                            class="w-full h-full rounded-[10px] bg-[#1a1a1a] flex items-center justify-center overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name ?? 'Guest') }}&background=transparent&color=fa9a08&bold=true"
                                alt="Avatar">
                        </div>
                    </div>
                    <div class="text-left hidden sm:block">
                        <p
                            class="text-sm font-bold leading-none text-white group-hover:text-[var(--accent)] transition-colors">
                            {{ Auth::user()?->name ?? 'Guest' }}
                        </p>
                        <p class="text-[10px] text-gray-500 font-medium uppercase tracking-tighter mt-1">
                            {{ Auth::user()?->role ?? 'guest' }}
                        </p>
                    </div>
                    <i class="ri-arrow-down-s-line text-gray-500 transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="absolute right-0 mt-3 w-64 origin-top-right bg-[#161616] border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-2 z-50 backdrop-blur-xl"
                    style="display: none;">

                    <div class="px-4 py-3 border-b border-white/5 mb-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Akun Personal</p>
                    </div>

                    <a href="{{ route('admin.profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-[var(--accent)] transition-all mx-2 rounded-xl">
                        <i class="ri-user-settings-line text-lg"></i>
                        <span>Edit Profil</span>
                    </a>

                    <div class="px-2 pt-1 border-t border-white/5 mt-1">
                        <button type="button" @click="handleLogout"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 transition-all rounded-xl">
                            <i class="ri-logout-circle-r-line text-lg"></i>
                            <span class="font-semibold">Keluar Sistem</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-10 w-full">
            <div class="w-full max-w-[1400px] mx-auto">
                @yield('content')
            </div>
        </main>

        <footer class="px-10 py-6 border-t border-white/5 text-gray-600 text-xs flex justify-between items-center">
            <p>&copy; {{ date('Y') }} Billiard CMS System.</p>
            <p class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System v1.0.4-stable
            </p>
        </footer>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('collapse-icon');
        const submenu = document.getElementById('cms-submenu');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            const isCol = sidebar.classList.contains('collapsed');
            icon.classList.toggle('ri-side-bar-fill', !isCol);
            icon.classList.toggle('ri-layout-column-line', isCol);
            localStorage.setItem('sb-state', isCol);
        }

        if (localStorage.getItem('sb-state') === 'true') toggleSidebar();

        function toggleSubmenu(e) {
            if (sidebar.classList.contains('collapsed')) toggleSidebar();
            submenu.classList.toggle('show');
            e.currentTarget.querySelector('.dropdown-arrow').style.transform =
                submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        // Logic Logout SweetAlert2
        const handleLogout = () => {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                html: '<p style="color: #999; font-size: 14px;">Apakah Anda yakin ingin mengakhiri sesi administrasi ini?</p>',
                icon: 'warning',
                iconColor: '#fa9a08',
                background: '#161616',
                showCancelButton: true,
                confirmButtonColor: '#fa9a08',
                cancelButtonColor: '#222',
                confirmButtonText: 'YA, LOGOUT',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-confirm-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mohon Tunggu...',
                        background: '#161616',
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        };

        // Auto Open Submenu if active
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('#cms-submenu .text-\\[var\\(--accent\\)\\]')) {
                submenu.classList.add('show');
                const arrow = document.querySelector('.dropdown-arrow');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            }
        });
    </script>

    @stack('scripts')
</body>

</html>