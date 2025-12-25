@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl">
                <ul class="list-disc list-inside text-red-500 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.manage-users.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10">
                <i class="ri-arrow-left-line"></i>
            </a>
            <h1 class="text-2xl font-bold text-white">Registrasi Staff Baru</h1>
        </div>

        <form action="{{ route('admin.manage-users.store') }}" method="POST"
            class="bg-[#111] border border-white/5 rounded-3xl p-8 space-y-6 shadow-2xl">
            @csrf

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Kredensial</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Hak Akses (Role)</label>
                    <select name="role"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
                        <option value="kitchen">Staff Dapur</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Password Awal</label>
                    <input type="password" name="password" required
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-orange-600 to-yellow-500 text-black font-bold py-4 rounded-2xl hover:shadow-[0_10px_30px_rgba(250,154,8,0.3)] active:scale-95 transition-all uppercase tracking-widest text-sm">
                Daftarkan User
            </button>
        </form>
    </div>
@endsection