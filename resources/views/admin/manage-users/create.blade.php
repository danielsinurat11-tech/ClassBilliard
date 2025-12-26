@extends('layouts.admin')

@section('title', 'Register New Staff')

@section('content')
    <div class="max-w-xl mx-auto animate-in fade-in duration-500">

        <!-- HEADER: Navigation & Breadcrumb -->
        <div class="flex items-center gap-4 mb-10">
            <a href="{{ route('admin.manage-users.index') }}"
                class="w-9 h-9 flex items-center justify-center rounded-md border border-slate-200 dark:border-white/10 text-slate-400 hover:text-[#fa9a08] hover:border-[#fa9a08] transition-all">
                <i class="ri-arrow-left-line"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Registrasi Staff Baru</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">User Access Control</p>
            </div>
        </div>

        <!-- ERROR HANDLING: Sharp Design -->
        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 dark:bg-red-500/5 border-l-4 border-red-500 rounded-md">
                <div class="flex items-center gap-2 mb-2">
                    <i class="ri-error-warning-fill text-red-500"></i>
                    <span class="text-xs font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">Terjadi Kesalahan
                        Kredensial</span>
                </div>
                <ul class="list-none text-xs text-red-600 dark:text-red-400/80 space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM: Professional Sleek -->
        <form action="{{ route('admin.manage-users.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="space-y-6">
                <!-- Full Name -->
                <div class="space-y-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: John Doe" required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all placeholder:text-slate-300 dark:placeholder:text-gray-700 font-medium">
                </div>

                <!-- Email -->
                <div class="space-y-2">
<<<<<<< HEAD
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Shift</label>
                    <select name="shift_id"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all appearance-none">
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
                <input type="password" name="password" required
                    class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-black/50 border border-white/10 rounded-2xl py-3.5 px-5 text-white focus:border-[var(--accent)] outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-orange-600 to-yellow-500 text-black font-bold py-4 rounded-2xl hover:shadow-[0_10px_30px_rgba(250,154,8,0.3)] active:scale-95 transition-all uppercase tracking-widest text-sm">
                Daftarkan User
            </button>
=======
                    <label
                        class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Email
                        Kredensial</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@billiardclass.com"
                        required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all placeholder:text-slate-300 dark:placeholder:text-gray-700 font-medium">
                </div>

                <!-- Role & Initial Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Hak
                            Akses (Role)</label>
                        <div class="relative">
                            <select name="role"
                                class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all appearance-none font-bold">
                                <option value="kitchen">Staff Dapur</option>
                                <option value="admin">Administrator</option>
                            </select>
                            <i
                                class="ri-arrow-down-s-line absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Password
                            Awal</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-[0.15em] ml-1">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] focus:ring-1 focus:ring-[#fa9a08]/20 outline-none transition-all font-medium">
                </div>
            </div>

            <!-- ACTION: Solid & Tactile -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-[#fa9a08] hover:bg-orange-600 text-black font-extrabold py-4 rounded-md shadow-lg shadow-orange-500/10 active:scale-[0.98] transition-all uppercase tracking-[0.2em] text-xs">
                    Daftarkan Staff
                </button>
                <p
                    class="text-center text-[10px] text-slate-400 dark:text-gray-600 font-bold uppercase tracking-widest mt-6">
                    Pastikan data yang dimasukkan sudah valid sesuai kebijakan keamanan sistem.
                </p>
            </div>
>>>>>>> 4dcc4bed6a4ba3ffc801d6f618a73bb4fab53c46
        </form>
    </div>
@endsection