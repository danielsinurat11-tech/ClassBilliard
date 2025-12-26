<!DOCTYPE html>
<html lang="id" x-data="themeManager" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Billiard Class')</title>

    <!-- Typography: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        .theme-transition {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Standardized Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #1e1e1e;
        }

        /* Professional Link State */
        .active-link {
            background-color: #fa9a08;
            color: #000 !important;
        }

        .submenu-active {
            color: #fa9a08 !important;
            font-weight: 700;
        }

        /* Sidebar Expansion Animation */
        .sidebar-animate {
            transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s ease;
        }
    </style>
</head>

<body
    class="font-['Plus_Jakarta_Sans'] antialiased theme-transition bg-white dark:bg-[#050505] text-slate-900 dark:text-slate-200"
    x-data="{ sidebarCollapsed: false, sidebarHover: false }">

    <!-- SIDEBAR -->
    <aside @mouseenter="if(sidebarCollapsed) sidebarHover = true" @mouseleave="sidebarHover = false"
        class="fixed top-0 left-0 h-screen z-50 theme-transition border-r border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0A0A0A] flex flex-col sidebar-animate"
        :class="[(sidebarCollapsed && !sidebarHover) ? 'w-20' : 'w-72', (sidebarCollapsed && sidebarHover) ? 'shadow-[20px_0_50px_rgba(0,0,0,0.2)] dark:shadow-[20px_0_50px_rgba(0,0,0,0.5)]' : '']">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 shrink-0 border-b border-slate-200 dark:border-white/5 overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#fa9a08] rounded-lg flex items-center justify-center shrink-0">
                    <i class="ri-shield-star-fill text-black text-lg"></i>
                </div>
                <div x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms
                    class="flex flex-col whitespace-nowrap">
                    <span class="font-bold text-sm tracking-tight dark:text-white uppercase leading-none">Billiard
                        Admin</span>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Core System</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-8 no-scrollbar">
            <!-- Group: Main -->
            <div>
                <p x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                    class="text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-widest px-4 mb-4">
                    Main</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-dashboard-2-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Dashboard Overview</span>
                    </a>
                </div>
            </div>

            <!-- Group: Website CMS -->
            <div
                x-data="{ open: {{ request()->routeIs('admin.hero', 'admin.tentang-kami', 'admin.about-founder', 'admin.keunggulan-fasilitas', 'admin.portfolio-achievement', 'admin.tim-kami', 'admin.testimoni-pelanggan', 'admin.event', 'admin.footer') ? 'true' : 'false' }} }">
                <p x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                    class="text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-widest px-4 mb-4">
                    Content</p>

                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-200/50 dark:hover:bg-white/5 transition-all text-slate-600 dark:text-slate-400 group">
                    <div class="flex items-center gap-4">
                        <i class="ri-stack-line text-lg group-hover:text-[#fa9a08]"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Website CMS</span>
                    </div>
                    <i x-show="!sidebarCollapsed || sidebarHover"
                        class="ri-arrow-down-s-line text-xs transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && (!sidebarCollapsed || sidebarHover)" x-collapse
                    class="pl-12 space-y-1 mt-2 border-l border-slate-200 dark:border-white/5 ml-6">
                    @php
                        $cmsLinks = [
                            ['r' => 'admin.hero', 'l' => 'Hero Section'],
                            ['r' => 'admin.tentang-kami', 'l' => 'Tentang Kami'],
                            ['r' => 'admin.about-founder', 'l' => 'About Founder'],
                            ['r' => 'admin.keunggulan-fasilitas', 'l' => 'Keunggulan'],
                            ['r' => 'admin.portfolio-achievement', 'l' => 'Portfolio'],
                            ['r' => 'admin.tim-kami', 'l' => 'Tim Kami'],
                            ['r' => 'admin.testimoni-pelanggan', 'l' => 'Testimoni'],
                            ['r' => 'admin.event', 'l' => 'Event'],
                            ['r' => 'admin.footer', 'l' => 'Footer'],
                        ];
                    @endphp
                    @foreach($cmsLinks as $m)
                        <a href="{{ route($m['r']) }}"
                            class="block py-1.5 text-[11px] font-bold transition-all hover:text-[#fa9a08] {{ request()->routeIs($m['r']) ? 'submenu-active' : 'text-slate-500 dark:text-gray-500' }}">
                            {{ $m['l'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Group: Management -->
            <div>
                <p x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                    class="text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-widest px-4 mb-4">
                    Management</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.manage-users.index') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.manage-users.*') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-user-settings-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">User Access</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.orders.*') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-shopping-cart-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Orders</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.categories.*') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-price-tag-3-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Categories</span>
                    </a>
                    <a href="{{ route('admin.menus.index') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.menus.*') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-restaurant-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Menu Items</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Toggle Bottom -->
        <div class="p-4 border-t border-slate-200 dark:border-white/5">
            <button @click="sidebarCollapsed = !sidebarCollapsed; sidebarHover = false"
                class="w-full h-9 flex items-center justify-center rounded-md bg-slate-200 dark:bg-white/5 hover:bg-[#fa9a08] hover:text-black transition-all group">
                <i :class="sidebarCollapsed ? 'ri-arrow-right-s-line' : 'ri-arrow-left-s-line'" class="text-sm"></i>
            </button>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="min-h-screen flex flex-col transition-all duration-300" :class="sidebarCollapsed ? 'ml-20' : 'ml-72'">

        <!-- HEADER -->
        <header
            class="h-16 px-8 flex items-center justify-between sticky top-0 z-40 bg-white/80 dark:bg-[#050505]/80 backdrop-blur-md border-b border-slate-200 dark:border-white/5">
            <div>
                <h1 class="text-sm font-bold dark:text-white tracking-tight">@yield('title', 'Dashboard Overview')</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Switcher -->
                <button @click="toggleTheme"
                    class="w-8 h-8 rounded-md border border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-[#fa9a08] transition-all">
                    <i x-show="!darkMode" class="ri-moon-line text-sm"></i>
                    <i x-show="darkMode" class="ri-sun-line text-sm text-[#fa9a08]" x-cloak></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2.5 group">
                        <img class="w-8 h-8 rounded-md object-cover border border-slate-200 dark:border-white/10"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name) }}&background=fa9a08&color=000&bold=true"
                            alt="">
                        <div class="text-left hidden md:block">
                            <p
                                class="text-[11px] font-bold dark:text-white leading-none group-hover:text-[#fa9a08] transition-colors">
                                {{ Auth::user()?->name }}</p>
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-3 w-52 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/10 rounded-lg shadow-xl p-1 z-50">
                        <a href="{{ route('admin.profile.edit') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-md text-xs font-bold hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                            <i class="ri-user-line text-sm text-[#fa9a08]"></i> Edit Profile
                        </a>
                        <button @click="handleLogout"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-xs font-bold text-red-500 hover:bg-red-500/5 transition-all text-left">
                            <i class="ri-logout-box-r-line text-sm"></i> Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 md:p-12">
            <div class="max-w-[1600px] mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- FOOTER -->
        <footer
            class="px-12 py-6 flex justify-between items-center text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-widest border-t border-slate-100 dark:border-white/5">
            <p>&copy; {{ date('Y') }} Billiard CMS v1.0.4</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Secure Core
                </span>
            </div>
        </footer>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('theme') === 'dark' ||
                    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                },

                handleLogout() {
                    Swal.fire({
                        title: 'Confirm Logout',
                        text: "Sesi administrasi akan diakhiri.",
                        icon: 'warning',
                        showCancelButton: true,
                        background: this.darkMode ? '#0A0A0A' : '#fff',
                        color: this.darkMode ? '#fff' : '#000',
                        confirmButtonColor: '#fa9a08',
                        cancelButtonColor: '#1e1e1e',
                        confirmButtonText: 'Yes, Sign Out',
                        customClass: {
                            popup: 'rounded-lg border border-white/5',
                            confirmButton: 'rounded-md text-xs font-bold px-5 py-2.5',
                            cancelButton: 'rounded-md text-xs font-bold px-5 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) document.getElementById('logout-form').submit();
                    });
                }
            }));
        });
    </script>
    @stack('scripts')
</body>

</html>