{{-- Header dengan Profile dan Theme Toggle --}}
<header class="h-16 px-8 flex items-center justify-between sticky top-0 z-40 bg-white/90 dark:bg-[#050505]/80 backdrop-blur-md border-b border-gray-100 dark:border-white/5">
    <div class="flex items-center gap-4">
        {{-- Mobile Sidebar Toggle --}}
        <button id="mobile-sidebar-toggle" class="lg:hidden text-black dark:text-slate-200">
            <i class="ri-menu-line text-2xl"></i>
        </button>
        <h1 class="text-sm font-bold text-black dark:text-white tracking-tight hidden lg:block">{{ $pageTitle ?? 'Dashboard Dapur' }}</h1>
    </div>

    <div class="flex items-center gap-4">
        {{-- Theme Switcher --}}
        <button @click="toggleTheme()"
            class="w-8 h-8 rounded-md border border-gray-200 dark:border-white/10 flex items-center justify-center hover:border-[#fa9a08] transition-all bg-white dark:bg-transparent">
            <i x-show="!darkMode" class="ri-moon-line text-sm text-black"></i>
            <i x-show="darkMode" class="ri-sun-line text-sm text-[#fa9a08]" x-cloak></i>
        </button>

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2.5 group">
                <img class="w-8 h-8 rounded-md object-cover border border-gray-200 dark:border-white/10"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name) }}&background=fa9a08&color=000&bold=true"
                    alt="">
                <div class="text-left hidden md:block">
                    <p class="text-[11px] font-bold text-black dark:text-white leading-none group-hover:text-[#fa9a08] transition-colors">
                        {{ Auth::user()?->name }}</p>
                </div>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-3 w-52 bg-white dark:bg-[#0A0A0A] border border-gray-200 dark:border-white/10 rounded-lg shadow-xl p-1 z-50">
                <button @click="handleLogout()"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-xs font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/5 transition-all text-left">
                    <i class="ri-logout-box-r-line text-sm"></i> Sign Out
                </button>
            </div>
        </div>
    </div>
</header>

