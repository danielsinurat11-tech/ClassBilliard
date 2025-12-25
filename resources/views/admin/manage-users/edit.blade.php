@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto py-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.manage-users.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10 transition-all">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Akses Staff</h1>
            <p class="text-gray-500 text-sm">Update informasi untuk: <span class="text-[var(--accent)]">{{ $user->name }}</span></p>
        </div>
    </div>

    <form action="{{ route('admin.manage-users.update', $user->id) }}" method="POST" class="bg-[#111] border border-white/5 rounded-3xl p-8 space-y-6 shadow-2xl relative overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Hak Akses (Role)</label>
                <select name="role" class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                    <option value="kitchen" {{ $user->role == 'kitchen' ? 'selected' : '' }}>Staff Dapur</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Sistem</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
        </div>

        <div class="pt-4 mt-4 border-t border-white/5">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-lock-password-line text-orange-500"></i>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Reset Keamanan (Opsional)</h3>
            </div>
            <p class="text-[10px] text-gray-500 mb-4 bg-orange-500/5 p-3 rounded-xl border border-orange-500/10">
                <i class="ri-information-line"></i> Biarkan kosong jika tidak ingin mengganti password user ini.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 pt-4">
            <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-yellow-500 text-black font-bold py-4 rounded-2xl hover:shadow-[0_10px_30px_rgba(250,154,8,0.3)] active:scale-95 transition-all uppercase tracking-widest text-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.manage-users.index') }}" class="text-center text-gray-500 text-xs hover:text-white transition-all uppercase tracking-widest">
                Batalkan dan Kembali
            </a>
        </div>
    </form>
</div>
@endsection