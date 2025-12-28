@extends('layouts.app')

@section('title', 'Tutup Hari - Billiard Class')

{{-- Include shift calculation PHP block --}}
@include('dapur.partials.shift-calculation')

{{-- Include shift meta tags --}}
@include('dapur.partials.shift-meta')

{{-- Include theme initialization script --}}
@include('dapur.partials.theme-manager')

{{-- Include common styles --}}
@include('dapur.partials.common-styles')

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
        @include('dapur.partials.navbar', ['pageTitle' => 'Tutup Hari'])

        {{-- Main Content --}}
        <main class="flex-1 p-8 md:p-12">
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
        </main>
    </div>
</div>

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

    {{-- Include shift check script --}}
    @include('dapur.partials.shift-check-script')
    @endpush
@endsection

