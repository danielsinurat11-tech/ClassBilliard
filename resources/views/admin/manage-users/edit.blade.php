@extends('layouts.admin')

@section('title', 'Edit Staff Access')

@section('content')
<div class="max-w-xl mx-auto animate-in fade-in duration-500">
    
    <!-- HEADER: Navigation & Info -->
    <div class="flex items-center gap-4 mb-10 border-b border-slate-200 dark:border-white/5 pb-8">
        <a href="{{ route('admin.manage-users.index') }}" 
           class="w-9 h-9 flex items-center justify-center rounded-md border border-slate-200 dark:border-white/10 text-slate-400 hover:text-[#fa9a08] hover:border-[#fa9a08] transition-all">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Akses Staff</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Identity: <span class="text-[#fa9a08]">{{ $user->name }}</span>
            </p>
        </div>
    </div>

    <!-- FORM: Professional Sleek (No Box-in-Box) -->
    <form action="{{ route('admin.manage-users.update', $user->id) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')
        
        <!-- SECTION 1: Personal Identity -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Hak Akses (Role)</label>
                    <div class="relative">
                        <select name="role" class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all appearance-none font-bold">
                            <option value="kitchen" {{ $user->role == 'kitchen' ? 'selected' : '' }}>Staff Dapur</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Email Sistem</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
            </div>
        </div>

<<<<<<< HEAD
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

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Sistem</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
        </div>

        <div class="pt-4 mt-4 border-t border-white/5">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-lock-password-line text-orange-500"></i>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Reset Keamanan (Opsional)</h3>
=======
        <!-- SECTION 2: Security (Optional) -->
        <div class="pt-8 border-t border-slate-200 dark:border-white/5 space-y-6">
            <div class="flex items-center gap-2">
                <i class="ri-shield-keyhole-line text-[#fa9a08]"></i>
                <h3 class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Reset Keamanan</h3>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-500/5 border border-blue-200 dark:border-blue-500/20 p-4 rounded-md">
                <p class="text-[10px] text-blue-700 dark:text-blue-400 font-bold leading-relaxed flex items-start gap-2 uppercase tracking-wider">
                    <i class="ri-information-line text-sm leading-none"></i>
                    Biarkan kolom di bawah kosong jika tidak ingin merubah password user ini.
                </p>
>>>>>>> 4dcc4bed6a4ba3ffc801d6f618a73bb4fab53c46
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Password Baru</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col gap-4 pt-6">
            <button type="submit" 
                class="w-full bg-[#fa9a08] hover:bg-orange-600 text-black font-extrabold py-4 rounded-md shadow-lg shadow-orange-500/10 active:scale-[0.98] transition-all uppercase tracking-[0.2em] text-xs">
                Update Data Staff
            </button>
            <a href="{{ route('admin.manage-users.index') }}" 
               class="text-center text-[10px] font-black text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all uppercase tracking-[0.2em]">
                Batalkan Perubahan
            </a>
        </div>
    </form>
</div>
@endsection