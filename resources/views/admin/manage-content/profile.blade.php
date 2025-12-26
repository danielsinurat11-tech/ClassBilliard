@extends('layouts.admin')

@section('title', 'Manajemen Profil')

@section('content')
<div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-6">
            <div class="bg-[#111] border border-white/5 rounded-3xl p-8 text-center relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-orange-600/20 to-yellow-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative inline-block mb-4">
                    <div class="w-32 h-32 rounded-3xl bg-gradient-to-tr from-[var(--accent)] to-yellow-300 p-[3px] rotate-3 group-hover:rotate-6 transition-transform">
                        <div class="w-full h-full rounded-[28px] bg-[#111] flex items-center justify-center overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=transparent&color=fa9a08&size=128&bold=true" class="-rotate-3 group-hover:-rotate-6 transition-transform">
                        </div>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ $user->name }}</h3>
                <p class="text-gray-500 text-sm mb-6">{{ $user->email }}</p>
                
                <div class="inline-flex items-center px-4 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-[var(--accent)] text-[10px] font-bold uppercase tracking-widest">
                    {{ $user->role }} Access
                </div>
            </div>

            <div class="bg-[#111] border border-white/5 rounded-3xl p-6">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Statistik Login</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Terdaftar Sejak</span>
                        <span class="text-sm text-gray-300 font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-white/5 pt-4">
                        <span class="text-sm text-gray-500">Status Akun</span>
                        <span class="flex items-center gap-2 text-emerald-500 text-sm font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Verified
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-[#111] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                    <div>
                        <h2 class="text-xl font-bold text-white">Informasi Dasar</h2>
                        <p class="text-sm text-gray-500">Perbarui identitas publik Anda di sistem.</p>
                    </div>
                    <i class="ri-user-smile-line text-3xl text-[var(--accent)] opacity-20"></i>
                </div>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] focus:ring-4 focus:ring-orange-500/10 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Email System</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] focus:ring-4 focus:ring-orange-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-gradient-to-r from-orange-600 to-yellow-500 hover:shadow-[0_10px_30px_rgba(250,154,8,0.3)] text-black font-bold py-3 px-10 rounded-2xl transition-all active:scale-95 uppercase text-xs tracking-widest">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-[#111] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                    <div>
                        <h2 class="text-xl font-bold text-white">Keamanan Password</h2>
                        <p class="text-sm text-gray-500">Pastikan gunakan kombinasi yang sulit ditebak.</p>
                    </div>
                    <i class="ri-lock-password-line text-3xl text-red-500 opacity-20"></i>
                </div>
                
                <form action="{{ route('admin.profile.password') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required
                                class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-red-500/50 focus:ring-4 focus:ring-red-500/5 transition-all outline-none">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Baru</label>
                                <input type="password" name="password" required
                                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-white/5 hover:bg-white/10 text-white font-bold py-3 px-10 rounded-2xl border border-white/10 transition-all active:scale-95 uppercase text-xs tracking-widest">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '<span class="text-white font-bold">SYTEM UPDATED</span>',
        html: '<p class="text-gray-400 text-sm">{{ session('success') }}</p>',
        background: '#111',
        confirmButtonColor: '#fa9a08',
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom'
        }
    });
</script>
@endif

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: '<span class="text-white font-bold">VALIDATION ERROR</span>',
        html: '<ul class="text-red-400 text-xs text-left list-disc ml-5 uppercase">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        background: '#111',
        confirmButtonColor: '#ef4444',
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom'
        }
    });
</script>
@endif
@endpush