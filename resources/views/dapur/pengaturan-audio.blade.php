@extends('layouts.app')

@section('title', 'Pengaturan Audio - Billiard Class')

@push('head')
    {{-- Initialize theme immediately in head --}}
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch(e) {
                console.error('Theme initialization error:', e);
            }
        })();
    </script>
@endpush

@push('styles')
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

    /* Sidebar Expansion Animation */
    .sidebar-animate {
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s ease;
    }

    .sidebar {
        width: 280px;
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .sidebar.collapsed {
        transform: translateX(-100%);
    }
    .sidebar-desktop-collapsed {
        width: 80px;
    }
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    /* Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .main-content.expanded {
        margin-left: 0;
    }
    .main-content.desktop-collapsed {
        margin-left: 80px;
    }
    .sidebar-menu-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .sidebar-menu-item.active {
        background-color: #fa9a08;
        color: #000 !important;
        font-weight: 600;
    }
    .sidebar-menu-item.active i {
        color: #000 !important;
    }
    .sidebar-menu-item.active span {
        color: #000 !important;
    }
    .sidebar-menu-item {
        display: flex;
        align-items: center;
    }
    /* Responsive Styles for Tablet and Mobile */
    @media (max-width: 1024px) {
        .sidebar {
            position: fixed;
            z-index: 9999;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 280px;
        }
        .sidebar:not(.collapsed) {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }
    }
</style>
@endpush

@section('content')
    {{-- Initialize theme before Alpine.js loads --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <div class="flex min-h-screen bg-gray-50 dark:bg-[#050505] theme-transition text-black dark:text-slate-200" x-data="themeManager()" x-init="initTheme()">
        {{-- Sidebar --}}
        <aside id="sidebar" 
            @mouseenter="if(sidebarCollapsed) sidebarHover = true" 
            @mouseleave="sidebarHover = false"
            class="sidebar fixed lg:static top-0 left-0 h-screen theme-transition border-r border-gray-100 dark:border-white/5 bg-white dark:bg-[#0A0A0A] z-50 flex flex-col sidebar-animate"
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
                <a href="{{ route('dapur') }}" id="menu-orders" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('dapur') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
                    <i class="ri-shopping-cart-2-line text-lg"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Orderan</span>
                </a>
                <a href="{{ route('reports') }}" id="menu-reports" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('reports') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
                    <i class="ri-file-chart-2-line text-lg"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Laporan</span>
                </a>
                <a href="{{ route('pengaturan-audio') }}" id="menu-audio" class="sidebar-menu-item flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('pengaturan-audio') ? 'active-link' : 'hover:bg-gray-100 dark:hover:bg-white/5 text-black dark:text-slate-400' }}">
                    <i class="ri-settings-3-line text-lg"></i>
                    <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity class="font-bold text-xs tracking-tight whitespace-nowrap">Pengaturan Audio</span>
                </a>
            </nav>

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

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Header dengan Profile dan Theme Toggle --}}
            <header class="h-16 px-8 flex items-center justify-between sticky top-0 z-40 bg-white/90 dark:bg-[#050505]/80 backdrop-blur-md border-b border-gray-100 dark:border-white/5">
                <div class="flex items-center gap-4">
                    {{-- Mobile Sidebar Toggle --}}
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-black dark:text-slate-200">
                        <i class="ri-menu-line text-2xl"></i>
                    </button>
                    <h1 class="text-sm font-bold text-black dark:text-white tracking-tight hidden lg:block">Pengaturan Audio</h1>
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

            <div class="flex-1 p-8 md:p-12 min-h-screen">
                <div class="w-full">
                    {{-- Audio Settings Card --}}
                    <div class="bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 p-6 rounded-2xl shadow-sm dark:shadow-none" x-data="kitchenNotification()">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ri-settings-3-line text-[#fa9a08]"></i>
                            Pengaturan Audio Notifikasi
                        </h2>
                        
                        {{-- Audio Settings --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Pilih Audio Notifikasi</label>
                            
                            {{-- Current Audio Display --}}
                            <div x-show="selectedAudio" class="mb-4 p-3 bg-gray-100 dark:bg-black/20 rounded-lg border border-gray-300 dark:border-white/10">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Audio Saat Ini:</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="getCurrentAudioName() || 'Tidak ada audio dipilih'"></p>
                            </div>
                            <div x-show="!selectedAudio" class="mb-4 p-3 bg-gray-100 dark:bg-gray-500/10 border border-gray-300 dark:border-gray-500/20 rounded-lg">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Audio Saat Ini:</p>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-500">Tidak ada audio</p>
                            </div>
                            
                            {{-- File Picker untuk Pilih/Upload Audio --}}
                            <div class="mb-4">
                                <input type="file" @change="handleAudioFileSelect($event)" accept="audio/*" 
                                    class="hidden" x-ref="audioFilePicker">
                                <button @click="openFilePicker()" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-3 px-4 rounded-lg transition-all mb-3">
                                    <i class="ri-file-music-line"></i> Pilih File Audio
                                </button>
                                
                                {{-- Preview Selected File --}}
                                <div x-show="previewFile" class="mb-3 p-3 bg-green-50 dark:bg-green-500/10 border border-green-300 dark:border-green-500/20 rounded-lg">
                                    <p class="text-xs text-green-600 dark:text-green-400 mb-1">File Dipilih:</p>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium" x-text="previewFile.name"></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400" x-text="formatFileSize(previewFile.size)"></p>
                                </div>
                                
                                {{-- Options untuk File yang Dipilih --}}
                                <div x-show="previewFile" class="space-y-3">
                                    <input type="text" x-model="newAudioName" placeholder="Nama audio (opsional)" 
                                        class="w-full bg-white dark:bg-black/40 border border-gray-300 dark:border-white/10 rounded-lg py-2 px-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50">
                                    
                                    <div class="flex gap-2">
                                        <button @click="useAudioDirectly()" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-all">
                                            <i class="ri-check-line"></i> Gunakan Langsung
                                        </button>
                                        <button @click="uploadAudioAsNew()" 
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-all">
                                            <i class="ri-upload-cloud-line"></i> Simpan ke Daftar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Test Audio Button --}}
                            <button x-show="selectedAudio" @click="testAudio()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-3 px-4 rounded-lg transition-all mb-4">
                                <i class="ri-play-line"></i> Test Audio
                            </button>
                            
                            {{-- List of Saved Audios --}}
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-3">Daftar Audio Tersimpan</label>
                                <div class="max-h-64 overflow-y-auto space-y-2">
                                    <div x-show="availableSounds.length === 0" class="text-sm text-gray-600 dark:text-gray-500 text-center py-4">
                                        Belum ada audio tersimpan
                                    </div>
                                    <template x-for="sound in availableSounds" :key="sound.id">
                                        <div class="flex items-center justify-between bg-gray-100 dark:bg-black/20 rounded-lg p-3 border border-gray-300 dark:border-white/10">
                                            <span class="text-sm text-gray-900 dark:text-white" x-text="sound.name"></span>
                                            <div class="flex items-center gap-2">
                                                <button @click="selectAudioFromList(sound)" 
                                                    class="text-sm text-blue-400 hover:text-blue-300 px-3 py-1 rounded-lg hover:bg-blue-500/10 transition-all" title="Gunakan audio ini">
                                                    <i class="ri-check-line"></i> Pilih
                                                </button>
                                                <button @click="deleteSound(sound.id)" 
                                                    class="text-sm text-red-400 hover:text-red-300 px-3 py-1 rounded-lg hover:bg-red-500/10 transition-all" title="Hapus">
                                                    <i class="ri-delete-bin-line"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Audio Element untuk Testing --}}
    <audio id="testAudioElement" preload="auto">
        <source id="testAudioSource" src="" type="audio/mpeg">
    </audio>

    @push('scripts')
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.querySelector('.main-content');

        function toggleSidebar() {
            const isMobile = window.innerWidth <= 1024;
            if (isMobile) {
                const isCollapsed = sidebar.classList.contains('collapsed');
                if (isCollapsed) {
                    sidebar.classList.remove('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.add('show');
                    }
                    document.body.style.overflow = 'hidden';
                } else {
                    sidebar.classList.add('collapsed');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('show');
                    }
                    document.body.style.overflow = '';
                }
            }
        }

        if (window.innerWidth <= 1024) {
            sidebar.classList.add('collapsed');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('collapsed');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('show');
                }
                document.body.style.overflow = '';
            } else {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                }
            }
        });

        // Menu Navigation
        const menuOrders = document.getElementById('menu-orders');
        const menuReports = document.getElementById('menu-reports');
        const menuAudio = document.getElementById('menu-audio');

        menuOrders.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebarOverlay.classList.remove('show');
            }
        });

        menuReports.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebarOverlay.classList.remove('show');
            }
        });

        menuAudio.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebarOverlay.classList.remove('show');
            }
        });

        // Alpine.js untuk Pengaturan Audio
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Alpine === 'undefined') {
                console.error('Alpine.js is not loaded');
                return;
            }

            Alpine.data('kitchenNotification', () => ({
                availableSounds: [],
                selectedAudio: localStorage.getItem('kitchenNotificationAudio') || '',
                audioElement: null,
                newAudioName: '',
                previewFile: null,
                currentAudioName: '',

                init() {
                    this.audioElement = document.getElementById('testAudioElement');
                    this.loadSavedAudioPreference();
                    this.loadAvailableSounds();
                },

                async loadAvailableSounds() {
                    try {
                        const response = await fetch('/notification-sounds');
                        if (response.ok) {
                            this.availableSounds = await response.json();
                        }
                    } catch (error) {
                        console.error('Error loading sounds:', error);
                    }
                },

                loadSavedAudioPreference() {
                    const savedAudio = localStorage.getItem('kitchenNotificationAudio');
                    const audioType = localStorage.getItem('kitchenNotificationAudioType');
                    
                    if (savedAudio && savedAudio !== '') {
                        if (audioType === 'database') {
                            // Will be set after availableSounds loads
                            setTimeout(() => {
                                if (this.availableSounds.find(s => s.filename === savedAudio)) {
                                    this.selectedAudio = savedAudio;
                                    const sound = this.availableSounds.find(s => s.filename === savedAudio);
                                    this.currentAudioName = sound ? sound.name : '';
                                    this.updateAudioSource();
                                } else {
                                    this.selectedAudio = '';
                                    this.currentAudioName = '';
                                    localStorage.removeItem('kitchenNotificationAudio');
                                    localStorage.removeItem('kitchenNotificationAudioType');
                                }
                            }, 500);
                        } else {
                            // File type - not persistent, clear it
                            this.selectedAudio = '';
                            this.currentAudioName = '';
                            localStorage.removeItem('kitchenNotificationAudio');
                            localStorage.removeItem('kitchenNotificationAudioType');
                        }
                    } else {
                        this.selectedAudio = '';
                        this.currentAudioName = '';
                    }
                },

                updateAudioSource() {
                    const source = document.getElementById('testAudioSource');
                    if (!source || !this.audioElement) return;

                    if (!this.selectedAudio || this.selectedAudio === '') {
                        source.src = '';
                        this.audioElement.pause();
                        return;
                    }
                    
                    // Check if selectedAudio is a file object (direct file)
                    if (this.selectedAudio instanceof File) {
                        source.src = URL.createObjectURL(this.selectedAudio);
                    } else {
                        // It's a filename from database
                        const sound = this.availableSounds.find(s => s.filename === this.selectedAudio);
                        if (sound) {
                            // Use storage path if file exists in storage
                            if (sound.file_path.startsWith('sounds/')) {
                                source.src = '{{ asset("storage") }}/' + sound.file_path;
                            } else {
                                source.src = '{{ asset("assets/sounds") }}/' + sound.filename;
                            }
                        } else {
                            // No sound found, clear source
                            source.src = '';
                            return;
                        }
                    }
                    
                    if (this.audioElement) {
                        this.audioElement.load();
                    }
                },

                getCurrentAudioName() {
                    if (this.selectedAudio instanceof File) {
                        return this.selectedAudio.name;
                    }
                    const sound = this.availableSounds.find(s => s.filename === this.selectedAudio);
                    return sound ? sound.name : 'Tidak ada audio';
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                openFilePicker() {
                    if (this.$refs.audioFilePicker) {
                        this.$refs.audioFilePicker.click();
                    }
                },

                handleAudioFileSelect(event) {
                    const file = event.target?.files?.[0] || event?.files?.[0];
                    if (!file) return;
                    
                    // Validate file type
                    if (!file.type.startsWith('audio/')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File tidak valid',
                            text: 'Harap pilih file audio (mp3, wav, ogg)',
                            background: '#161616',
                            color: '#fff'
                        });
                        return;
                    }
                    
                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File terlalu besar',
                            text: 'Ukuran file maksimal 2MB',
                            background: '#161616',
                            color: '#fff'
                        });
                        return;
                    }
                    
                    this.previewFile = file;
                    // Auto-generate name from filename if not provided
                    if (!this.newAudioName) {
                        this.newAudioName = file.name.replace(/\.[^/.]+$/, '');
                    }
                },

                useAudioDirectly() {
                    if (!this.previewFile) return;
                    
                    this.selectedAudio = this.previewFile;
                    this.currentAudioName = this.previewFile.name;
                    this.saveAudioPreference();
                    
                    // Clear preview
                    this.previewFile = null;
                    this.newAudioName = '';
                    if (this.$refs.audioFilePicker) {
                        this.$refs.audioFilePicker.value = '';
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Audio berhasil dipilih',
                        background: '#161616',
                        color: '#fff',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },

                async uploadAudioAsNew() {
                    if (!this.previewFile) return;
                    
                    if (!this.newAudioName) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nama audio diperlukan',
                            text: 'Harap isi nama audio',
                            background: '#161616',
                            color: '#fff'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', this.newAudioName);
                    formData.append('audio', this.previewFile);

                    try {
                        const response = await fetch('/notification-sounds', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Audio berhasil disimpan',
                                background: '#161616',
                                color: '#fff'
                            });
                            
                            // Reload sounds
                            await this.loadAvailableSounds();
                            
                            // Auto-select the newly uploaded sound
                            if (data.sound) {
                                this.selectedAudio = data.sound.filename;
                                this.currentAudioName = data.sound.name;
                                this.saveAudioPreference();
                            }
                            
                            // Clear preview
                            this.previewFile = null;
                            this.newAudioName = '';
                            if (this.$refs.audioFilePicker) {
                                this.$refs.audioFilePicker.value = '';
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal upload audio',
                                background: '#161616',
                                color: '#fff'
                            });
                        }
                    } catch (error) {
                        console.error('Error uploading audio:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat upload audio',
                            background: '#161616',
                            color: '#fff'
                        });
                    }
                },

                async selectAudioFromList(sound) {
                    this.selectedAudio = sound.filename;
                    this.currentAudioName = sound.name;
                    this.saveAudioPreference();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Audio berhasil dipilih',
                        background: '#161616',
                        color: '#fff',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },

                saveAudioPreference() {
                    // Save filename if it's from database, or save file name if it's a direct file
                    if (this.selectedAudio instanceof File) {
                        localStorage.setItem('kitchenNotificationAudio', this.selectedAudio.name);
                        localStorage.setItem('kitchenNotificationAudioType', 'file');
                    } else {
                        localStorage.setItem('kitchenNotificationAudio', this.selectedAudio);
                        localStorage.setItem('kitchenNotificationAudioType', 'database');
                    }
                    this.updateAudioSource();
                },

                testAudio() {
                    if (!this.selectedAudio || this.selectedAudio === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak ada audio',
                            text: 'Harap pilih audio terlebih dahulu',
                            background: '#161616',
                            color: '#fff',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }
                    
                    // Ensure audio source is updated before testing
                    this.updateAudioSource();
                    
                    // Wait a bit for audio to load
                    setTimeout(() => {
                        const source = document.getElementById('testAudioSource');
                        if (!this.audioElement) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Audio element tidak ditemukan',
                                background: '#161616',
                                color: '#fff',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            return;
                        }
                        
                        if (!source || !source.src || source.src === '') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Audio source tidak tersedia. Pastikan audio sudah dipilih dengan benar.',
                                background: '#161616',
                                color: '#fff',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            return;
                        }
                        
                        // Reset and play
                        this.audioElement.currentTime = 0;
                        this.audioElement.play().catch(err => {
                            console.error('Audio test failed:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal memutar audio',
                                text: 'Pastikan file audio tersedia dan formatnya didukung (mp3, wav, ogg)',
                                background: '#161616',
                                color: '#fff',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                    }, 100);
                },

                async deleteSound(soundId) {
                    Swal.fire({
                        title: 'Hapus Audio?',
                        text: 'Audio yang dihapus tidak dapat dikembalikan',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        background: '#161616',
                        color: '#fff'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/notification-sounds/${soundId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Audio berhasil dihapus',
                                        background: '#161616',
                                        color: '#fff'
                                    });
                                    
                                    // Check if deleted sound was selected
                                    const deletedSound = this.availableSounds.find(s => s.id === soundId);
                                    if (deletedSound && this.selectedAudio === deletedSound.filename) {
                                        this.selectedAudio = '';
                                        this.currentAudioName = '';
                                        localStorage.removeItem('kitchenNotificationAudio');
                                        localStorage.removeItem('kitchenNotificationAudioType');
                                        this.updateAudioSource();
                                    }
                                    
                                    // Reload sounds after a short delay
                                    setTimeout(async () => {
                                        await this.loadAvailableSounds();
                                    }, 300);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message || 'Gagal menghapus audio',
                                        background: '#161616',
                                        color: '#fff'
                                    });
                                    
                                    // Reload sounds anyway to refresh the list
                                    await this.loadAvailableSounds();
                                }
                            } catch (error) {
                                console.error('Error deleting sound:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menghapus audio',
                                    background: '#161616',
                                    color: '#fff'
                                });
                            }
                        }
                    });
                }
            }));
        });
    </script>

    {{-- Hidden Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    {{-- Theme Manager Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                sidebarCollapsed: false,
                sidebarHover: false,
                darkMode: false, // Will be set in initTheme()

                initTheme() {
                    // Set initial theme based on cookie, localStorage, or system preference
                    const cookieTheme = document.cookie.split('; ').find(row => row.startsWith('theme='))?.split('=')[1];
                    const savedTheme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    
                    // Prioritize cookie, then localStorage, then system preference
                    const theme = cookieTheme || savedTheme || (prefersDark ? 'dark' : 'light');
                    
                    this.darkMode = theme === 'dark';
                    
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Sync localStorage with cookie if cookie exists
                    if (cookieTheme && cookieTheme !== savedTheme) {
                        localStorage.setItem('theme', cookieTheme);
                    }
                    
                    console.log('Theme initialized:', this.darkMode ? 'dark' : 'light', 'Cookie:', cookieTheme, 'LocalStorage:', savedTheme);
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    const theme = this.darkMode ? 'dark' : 'light';
                    
                    // Set localStorage
                    localStorage.setItem('theme', theme);
                    
                    // Set cookie untuk persist antar reload dan bisa dipakai server-side
                    document.cookie = `theme=${theme}; path=/; max-age=31536000`;
                    
                    // Update DOM class
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Force re-render untuk memastikan perubahan terlihat
                    this.$nextTick(() => {
                        console.log('Theme toggled:', theme);
                    });
                },

                updateTheme() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                handleLogout() {
                    Swal.fire({
                        title: 'Confirm Logout',
                        text: 'Are you sure you want to logout?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#fa9a08',
                        cancelButtonColor: '#1e1e1e',
                        confirmButtonText: 'Yes, Sign Out',
                        background: this.darkMode ? '#0A0A0A' : '#fff',
                        color: this.darkMode ? '#fff' : '#000',
                        customClass: {
                            popup: 'rounded-lg border border-white/5',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                }
            }));
        });
    </script>
    @endpush
@endsection

