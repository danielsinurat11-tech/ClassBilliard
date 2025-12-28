{{-- Header dengan Profile dan Theme Toggle --}}
<header class="h-16 px-8 flex items-center justify-between sticky top-0 z-40 bg-white/80 dark:bg-[#050505]/80 backdrop-blur-md border-b border-slate-200 dark:border-white/5">
    <div class="flex items-center gap-4">
        <h1 class="text-sm font-bold dark:text-white tracking-tight">{{ $pageTitle ?? 'Dashboard Dapur' }}</h1>
    </div>

    <div class="flex items-center gap-4">
        {{-- Theme Switcher --}}
        <button @click="toggleTheme()"
            class="w-8 h-8 rounded-md border border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-[#fa9a08] transition-all">
            <i x-show="!darkMode" class="ri-moon-line text-sm"></i>
            <i x-show="darkMode" class="ri-sun-line text-sm text-[#fa9a08]" x-cloak></i>
        </button>

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2.5 group">
                <img class="w-8 h-8 rounded-md object-cover border border-slate-200 dark:border-white/10"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name) }}&background=fa9a08&color=000&bold=true"
                    alt="">
                <div class="text-left hidden md:block">
                    <p class="text-[11px] font-bold dark:text-white leading-none group-hover:text-[#fa9a08] transition-colors">
                        {{ Auth::user()?->name }}</p>
                </div>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-3 w-52 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/10 rounded-lg shadow-xl p-1 z-50">
                <button @click="handleLogout()"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-xs font-bold text-red-500 hover:bg-red-500/5 transition-all text-left">
                    <i class="ri-logout-box-r-line text-sm"></i> Sign Out
                </button>
            </div>
        </div>
    </div>
</header>

