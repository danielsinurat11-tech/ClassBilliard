@extends('layouts.admin')

@section('title', 'Register New Staff')

@section('content')
<div class="max-w-xl mx-auto animate-in fade-in duration-500">
    <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.manage-users.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10 transition-all">
                <i class="ri-arrow-left-line"></i>
            </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Registrasi Staff Baru</h1>
            <p class="text-gray-500 text-xs">User Access Control</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-2xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="ri-error-warning-fill"></i>
                <span class="text-xs font-bold uppercase">Terjadi Kesalahan</span>
            </div>
            <ul class="list-none text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.manage-users.store') }}" method="POST" class="space-y-6">
            @csrf

        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: John Doe" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Kredensial</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@billiardclass.com" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Hak Akses (Role)</label>
                    <select name="role" class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                        <option value="kitchen">Staff Dapur</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Shift</label>
                    <select name="shift_id" class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                        <option value="">Pilih Shift</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->name }} ({{ $shift->start_time->format('H:i') }} - {{ $shift->end_time->format('H:i') }} WIB)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Awal</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
            </div>
            </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-[var(--accent)] hover:bg-orange-600 text-black font-bold py-4 rounded-2xl transition-all uppercase tracking-wider text-sm">
                Daftarkan Staff
            </button>
            <p class="text-center text-[10px] text-gray-500 mt-4">
                Pastikan data yang dimasukkan sudah valid sesuai kebijakan keamanan sistem.
            </p>
        </div>
        </form>
    </div>
@endsection
