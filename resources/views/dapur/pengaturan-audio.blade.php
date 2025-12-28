@extends('layouts.app')

@section('title', 'Pengaturan Audio - Billiard Class')

@php
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
        @include('dapur.partials.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-1 w-full" :class="sidebarCollapsed ? 'desktop-collapsed' : ''">
            {{-- Navbar --}}
            @include('dapur.partials.navbar', ['pageTitle' => 'Pengaturan Audio'])

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
            });
        } else {
            // DOM is already ready
            // Check shift end every 10 seconds untuk real-time auto-logout
            setInterval(checkShiftEndRealTime, 10000);
            checkShiftEndRealTime();
        }
        
        // Request notification permission on page load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
    @endpush
@endsection

