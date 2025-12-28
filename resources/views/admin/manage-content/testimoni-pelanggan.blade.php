@extends('layouts.admin')

@section('title', 'Edit Testimoni Pelanggan - Admin')

@section('content')
    <div class="min-h-screen bg-white dark:bg-[#050505] p-6 lg:p-10 transition-colors duration-300"
        x-data="{ showCreate: false }">

        <!-- HEADER STANDARD -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-10">
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#fa9a08] transition-all duration-300 mb-2">
                    <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Customer <span
                        class="text-[#fa9a08]">Testimonials</span></h1>
                <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Kelola apresiasi dan feedback pelanggan
                    untuk membangun kepercayaan brand.</p>
            </div>

            <button @click="showCreate = !showCreate"
                class="bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest py-3 px-6 rounded-md transition-all shadow-sm flex items-center gap-2 active:scale-95">
                <i :class="showCreate ? 'ri-close-line' : 'ri-add-line'" class="text-lg"></i>
                <span x-text="showCreate ? 'Batalkan' : 'Tambah Testimoni'"></span>
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
        <div x-show="showCreate" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-12">
            <div class="bg-slate-50 dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#fa9a08] mb-8">Input Testimoni Baru</h2>
                <form action="{{ route('admin.testimoni-pelanggan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Nama
                                Pelanggan</label>
                            <input type="text" name="customer_name" required
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Role
                                / Jabatan</label>
                            <input type="text" name="customer_role" placeholder="e.g. Regular Guest"
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Rating
                                Score (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" value="5"
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm font-bold text-[#fa9a08] focus:border-[#fa9a08] outline-none transition-all">
                        </div>
                        <div class="md:col-span-2 lg:col-span-3 space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Testimonial
                                Content</label>
                            <textarea name="testimonial" rows="4" required
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all leading-relaxed"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Customer
                                Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#fa9a08] file:text-black hover:file:bg-orange-600">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Sort
                                Order</label>
                            <input type="number" name="order" value="0"
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" checked value="1" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#fa9a08]">
                                </div>
                                <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Publish
                                    to Site</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-10 flex justify-end">
                        <button type="submit"
                            class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-4 px-12 rounded-md transition-all active:scale-95 shadow-sm">
                            Submit Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TESTIMONIALS LIBRARY -->
        <div class="space-y-6">
            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-gray-500 mb-6">Verified
                Customer Feedback Library</h2>

            <div class="grid grid-cols-1 gap-8">
                @foreach($testimonis as $testimoni)
                    <div
                        class="group bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg overflow-hidden transition-all duration-300 hover:border-[#fa9a08]/50 p-8">

                        <div class="flex flex-col lg:flex-row gap-10">
                            <!-- Left: Profile & Visual -->
                            <div class="lg:w-64 space-y-4 shrink-0">
                                <div class="relative w-24 h-24 mx-auto lg:mx-0">
                                    @if($testimoni->photo)
                                        <img src="{{ asset('storage/' . $testimoni->photo) }}" alt="{{ $testimoni->customer_name }}"
                                            class="w-full h-full rounded-md object-cover border border-slate-200 dark:border-white/10">
                                    @else
                                        <div
                                            class="w-full h-full rounded-md bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                            <i class="ri-user-3-line text-3xl"></i>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute -bottom-2 -right-2 bg-[#fa9a08] text-black w-6 h-6 rounded-md flex items-center justify-center text-xs border-2 border-white dark:border-[#0A0A0A]">
                                        <i class="ri-chat-quote-line"></i>
                                    </div>
                                </div>
                                <div class="text-center lg:text-left space-y-1">
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                        {{ $testimoni->customer_name }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $testimoni->customer_role }}</p>
                                    <div class="flex justify-center lg:justify-start gap-0.5 text-[#fa9a08] pt-2">
                                        @for($i = 0; $i < 5; $i++)
                                            <i
                                                class="{{ $i < $testimoni->rating ? 'ri-star-fill' : 'ri-star-line opacity-30' }} text-xs"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Edit Form -->
                            <div class="flex-1">
                                <form action="{{ route('admin.testimoni-pelanggan.update', $testimoni->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Update
                                                Content</label>
                                            <textarea name="testimonial" rows="3"
                                                class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all leading-relaxed">{{ $testimoni->testimonial }}</textarea>
                                        </div>
                                        <div class="space-y-6">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-1">
                                                    <label
                                                        class="text-[9px] font-black uppercase tracking-widest text-slate-400">Rating</label>
                                                    <input type="number" name="rating" min="1" max="5"
                                                        value="{{ $testimoni->rating }}"
                                                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-3 py-1.5 text-sm font-bold text-[#fa9a08] outline-none">
                                                </div>
                                                <div class="space-y-1">
                                                    <label
                                                        class="text-[9px] font-black uppercase tracking-widest text-slate-400">Order</label>
                                                    <input type="number" name="order" value="{{ $testimoni->order }}"
                                                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-3 py-1.5 text-sm font-bold text-slate-900 dark:text-white outline-none">
                                                </div>
                                            </div>
                                            <div class="space-y-1">
                                                <label
                                                    class="text-[9px] font-black uppercase tracking-widest text-slate-400">Photo
                                                    Update</label>
                                                <input type="file" name="photo" accept="image/*"
                                                    class="text-[10px] text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-slate-200 dark:file:bg-white/10 file:text-[9px] file:font-black file:uppercase">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Actions -->
                                    <div
                                        class="mt-8 flex flex-wrap items-center justify-between gap-6 pt-6 border-t border-slate-100 dark:border-white/5">
                                        <div class="flex items-center gap-6">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" {{ $testimoni->is_active ? 'checked' : '' }} value="1" class="sr-only peer">
                                                <div
                                                    class="w-10 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#fa9a08]">
                                                </div>
                                                <span
                                                    class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Visible</span>
                                            </label>

                                            <div class="hidden md:block">
                                                <p class="text-[9px] text-slate-400 font-medium uppercase tracking-tight">
                                                    Identity: <span
                                                        class="text-slate-900 dark:text-white font-bold">{{ $testimoni->customer_name }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <button type="submit"
                                                class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-2.5 px-6 rounded-md hover:bg-slate-800 dark:hover:bg-slate-200 transition-all active:scale-95 shadow-sm">
                                                Update Feedback
                                            </button>
                                            <button type="button"
                                                onclick="if(confirm('Data testimoni akan dihapus permanen. Lanjutkan?')) document.getElementById('delete-form-{{ $testimoni->id }}').submit();"
                                                class="bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest py-2.5 px-6 rounded-md hover:bg-red-500 hover:text-white transition-all active:scale-95">
                                                Purge
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <form id="delete-form-{{ $testimoni->id }}"
                                    action="{{ route('admin.testimoni-pelanggan.destroy', $testimoni->id) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        input:focus,
        textarea:focus {
            border-color: #fa9a08 !important;
            box-shadow: 0 0 0 1px rgba(250, 154, 8, 0.1) !important;
        }
    </style>
@endsection