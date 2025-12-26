@extends('layouts.admin')

@section('title', 'Tambah Meja Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumbs / Back Link -->
    <div class="mb-8">
        <a href="{{ route('admin.tables.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-[var(--accent)] transition-colors group">
            <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Daftar Meja
        </a>
        <h1 class="text-3xl font-bold italic mt-4 uppercase tracking-tight">
            Tambah <span class="text-[var(--accent)]">Meja Baru</span>
        </h1>
        <p class="text-gray-500 text-sm mt-1">Sistem akan otomatis membuat QR Code unik untuk setiap meja.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-[#1A1A1A] rounded-[40px] border border-white/10 p-10 shadow-2xl relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-[var(--accent)]/5 rounded-full blur-3xl"></div>

        <form action="{{ route('admin.tables.store') }}" method="POST" class="relative z-10 space-y-8">
            @csrf

            <!-- Icon Header -->
            <div class="flex justify-center">
                <div class="w-24 h-24 bg-[var(--accent)]/10 rounded-3xl border border-[var(--accent)]/20 flex items-center justify-center">
                    <i class="ri-qr-scan-2-line text-5xl text-[var(--accent)]"></i>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Input Nama Meja -->
                <div class="space-y-2">
                    <label for="name" class="text-xs font-bold uppercase tracking-widest text-gray-500 ml-1">
                        Nama / Nomor Meja
                    </label>
                    <input type="text" name="name" id="name"
                        class="w-full bg-black border border-white/10 p-4 rounded-2xl focus:outline-none focus:border-[var(--accent)] transition-all text-white placeholder:text-gray-600 text-lg font-semibold"
                        placeholder="Contoh: Meja 01 atau VIP 05" required autofocus value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Ruangan -->
                <div class="space-y-2">
                    <label for="room" class="text-xs font-bold uppercase tracking-widest text-gray-500 ml-1">
                        Ruangan
                    </label>
                    <input type="text" name="room" id="room"
                        class="w-full bg-black border border-white/10 p-4 rounded-2xl focus:outline-none focus:border-[var(--accent)] transition-all text-white placeholder:text-gray-600 text-lg font-semibold"
                        placeholder="Contoh: Ruang A, VIP Room, dll" required value="{{ old('room') }}">
                    @error('room')
                        <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-500/5 border border-blue-500/20 p-5 rounded-2xl flex gap-4 items-start">
                    <i class="ri-information-fill text-blue-500 text-xl shrink-0"></i>
                    <div class="text-xs text-gray-400 leading-relaxed">
                        <p class="font-bold text-blue-400 mb-1">Informasi Otomatis</p>
                        Setelah disimpan, sistem akan men-generate <span class="text-white">Slug</span> dan <span class="text-white">QR Code</span> yang mengarah ke link pesanan digital meja ini.
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="reset"
                    class="flex-1 py-4 rounded-2xl bg-white/5 font-semibold hover:bg-white/10 transition-all text-gray-400 hover:text-white">
                    Reset Form
                </button>
                <button type="submit"
                    class="flex-[2] py-4 rounded-2xl bg-[var(--accent)] text-black font-bold hover:bg-[#e19e2b] shadow-[0_20px_40px_rgba(250,154,8,0.2)] hover:shadow-[0_25px_50px_rgba(250,154,8,0.3)] transition-all transform hover:-translate-y-1 active:scale-95">
                    Generate & Simpan Meja
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

