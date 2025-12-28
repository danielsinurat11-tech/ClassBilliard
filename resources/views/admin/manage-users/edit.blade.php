@extends('layouts.admin')

@section('title', 'Edit Staff Access')

@section('content')
<div class="max-w-xl mx-auto animate-in fade-in duration-500">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.manage-users.index') }}" 
           class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10 transition-all">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Akses Staff</h1>
            <p class="text-gray-500 text-xs">
                Identity: <span class="text-[var(--accent)]">{{ $user->name }}</span>
            </p>
        </div>
    </div>

    <form action="{{ route('admin.manage-users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Hak Akses (Role)</label>
                @php
                    $currentRole = $user->getRoleNames()->first();
                @endphp
                <select name="role" class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                    <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kitchen" {{ $currentRole == 'kitchen' ? 'selected' : '' }}>Kitchen Staff</option>
                    <option value="super_admin" {{ $currentRole == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Sistem</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
        </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Shift</label>
                <select name="shift_id" class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                    <option value="">Tidak ada shift</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $user->shift_id) == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_time->format('H:i') }} - {{ $shift->end_time->format('H:i') }} WIB)
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 mt-1">Assign shift untuk user admin/dapur agar order ter-assign ke shift yang sesuai</p>
            </div>
        </div>

        <div class="pt-6 border-t border-white/5 space-y-6">
            <div class="flex items-center gap-2">
                <i class="ri-lock-password-line text-[var(--accent)]"></i>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Reset Keamanan (Opsional)</h3>
            </div>
            
            <div class="bg-blue-500/10 border border-blue-500/20 p-4 rounded-2xl">
                <p class="text-xs text-blue-400 font-medium flex items-start gap-2">
                    <i class="ri-information-line"></i>
                    Biarkan kolom di bawah kosong jika tidak ingin merubah password user ini.
            </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Baru</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all placeholder:text-gray-600">
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 pt-6">
            <button type="submit" class="w-full bg-[var(--accent)] hover:bg-orange-600 text-black font-bold py-4 rounded-2xl transition-all uppercase tracking-wider text-sm">
                Update Data Staff
            </button>
            <a href="{{ route('admin.manage-users.index') }}" class="text-center text-xs text-gray-500 hover:text-white transition-all">
                Batalkan Perubahan
            </a>
        </div>
    </form>
</div>
@endsection
