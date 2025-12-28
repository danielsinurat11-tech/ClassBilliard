@extends('layouts.admin')

@section('title', 'Edit Keunggulan & Fasilitas - Admin')

@section('content')
<div class="min-h-screen bg-white dark:bg-[#050505] p-6 lg:p-10 transition-colors duration-300" x-data="{ showCreate: false }">
    
    <!-- HEADER STANDARD -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-10">
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#fa9a08] transition-all duration-300 mb-2">
                <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i> Dashboard
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Keunggulan <span class="text-[#fa9a08]">& Fasilitas</span></h1>
            <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Manajemen nilai tambah dan infrastruktur pendukung operasional.</p>
        </div>

        <button @click="showCreate = !showCreate" 
                class="bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest py-3 px-6 rounded-md transition-all shadow-sm flex items-center gap-2 active:scale-95">
            <i :class="showCreate ? 'ri-close-line' : 'ri-add-line'" class="text-lg"></i>
            <span x-text="showCreate ? 'Batalkan' : 'Tambah Fasilitas'"></span>
        </button>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="mb-8 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 rounded-md animate-in fade-in slide-in-from-top-4 duration-300">
            <i class="ri-checkbox-circle-fill text-emerald-500"></i>
            <span class="text-[11px] font-black uppercase tracking-widest text-emerald-500">{{ session('success') }}</span>
        </div>
    @endif

    <!-- CREATION MODULE (Sleek Accordion) -->
    <div x-show="showCreate" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="mb-12">
        <div class="bg-slate-50 dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#fa9a08] mb-8">Registrasi Fasilitas Baru</h2>
            <form action="{{ route('admin.keunggulan-fasilitas.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="space-y-2" x-data="{ icon: 'ri-focus-3-line' }">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500 flex justify-between">
                            Icon Class 
                            <a href="https://remixicon.com/" target="_blank" class="text-[#fa9a08] hover:underline lowercase tracking-normal font-medium">find icons</a>
                        </label>
                        <div class="relative">
                            <input type="text" name="icon" x-model="icon" placeholder="ri-table-fill" 
                                   class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md pl-10 pr-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                            <i :class="icon" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Nama Fasilitas</label>
                        <input type="text" name="name" required class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Sort Order</label>
                        <input type="number" name="order" value="0" class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all"></textarea>
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#fa9a08]"></div>
                            <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Aktifkan</span>
                        </label>
                    </div>
                </div>
                <div class="mt-10 flex justify-end">
                    <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-4 px-12 rounded-md transition-all active:scale-95 shadow-sm">
                        Simpan Fasilitas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EXISTING ITEMS LIST -->
    <div class="space-y-6">
        <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-gray-500 mb-6">Existing Assets Library</h2>
        
        <div class="grid grid-cols-1 gap-6">
            @foreach($keunggulanFasilitas as $fasilitas)
            <div class="group bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg overflow-hidden flex flex-col lg:flex-row hover:border-[#fa9a08]/50 transition-all duration-300">
                
                <!-- Icon Visual Section -->
                <div class="w-full lg:w-48 h-32 lg:h-auto bg-slate-50 dark:bg-white/[0.02] flex items-center justify-center border-r border-slate-200 dark:border-white/5">
                    <div class="w-16 h-16 rounded-lg bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-[#fa9a08] group-hover:scale-110 transition-transform duration-500">
                        <i class="{{ $fasilitas->icon }} text-3xl"></i>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="flex-1 p-8">
                    <form action="{{ route('admin.keunggulan-fasilitas.update', $fasilitas->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Icon Class</label>
                                <input type="text" name="icon" value="{{ $fasilitas->icon }}" class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Nama</label>
                                <input type="text" name="name" value="{{ $fasilitas->name }}" class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Deskripsi Singkat</label>
                                <textarea name="description" rows="2" class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">{{ $fasilitas->description }}</textarea>
                            </div>
                            
                            <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-6 pt-4 border-t border-slate-100 dark:border-white/5">
                                <div class="flex items-center gap-8">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 block">Sort</label>
                                        <input type="number" name="order" value="{{ $fasilitas->order }}" class="bg-transparent border-none p-0 text-sm font-bold text-slate-900 dark:text-white focus:ring-0 w-12">
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" {{ $fasilitas->is_active ? 'checked' : '' }} value="1" class="sr-only peer">
                                        <div class="w-10 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#fa9a08]"></div>
                                        <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</span>
                                    </label>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-2.5 px-6 rounded-md hover:bg-slate-800 dark:hover:bg-slate-200 transition-all active:scale-95">
                                        Update
                                    </button>
                                    <button type="button" 
                                            onclick="if(confirm('Hapus fasilitas ini secara permanen?')) document.getElementById('delete-form-{{ $fasilitas->id }}').submit();"
                                            class="bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest py-2.5 px-6 rounded-md hover:bg-red-500 hover:text-white transition-all active:scale-95">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form-{{ $fasilitas->id }}" action="{{ route('admin.keunggulan-fasilitas.destroy', $fasilitas->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    input:focus, textarea:focus {
        border-color: #fa9a08 !important;
        box-shadow: 0 0 0 1px rgba(250, 154, 8, 0.1) !important;
    }
</style>
@endsection