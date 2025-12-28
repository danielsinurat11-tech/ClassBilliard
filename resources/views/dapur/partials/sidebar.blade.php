{{-- Sidebar --}}
<aside id="sidebar" 
    @mouseenter="if(sidebarCollapsed) sidebarHover = true" 
    @mouseleave="sidebarHover = false"
    class="sidebar theme-transition border-r border-gray-100 dark:border-white/5 bg-white dark:bg-[#0A0A0A] flex flex-col sidebar-animate"
    :class="[
        (sidebarCollapsed && !sidebarHover) ? 'sidebar-desktop-collapsed' : '',
        (sidebarCollapsed && sidebarHover) ? 'shadow-[20px_0_50px_rgba(0,0,0,0.2)] dark:shadow-[20px_0_50px_rgba(0,0,0,0.5)]' : ''
    ]">
    {{-- Sidebar Header --}}
    <div class="h-20 flex items-center px-6 shrink-0 border-b border-gray-100 dark:border-white/5 overflow-hidden">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-[#fa9a08] rounded-lg flex items-center justify-center shrink-0">
                <i class="ri-restaurant-line text-black text-lg"></i>
            </div>
            <div x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity.duration.300ms class="flex flex-col whitespace-nowrap">
                <span class="font-bold text-sm tracking-tight text-black dark:text-white uppercase leading-none">Dashboard Dapur</span>
                <span class="text-[9px] font-bold text-gray-600 dark:text-gray-600 uppercase tracking-widest mt-1">Kitchen System</span>
            </div>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1 no-scrollbar">
        <a href="{{ route('dapur') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('dapur') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
            <i class="ri-shopping-cart-2-line text-lg"></i>
            <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Orderan</span>
        </a>
        <a href="{{ route('reports') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('reports') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
            <i class="ri-file-chart-2-line text-lg"></i>
            <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Laporan</span>
        </a>
        <a href="{{ route('tutup-hari') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('tutup-hari') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
            <i class="ri-file-list-3-line text-lg"></i>
            <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Tutup Hari</span>
        </a>
        <a href="{{ route('pengaturan-audio') }}" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('pengaturan-audio') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
            <i class="ri-settings-3-line text-lg"></i>
            <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Pengaturan Audio</span>
        </a>
    </nav>

    {{-- Sidebar Clock --}}
    <div class="px-4 py-4 border-t border-gray-100 dark:border-white/5">
        <div class="bg-gray-100 dark:bg-white/5 rounded-lg p-4 text-center">
            <div x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="mb-2">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Waktu Saat Ini</p>
                <p class="text-2xl font-bold text-black dark:text-white" id="sidebar-clock">--:--</p>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">WIB</p>
            </div>
            <div x-show="sidebarCollapsed && !sidebarHover" x-transition.opacity class="text-center">
                <i class="ri-time-line text-xl text-gray-400 dark:text-gray-500"></i>
            </div>
        </div>
    </div>

    {{-- Sidebar Footer --}}
    <div class="p-4 border-t border-gray-100 dark:border-white/5">
        {{-- Sidebar Toggle Button --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed; sidebarHover = false" 
            class="w-full h-9 flex items-center justify-center rounded-md bg-gray-100 dark:bg-white/5 hover:bg-[#fa9a08] hover:text-black transition-all group">
            <i :class="sidebarCollapsed ? 'ri-arrow-right-s-line' : 'ri-arrow-left-s-line'" class="text-sm"></i>
        </button>
    </div>
</aside>

{{-- Sidebar Overlay untuk Mobile --}}
<div id="sidebar-overlay" class="sidebar-overlay"></div>

