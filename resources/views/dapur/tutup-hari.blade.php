@extends('layouts.app')

@section('title', 'Tutup Hari - Billiard Class')

@php
    $isDarkMode = request()->cookie('theme') === 'dark' || (!request()->cookie('theme') && isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark');
    
    // Get shift_end from session untuk auto-logout real-time
    $shiftEndTimestamp = session('shift_end');
    $shiftEndDatetime = session('shift_end_datetime');
    
    // Jika belum ada di session, hitung dari active shift
    if (!$shiftEndTimestamp && isset($activeShift) && $activeShift) {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $shiftStart = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($activeShift->start_time);
        $shiftEnd = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($activeShift->end_time);
        
        if ($shiftEnd->lt($shiftStart)) {
            $shiftEnd->addDay();
        }
        
        $shiftEndTimestamp = $shiftEnd->timestamp;
        $shiftEndDatetime = $shiftEnd->toIso8601String();
    }
@endphp

@push('head')
@if($shiftEndTimestamp)
<meta name="shift-end" content="{{ $shiftEndDatetime }}">
<meta name="shift-end-timestamp" content="{{ $shiftEndTimestamp }}">
@endif
@endpush

@push('scripts')
<script>
    {{-- Theme Manager Script --}}
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
                        const logoutForm = document.getElementById('logout-form');
                        if (logoutForm) {
                            logoutForm.submit();
                        } else {
                            // Fallback: create and submit form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("logout") }}';
                            const csrfToken = document.querySelector('meta[name="csrf-token"]');
                            if (csrfToken) {
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken.getAttribute('content');
                                form.appendChild(csrfInput);
                            }
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                });
            }
        }));
    });
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
<div x-data="themeManager()" x-init="initTheme()" class="min-h-screen bg-gray-50 dark:bg-[#050505] theme-transition text-black dark:text-slate-200">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('dapur.partials.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Navbar --}}
            @include('dapur.partials.navbar', ['pageTitle' => 'Tutup Hari'])

            {{-- Content --}}
            <div class="flex-1 p-8 md:p-12 min-h-screen">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white dark:bg-[#0a0a0a] rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-white/5">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-black dark:text-white mb-2">Generate Struk Harian</h2>
                            <p class="text-gray-600 dark:text-gray-400">Pilih tanggal untuk membuat struk laporan harian</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="tanggal" class="block text-sm font-semibold text-black dark:text-white mb-2">
                                    <i class="ri-calendar-line mr-2"></i>Tanggal
                                </label>
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#050505] text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-[#fa9a08] focus:border-transparent transition-all">
                            </div>

                            @php
                                $userShift = Auth::user()->shift;
                            @endphp
                            @if($userShift)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <div class="flex items-center gap-3">
                                    <i class="ri-time-line text-blue-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-bold text-black dark:text-white">Shift Anda: {{ $userShift->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $userShift->start_time }} - {{ $userShift->end_time }} WIB</p>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <i class="ri-information-line mr-1"></i>Struk akan menampilkan order dari shift Anda saja.
                                </p>
                            </div>
                            @endif

                            <div class="flex gap-4">
                                <form action="{{ route('tutup-hari.struk') }}" method="GET" target="_blank" class="flex-1" id="strukForm">
                                    <input type="hidden" name="tanggal" id="tanggal-print" value="{{ date('Y-m-d') }}">
                                    <button type="submit" class="w-full bg-gradient-to-r from-[#fa9a08] to-[#ff8c00] text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="ri-printer-line text-xl"></i>
                                        <span>Generate & Print Struk</span>
                                    </button>
                                </form>
                            </div>

                            <div class="divider-line border-t border-gray-200 dark:border-white/10 my-6"></div>

                            <div>
                                <h3 class="text-lg font-bold text-black dark:text-white mb-4">
                                    <i class="ri-mail-line mr-2"></i>Kirim ke Email
                                </h3>
                                <form id="emailForm" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-black dark:text-white mb-2">
                                            <i class="ri-mail-line mr-2"></i>Alamat Email
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               required
                                               placeholder="contoh@email.com"
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#050505] text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-[#fa9a08] focus:border-transparent transition-all">
                                    </div>
                                    <input type="hidden" name="tanggal" id="tanggal-email" value="{{ date('Y-m-d') }}">
                                    <button type="submit" id="sendEmailBtn" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="ri-send-plane-line text-xl"></i>
                                        <span>Kirim ke Email</span>
                                    </button>
                                </form>
                                <div id="emailMessage" class="mt-4 hidden"></div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-100 dark:border-white/5">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-100 dark:border-blue-800/30">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="ri-information-line text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-black dark:text-white mb-2">Informasi Struk</h3>
                                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                            <li class="flex items-start gap-2">
                                                <i class="ri-checkbox-circle-line text-blue-500 mt-0.5"></i>
                                                <span>Struk akan menampilkan semua order yang sudah selesai (completed) pada tanggal yang dipilih</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="ri-checkbox-circle-line text-blue-500 mt-0.5"></i>
                                                <span>Termasuk total pendapatan, breakdown per metode pembayaran, dan detail per ruangan</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="ri-checkbox-circle-line text-blue-500 mt-0.5"></i>
                                                <span>Struk dapat langsung di-print atau disimpan sebagai PDF</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Logout Form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

@push('scripts')
<script>

    // Handle email form submission
    const emailForm = document.getElementById('emailForm');
    const tanggalInput = document.getElementById('tanggal');
    const tanggalPrint = document.getElementById('tanggal-print');
    const tanggalEmail = document.getElementById('tanggal-email');
    const emailMessage = document.getElementById('emailMessage');
    const sendEmailBtn = document.getElementById('sendEmailBtn');

    // Sync tanggal inputs
    if (tanggalInput && tanggalPrint && tanggalEmail) {
        tanggalInput.addEventListener('change', function() {
            tanggalPrint.value = this.value;
            tanggalEmail.value = this.value;
        });
    }

    if (emailForm) {
        emailForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const tanggal = tanggalEmail.value;
            const submitBtn = sendEmailBtn;
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> Mengirim...';
            emailMessage.classList.add('hidden');

            try {
                const response = await fetch('{{ route("tutup-hari.kirim-email") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        email: email,
                        tanggal: tanggal
                    })
                });

                const result = await response.json();

                if (result.success) {
                    emailMessage.className = 'mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg';
                    emailMessage.innerHTML = '<div class="flex items-center gap-2 text-green-600 dark:text-green-400"><i class="ri-checkbox-circle-line"></i><span>' + result.message + '</span></div>';
                    emailMessage.classList.remove('hidden');
                    emailForm.reset();
                    document.getElementById('email').value = '';
                } else {
                    emailMessage.className = 'mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg';
                    emailMessage.innerHTML = '<div class="flex items-center gap-2 text-red-600 dark:text-red-400"><i class="ri-error-warning-line"></i><span>' + (result.message || 'Gagal mengirim email') + '</span></div>';
                    emailMessage.classList.remove('hidden');
                }
            } catch (error) {
                emailMessage.className = 'mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg';
                emailMessage.innerHTML = '<div class="flex items-center gap-2 text-red-600 dark:text-red-400"><i class="ri-error-warning-line"></i><span>Terjadi kesalahan: ' + error.message + '</span></div>';
                emailMessage.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    // Check shift end and auto-logout (REAL-TIME dengan session shift_end)
    function checkShiftEndRealTime() {
        const shiftEndMeta = document.querySelector('meta[name="shift-end-timestamp"]');
        
        if (!shiftEndMeta) {
            return; // Skip jika meta tidak ada
        }
        
        const shiftEndTimestamp = parseInt(shiftEndMeta.getAttribute('content'));
        const now = Math.floor(Date.now() / 1000);
        
        // Cek apakah shift sudah berakhir
        if (now >= shiftEndTimestamp) {
            // Shift telah berakhir - logout otomatis
            let message = 'Shift Anda telah berakhir. Anda akan di-logout.';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Shift Berakhir',
                    html: `<p class="text-lg mb-2">${message}</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fa9a08',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: document.documentElement.classList.contains('dark') ? '#0A0A0A' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                }).then(() => {
                    performLogout();
                });
            } else {
                performLogout();
            }
            return;
        }
        
        // Cek apakah 5 menit sebelum shift berakhir untuk notifikasi
        const minutesUntilEnd = Math.floor((shiftEndTimestamp - now) / 60);
        if (minutesUntilEnd <= 5 && minutesUntilEnd >= 0) {
            const lastNotification = localStorage.getItem('shiftWarningShown');
            const currentMinute = Math.floor(now / 60);
            
            if (lastNotification !== String(currentMinute)) {
                // Show browser notification
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('⏰ Peringatan Shift', {
                        body: `Shift akan berakhir dalam ${minutesUntilEnd} menit. Jangan lupa untuk Tutup Hari!`,
                        icon: '/logo.png',
                        tag: 'shift-warning',
                        requireInteraction: true
                    });
                }
                
                // Show SweetAlert notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: '⏰ Peringatan!',
                        html: `<p class="text-lg mb-2">Shift akan berakhir dalam <strong>${minutesUntilEnd} menit</strong>!</p><p class="text-sm">Jangan lupa untuk <strong>Tutup Hari</strong> sebelum shift berakhir.</p>`,
                        confirmButtonText: 'Ke Halaman Tutup Hari',
                        confirmButtonColor: '#fa9a08',
                        showCancelButton: true,
                        cancelButtonText: 'Nanti',
                        background: document.documentElement.classList.contains('dark') ? '#0A0A0A' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("tutup-hari") }}';
                        }
                    });
                }
                
                localStorage.setItem('shiftWarningShown', String(currentMinute));
            }
        }
    }
    
    // Helper function untuk logout
    function performLogout() {
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.submit();
        } else {
            // Fallback: create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    
    // Wait for DOM to be ready before checking shift end
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            // Check shift end every 10 seconds untuk real-time auto-logout
            setInterval(checkShiftEndRealTime, 10000);
            checkShiftEndRealTime();
            
            // Request notification permission on page load
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        });
    } else {
        // DOM is already ready
        // Check shift end every 10 seconds untuk real-time auto-logout
        setInterval(checkShiftEndRealTime, 10000);
        checkShiftEndRealTime();
        
        // Request notification permission on page load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
    @endpush
@endsection

