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
    <script src="https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js"></script>

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
            <a href="{{ route('admin.tables.index') }}"
                        class="flex items-center gap-4 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.tables.*') || request()->routeIs('admin.barcode.*') ? 'active-link' : 'hover:bg-slate-200/50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-400' }}">
                        <i class="ri-qr-code-line text-lg"></i>
                        <span x-show="!sidebarCollapsed || sidebarHover" x-transition.opacity
                            class="font-bold text-xs tracking-tight whitespace-nowrap">Barcode</span>
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
                <!-- Kitchen Notification -->
                <div class="relative" x-data="kitchenNotification()">
                    <button @click="openSettings = !openSettings" class="relative w-8 h-8 rounded-md border border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-[#fa9a08] transition-all">
                        <i class="ri-notification-3-line text-sm"></i>
                        <span x-show="newOrdersCount > 0" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-[8px] font-bold text-white" x-text="newOrdersCount"></span>
                        </span>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div x-show="openSettings" @click.away="openSettings = false" x-cloak
                        class="absolute right-0 mt-3 w-80 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/10 rounded-lg shadow-xl p-4 z-50">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold dark:text-white">Notifikasi Dapur</h3>
                            <button @click="openSettings = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        
                        <!-- Audio Settings -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-400 dark:text-gray-500 mb-2">Pilih Audio Notifikasi</label>
                            
                            <!-- Current Audio Display -->
                            <div x-show="selectedAudio" class="mb-2 p-2 bg-black/20 dark:bg-white/5 rounded-lg">
                                <p class="text-xs text-gray-400 dark:text-gray-500">Audio Saat Ini:</p>
                                <p class="text-xs font-medium text-white" x-text="getCurrentAudioName() || 'Tidak ada audio dipilih'"></p>
                            </div>
                            
                            <!-- File Picker untuk Pilih/Upload Audio -->
                            <div class="mb-2">
                                <input type="file" @change="handleAudioFileSelect($event)" accept="audio/*" 
                                    class="hidden" x-ref="audioFilePicker">
                                <button @click="openFilePicker()" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all mb-2">
                                    <i class="ri-file-music-line"></i> Pilih File Audio
                                </button>
                                
                                <!-- Preview Selected File -->
                                <div x-show="previewFile" class="mb-2 p-2 bg-green-500/10 border border-green-500/20 rounded-lg">
                                    <p class="text-xs text-green-400 mb-1">File Dipilih:</p>
                                    <p class="text-xs text-white font-medium" x-text="previewFile.name"></p>
                                    <p class="text-xs text-gray-400" x-text="formatFileSize(previewFile.size)"></p>
                                </div>
                                
                                <!-- Options untuk File yang Dipilih -->
                                <div x-show="previewFile" class="space-y-2">
                                    <input type="text" x-model="newAudioName" placeholder="Nama audio (opsional)" 
                                        class="w-full bg-black/40 dark:bg-white/5 border border-white/10 rounded-lg py-2 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-600/50">
                                    
                                    <div class="flex gap-2">
                                        <button @click="useAudioDirectly()" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all">
                                            <i class="ri-check-line"></i> Gunakan Langsung
                                        </button>
                                        <button @click="uploadAudioAsNew()" 
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all">
                                            <i class="ri-upload-cloud-line"></i> Simpan ke Daftar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Test Audio Button -->
                            <button x-show="selectedAudio" @click="testAudio()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all mb-3">
                                <i class="ri-play-line"></i> Test Audio
                            </button>
                            
                            <!-- List of Saved Audios -->
                            <div class="mt-3 pt-3 border-t border-white/10">
                                <label class="block text-xs font-medium text-gray-400 dark:text-gray-500 mb-2">Daftar Audio Tersimpan</label>
                                <div class="max-h-32 overflow-y-auto">
                                    <div x-show="availableSounds.length === 0" class="text-xs text-gray-500 dark:text-gray-400 text-center py-2">
                                        Belum ada audio tersimpan
                                    </div>
                                    <template x-for="sound in availableSounds" :key="sound.id">
                                        <div class="flex items-center justify-between bg-black/20 dark:bg-white/5 rounded-lg p-2 mb-1">
                                            <span class="text-xs text-white" x-text="sound.name"></span>
                                            <div class="flex items-center gap-2">
                                                <button @click="selectAudioFromList(sound)" 
                                                    class="text-xs text-blue-400 hover:text-blue-300" title="Gunakan audio ini">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                <button @click="deleteSound(sound.id)" 
                                                    class="text-xs text-red-400 hover:text-red-300" title="Hapus">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Orders List -->
                        <div class="max-h-60 overflow-y-auto">
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-2">Order Baru</p>
                            <div x-show="newOrders.length === 0" class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">
                                Tidak ada order baru
                            </div>
                            <template x-for="order in newOrders" :key="order.id">
                                <div class="bg-purple-500/10 border border-purple-500/20 rounded-lg p-3 mb-2">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-white" x-text="order.customer_name"></p>
                                            <p class="text-xs text-gray-400" x-text="'Meja ' + order.table_number"></p>
                                            <p class="text-xs text-purple-400 font-medium" x-text="'Rp ' + parseInt(order.total_price).toLocaleString('id-ID')"></p>
                                        </div>
                                        <button @click="markAsRead(order.id)" class="text-gray-400 hover:text-white">
                                            <i class="ri-close-line text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

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

    {{-- Audio element for kitchen notification --}}
    <audio id="kitchenNotificationSound" preload="auto" style="display: none;">
        <source id="kitchenNotificationSource" src="" type="audio/mpeg">
    </audio>

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

            Alpine.data('kitchenNotification', () => ({
                openSettings: false,
                newOrders: [],
                newOrdersCount: 0,
                availableSounds: [],
                selectedAudio: localStorage.getItem('kitchenNotificationAudio') || '',
                lastOrderId: {{ \App\Models\orders::max('id') ?? 0 }},
                lastCheckTime: new Date().toISOString(),
                checkInterval: null,
                audioElement: null,
                newAudioName: '',
                selectedFile: null,
                previewFile: null,
                currentAudioName: '',

                openFilePicker() {
                    const filePicker = this.$refs.audioFilePicker;
                    if (filePicker) {
                        filePicker.click();
                    }
                },

                async init() {
                    // Initialize audio element
                    this.audioElement = document.getElementById('kitchenNotificationSound');
                    
                    // Clear audio source initially
                    const source = document.getElementById('kitchenNotificationSource');
                    if (source) {
                        source.src = '';
                    }
                    
                    // Load available sounds from database
                    await this.loadAvailableSounds();
                    
                    // Load audio preference
                    const savedAudio = localStorage.getItem('kitchenNotificationAudio');
                    const audioType = localStorage.getItem('kitchenNotificationAudioType');
                    
                    if (savedAudio && savedAudio !== '') {
                        if (audioType === 'database' && this.availableSounds.find(s => s.filename === savedAudio)) {
                            this.selectedAudio = savedAudio;
                            const sound = this.availableSounds.find(s => s.filename === savedAudio);
                            this.currentAudioName = sound ? sound.name : '';
                            this.updateAudioSource();
                        } else if (audioType === 'file') {
                            // File was selected directly, user needs to reselect on page reload
                            // Clear selection since file is not persistent
                            this.selectedAudio = '';
                            this.currentAudioName = '';
                            localStorage.removeItem('kitchenNotificationAudio');
                            localStorage.removeItem('kitchenNotificationAudioType');
                        }
                    } else if (this.availableSounds.length > 0) {
                        // Use first sound if available
                        this.selectedAudio = this.availableSounds[0].filename;
                        this.currentAudioName = this.availableSounds[0].name;
                        this.updateAudioSource();
                    } else {
                        // No audio available, clear selection
                        this.selectedAudio = '';
                        this.currentAudioName = '';
                    }
                    
                    // Start checking for new orders
                    this.startChecking();
                },

                async loadAvailableSounds() {
                    try {
                        const response = await fetch('/admin/notification-sounds');
                        this.availableSounds = await response.json();
                    } catch (error) {
                        console.error('Error loading sounds:', error);
                    }
                },

                updateAudioSource() {
                    const source = document.getElementById('kitchenNotificationSource');
                    if (!source || !this.audioElement) return;
                    
                    // Clear audio source if no audio selected
                    if (!this.selectedAudio || this.selectedAudio === '') {
                        source.src = '';
                        this.audioElement.pause();
                        this.audioElement.currentTime = 0;
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

                handleAudioFileSelect(event) {
                    const file = event.target?.files?.[0] || event?.files?.[0];
                    if (!file) return;
                    if (file) {
                        // Validate file type
                        if (!file.type.startsWith('audio/')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File tidak valid',
                                text: 'Harap pilih file audio (mp3, wav, ogg)',
                                background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                            });
                            return;
                        }
                        
                        // Validate file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File terlalu besar',
                                text: 'Ukuran file maksimal 2MB',
                                background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                            });
                            return;
                        }
                        
                        this.previewFile = file;
                        // Auto-generate name from filename if not provided
                        if (!this.newAudioName) {
                            this.newAudioName = file.name.replace(/\.[^/.]+$/, '');
                        }
                    }
                },

                useAudioDirectly() {
                    if (!this.previewFile) return;
                    
                    this.selectedAudio = this.previewFile;
                    this.currentAudioName = this.previewFile.name;
                    this.saveAudioPreference();
                    
                    // Note: Direct file selection doesn't sync to kitchen
                    // User needs to save to list first to sync
                    
                    // Clear preview
                    this.previewFile = null;
                    this.newAudioName = '';
                    if (this.$refs.audioFilePicker) {
                        this.$refs.audioFilePicker.value = '';
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Audio berhasil dipilih (hanya untuk admin). Simpan ke daftar untuk diterapkan ke dapur.',
                        background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                        color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}',
                        timer: 3000,
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
                            background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                            color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', this.newAudioName);
                    formData.append('audio', this.previewFile);

                    try {
                        const response = await fetch('/admin/notification-sounds', {
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
                                background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                            });
                            
                            // Reload sounds
                            await this.loadAvailableSounds();
                            
                            // Auto-select the newly uploaded sound
                            if (data.sound) {
                                this.selectedAudio = data.sound.filename;
                                this.currentAudioName = data.sound.name;
                                this.saveAudioPreference();
                                
                                // Set as active sound for kitchen
                                try {
                                    const activeResponse = await fetch(`/admin/notification-sounds/${data.sound.id}/set-active`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    });
                                    
                                    const activeData = await activeResponse.json();
                                    if (!activeData.success) {
                                        console.error('Failed to set active sound:', activeData.message);
                                    }
                                } catch (error) {
                                    console.error('Error setting active sound:', error);
                                }
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
                                background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                            });
                        }
                    } catch (error) {
                        console.error('Error uploading audio:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat upload audio',
                            background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                            color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                        });
                    }
                },

                async selectAudioFromList(sound) {
                    this.selectedAudio = sound.filename;
                    this.currentAudioName = sound.name;
                    this.saveAudioPreference();
                    
                    // Set as active sound for kitchen
                    try {
                        const response = await fetch(`/admin/notification-sounds/${sound.id}/set-active`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        if (!data.success) {
                            console.error('Failed to set active sound:', data.message);
                        }
                    } catch (error) {
                        console.error('Error setting active sound:', error);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Audio berhasil dipilih dan diterapkan ke dapur',
                        background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                        color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },

                saveAudioPreference() {
                    // Save filename if it's from database, or save file name if it's a direct file
                    if (this.selectedAudio instanceof File) {
                        localStorage.setItem('kitchenNotificationAudio', this.selectedAudio.name);
                        localStorage.setItem('kitchenNotificationAudioType', 'file');
                        // Store file as base64 for persistence (optional, but limited by localStorage size)
                        // For now, we'll just store the filename and reload file on next session
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
                            background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                            color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }
                    
                    if (this.audioElement && this.audioElement.src) {
                        this.audioElement.currentTime = 0;
                        this.audioElement.play().catch(err => {
                            console.log('Audio test failed:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal memutar audio',
                                text: 'Pastikan file audio tersedia',
                                background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    }
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
                        background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                        color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/admin/notification-sounds/${soundId}`, {
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
                                        background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                        color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                                    });
                                    
                                    // Reload sounds
                                    await this.loadAvailableSounds();
                                    
                                    // If deleted sound was selected, select first available
                                    const deletedSound = this.availableSounds.find(s => s.id === soundId);
                                    if (deletedSound && this.selectedAudio === deletedSound.filename) {
                                        if (this.availableSounds.length > 0) {
                                            this.selectedAudio = this.availableSounds[0].filename;
                                            this.saveAudioPreference();
                                        } else {
                                            this.selectedAudio = '';
                                        }
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message || 'Gagal menghapus audio',
                                        background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                        color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                                    });
                                }
                            } catch (error) {
                                console.error('Error deleting sound:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menghapus audio',
                                    background: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#0A0A0A" : "#fff" }}',
                                    color: '{{ Auth::check() && Auth::user()->theme === "dark" ? "#fff" : "#000" }}'
                                });
                            }
                        }
                    });
                },


                startChecking() {
                    // Check immediately
                    this.checkNewOrders();
                    
                    // Then check every 3 seconds
                    this.checkInterval = setInterval(() => {
                        this.checkNewOrders();
                    }, 3000);
                },

                async checkNewOrders() {
                    try {
                        const response = await fetch(`/admin/orders/check-new?last_order_id=${this.lastOrderId}&last_check_time=${encodeURIComponent(this.lastCheckTime)}`);
                        const data = await response.json();
                        
                        if (data.has_new_orders && data.new_orders && data.new_orders.length > 0) {
                            // Add new orders to list
                            data.new_orders.forEach(order => {
                                if (!this.newOrders.find(o => o.id === order.id)) {
                                    this.newOrders.unshift(order);
                                }
                            });
                            
                            // Keep only last 10 orders
                            this.newOrders = this.newOrders.slice(0, 10);
                            this.newOrdersCount = this.newOrders.length;
                            
                            // Play notification sound
                            this.playNotificationSound();
                        }
                        
                        // Update tracking
                        if (data.latest_order_id > this.lastOrderId) {
                            this.lastOrderId = data.latest_order_id;
                        }
                        if (data.current_time) {
                            this.lastCheckTime = data.current_time;
                        }
                    } catch (error) {
                        console.error('Error checking new orders:', error);
                    }
                },

                playNotificationSound() {
                    // Don't play if no audio selected or audio source is empty
                    if (!this.selectedAudio || this.selectedAudio === '' || !this.audioElement) {
                        return;
                    }
                    
                    // Check if audio source is set
                    const source = document.getElementById('kitchenNotificationSource');
                    if (!source || !source.src || source.src === '') {
                        return;
                    }
                    
                    // Only play if audio is ready
                    if (this.audioElement.readyState >= 2) {
                        this.audioElement.currentTime = 0;
                        this.audioElement.play().catch(err => {
                            console.log('Notification sound play failed:', err);
                        });
                    }
                },

                markAsRead(orderId) {
                    this.newOrders = this.newOrders.filter(order => order.id !== orderId);
                    this.newOrdersCount = this.newOrders.length;
                }
            }));
        });
    </script>
    @stack('scripts')
</body>

</html>