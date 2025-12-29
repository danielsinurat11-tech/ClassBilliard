@extends('layouts.admin')

@section('title', 'Edit Staff Access')

@section('content')
    <div class="max-w-2xl mx-auto animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- HEADER & NAVIGATION -->
        <div class="flex items-center gap-5 mb-10 pb-6 border-b border-slate-200 dark:border-white/5">
            <a href="{{ route('admin.manage-users.index') }}"
                class="w-9 h-9 flex items-center justify-center rounded-md border border-slate-200 dark:border-white/10 text-slate-500 dark:text-gray-400 hover:text-[#fa9a08] hover:border-[#fa9a08]/50 transition-all duration-300">
                <i class="ri-arrow-left-s-line text-xl"></i>
            </a>
            <div class="space-y-0.5">
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Akses Staff</h1>
                <p class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                    Identity: <span class="text-[#fa9a08]">{{ $user->name }}</span>
                </p>
            </div>
        </div>

        <form action="{{ route('admin.manage-users.update', $user->id) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            <!-- SECTION 1: PROFILE & ACCESS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- NAMA LENGKAP -->
                <div class="space-y-2">
                    <label
                        class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all placeholder:text-gray-600">
                </div>

                <!-- CUSTOM DROPDOWN: ROLE -->
                @php $currentRole = $user->getRoleNames()->first(); @endphp
                <div class="space-y-2" x-data="{ 
                    open: false, 
                    selected: '{{ old('role', $currentRole) }}',
                    options: {
                        'admin': 'Administrator',
                        'kitchen': 'Kitchen Staff',
                        'super_admin': 'Super Admin'
                    }
                }">
                    <label
                        class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Hak
                        Akses (Role)</label>
                    <div class="relative">
                        <input type="hidden" name="role" :value="selected">
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-left text-slate-900 dark:text-white hover:border-[#fa9a08]/50 transition-all">
                            <span x-text="options[selected] || 'Pilih Role'"></span>
                            <i class="ri-arrow-down-s-line text-slate-400 transition-transform duration-300"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-2 bg-[#0A0A0A] border border-white/10 rounded-md shadow-2xl overflow-hidden backdrop-blur-md">
                            <template x-for="(label, value) in options">
                                <div @click="selected = value; open = false"
                                    class="px-4 py-3 text-sm text-gray-400 hover:bg-[#fa9a08] hover:text-black cursor-pointer transition-colors font-medium flex justify-between items-center"
                                    :class="selected === value ? 'text-[#fa9a08] bg-white/[0.02]' : ''">
                                    <span x-text="label"></span>
                                    <i x-show="selected === value" class="ri-check-line"></i>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="space-y-2 md:col-span-2">
                    <label
                        class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Email
                        Sistem</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all placeholder:text-gray-600">
                </div>

                <!-- CUSTOM DROPDOWN: SHIFT -->
                <div class="space-y-2 md:col-span-2" x-data="{ 
                    open: false, 
                    selected: '{{ old('shift_id', $user->shift_id) }}',
                    selectedLabel: '{{ $user->shift ? $user->shift->name : 'Tidak ada shift' }}'
                }">
                    <label
                        class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Shift
                        Operasional</label>
                    <div class="relative">
                        <input type="hidden" name="shift_id" :value="selected">
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-left text-slate-900 dark:text-white hover:border-[#fa9a08]/50 transition-all">
                            <span x-text="selectedLabel"></span>
                            <i class="ri-arrow-down-s-line text-slate-400 transition-transform duration-300"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-2 bg-[#0A0A0A] border border-white/10 rounded-md shadow-2xl max-h-60 overflow-y-auto backdrop-blur-md">
                            <div @click="selected = ''; selectedLabel = 'Tidak ada shift'; open = false"
                                class="px-4 py-3 text-sm text-gray-500 hover:bg-white/5 cursor-pointer italic border-b border-white/5">
                                None</div>
                            @foreach($shifts as $shift)
                                <div @click="selected = '{{ $shift->id }}'; selectedLabel = '{{ $shift->name }}'; open = false"
                                    class="px-4 py-3 text-sm text-gray-400 hover:bg-[#fa9a08] hover:text-black cursor-pointer transition-colors font-medium flex justify-between items-center group">
                                    <span>{{ $shift->name }}</span>
                                    <span
                                        class="text-[10px] opacity-50 group-hover:text-black/70">{{ $shift->start_time->format('H:i') }}
                                        - {{ $shift->end_time->format('H:i') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 font-medium italic">Assign shift diperlukan agar
                        manajemen order ter-sinkronisasi dengan benar.</p>
                </div>
            </div>

            <!-- SECTION 2: SECURITY RESET -->
            <div class="pt-10 border-t border-slate-200 dark:border-white/5 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-md bg-[#fa9a08]/10 flex items-center justify-center text-[#fa9a08]">
                        <i class="ri-lock-password-line text-lg"></i>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Reset
                        Keamanan (Opsional)</h3>
                </div>

                <!-- SUBTLE CALLOUT (Replacing Box-in-Box) -->
                <div class="flex gap-3 px-4 py-3 border-l-2 border-blue-500 bg-blue-500/5 dark:bg-blue-500/[0.02]">
                    <i class="ri-information-line text-blue-500 mt-0.5"></i>
                    <p class="text-[11px] text-slate-600 dark:text-blue-400/80 font-medium leading-relaxed">
                        Kosongkan kolom password di bawah jika Anda tidak ingin mengubah kredensial akses saat ini.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Password
                            Baru</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-gray-500 uppercase tracking-widest ml-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col gap-4 pt-6">
                <button type="submit"
                    class="w-full bg-[#fa9a08] hover:bg-orange-600 text-black text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-md transition-all shadow-sm active:scale-[0.98]">
                    Simpan Perubahan Data
                </button>
                <a href="{{ route('admin.manage-users.index') }}"
                    class="text-center text-[10px] font-black text-slate-400 dark:text-gray-500 hover:text-red-500 uppercase tracking-widest transition-all">
                    Batalkan dan Kembali
                </a>
            </div>
        </form>
    </div>
@endsection