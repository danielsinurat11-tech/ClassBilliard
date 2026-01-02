@extends('layouts.app')

@section('title', 'Pengaturan Audio - Billiard Class')

{{-- Include shift calculation PHP block --}}
@include('dapur.partials.shift-calculation')

{{-- Include shift meta tags --}}
@include('dapur.partials.shift-meta')

{{-- Include theme initialization script --}}
@include('dapur.partials.theme-manager')

{{-- Include common styles --}}
@include('dapur.partials.common-styles')

@push('head')
    <script>
    // Alpine.js untuk Pengaturan Audio - harus didaftarkan sebelum Alpine memproses DOM
    document.addEventListener('alpine:init', () => {
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
@endpush

{{-- Include sidebar & main content styles --}}
@include('dapur.partials.sidebar-main-styles')

@section('content')
    {{-- Logout Form --}}
    @include('dapur.partials.logout-form')

    <div x-data="themeManager()" x-init="initTheme()" class="min-h-screen bg-gray-50 dark:bg-[#050505] theme-transition text-black dark:text-slate-200">
        {{-- Sidebar --}}
        @include('dapur.partials.sidebar')

        {{-- Main Content Wrapper --}}
        <div class="min-h-screen flex flex-col transition-all duration-300" :class="sidebarCollapsed ? 'ml-20 lg:ml-20' : 'ml-72 lg:ml-72'">
            {{-- Navbar --}}
            @include('dapur.partials.navbar', ['pageTitle' => 'Pengaturan Audio'])

            {{-- Main Content --}}
            <main class="flex-1 p-8 md:p-12">
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
                                <template x-if="previewFile">
                                    <div class="mb-3 p-3 bg-green-50 dark:bg-green-500/10 border border-green-300 dark:border-green-500/20 rounded-lg">
                                        <p class="text-xs text-green-600 dark:text-green-400 mb-1">File Dipilih:</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-medium" x-text="previewFile.name"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400" x-text="formatFileSize(previewFile.size)"></p>
                                    </div>
                                </template>
                                
                                {{-- Options untuk File yang Dipilih --}}
                                <template x-if="previewFile">
                                    <div class="space-y-3">
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
                                </template>
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
                                            <span class="text-sm text-gray-900 dark:text-white flex-1" x-text="sound.name"></span>
                                            <div class="flex items-center gap-2">
                                                <button @click.stop="selectAudioFromList(sound)" 
                                                    class="text-sm text-blue-400 hover:text-blue-300 px-3 py-1 rounded-lg hover:bg-blue-500/10 transition-all cursor-pointer relative z-10" 
                                                    title="Gunakan audio ini"
                                                    type="button">
                                                    <i class="ri-check-line"></i> Pilih
                                                </button>
                                                <button @click.stop="deleteSound(sound.id)" 
                                                    class="text-sm text-red-400 hover:text-red-300 px-3 py-1 rounded-lg hover:bg-red-500/10 transition-all cursor-pointer relative z-10" 
                                                    title="Hapus"
                                                    type="button">
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
            </main>
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
        const menuAudio = document.getElementById('menu-audio');

        if (menuOrders) {
            menuOrders.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    sidebarOverlay.classList.remove('show');
                }
            });
        }

        if (menuAudio) {
            menuAudio.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    sidebarOverlay.classList.remove('show');
                }
            });
        }

    </script>

    {{-- Hidden Logout Form --}}
    {{-- Include theme manager script --}}
    @include('dapur.partials.theme-manager')
    
    {{-- Include shift check script --}}
    @include('dapur.partials.shift-check-script')
    @endpush
@endsection

