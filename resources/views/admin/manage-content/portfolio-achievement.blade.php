@extends('layouts.admin')

@section('title', 'Portfolio & Achievement - Admin')

@section('content')
    <div class="min-h-screen bg-white dark:bg-[#050505] p-6 lg:p-10 transition-colors duration-300"
        x-data="{ showCreate: false, type: 'achievement' }">

        <!-- HEADER STANDARD -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-10">
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#fa9a08] transition-all duration-300 mb-2">
                    <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i> Dashboard
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Portfolio <span
                        class="text-[#fa9a08]">& Achievements</span></h1>
                <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Manajemen rekam jejak, pencapaian numerik,
                    dan galeri visual entitas.</p>
            </div>

            <button @click="showCreate = !showCreate"
                class="bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest py-3 px-6 rounded-md transition-all shadow-sm flex items-center gap-2 active:scale-95">
                <i :class="showCreate ? 'ri-close-line' : 'ri-add-line'" class="text-lg"></i>
                <span x-text="showCreate ? 'Discard Item' : 'Create New Item'"></span>
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

        <!-- CREATION MODULE (Alpine.js Conditional Logic) -->
        <div x-show="showCreate" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-12">
            <div class="bg-slate-50 dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#fa9a08] mb-8">Asset Registration</h2>
                <form action="{{ route('admin.portfolio-achievement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Asset
                                Type</label>
                            <select name="type" x-model="type"
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all appearance-none cursor-pointer">
                                <option value="achievement">🏆 Achievement (Stats)</option>
                                <option value="gallery">🖼️ Gallery (Visual)</option>
                            </select>
                        </div>

                        <!-- Conditional Achievement Fields -->
                        <template x-if="type === 'achievement'">
                            <div class="contents">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Icon
                                        Class (Remix)</label>
                                    <input type="text" name="icon" placeholder="ri-trophy-fill"
                                        class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Numerical
                                        Value</label>
                                    <input type="text" name="number" placeholder="e.g. 100+"
                                        class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm font-bold text-[#fa9a08] focus:border-[#fa9a08] outline-none transition-all">
                                </div>
                            </div>
                        </template>

                        <!-- Conditional Gallery Fields -->
                        <template x-if="type === 'gallery'">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Gallery
                                    Image</label>
                                <input type="file" name="image" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#fa9a08] file:text-black">
                            </div>
                        </template>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Label
                                / Description</label>
                            <input type="text" name="label" required
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Sort
                                Order</label>
                            <input type="number" name="order" value="0"
                                class="w-full bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-10 flex justify-end">
                        <button type="submit"
                            class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-4 px-12 rounded-md transition-all active:scale-95 shadow-sm">
                            Confirm Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DATA GRID -->
        <div class="space-y-16">

            <!-- ACHIEVEMENTS SECTION -->
            <section>
                <h2
                    class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-gray-500 mb-8 flex items-center gap-4">
                    Achievements Library
                    <span class="h-px flex-1 bg-slate-100 dark:bg-white/5"></span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($achievements as $item)
                        <div
                            class="bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-6 group hover:border-[#fa9a08]/50 transition-all duration-300">
                            <form action="{{ route('admin.portfolio-achievement.update', $item->id) }}" method="POST"
                                class="space-y-6">
                                @csrf
                                <div class="flex items-start gap-6">
                                    <div
                                        class="w-16 h-16 rounded-md bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-center text-[#fa9a08] shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="{{ $item->icon }} text-3xl"></i>
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label
                                                class="text-[9px] font-black uppercase tracking-widest text-slate-400">Value</label>
                                            <input type="text" name="number" value="{{ $item->number }}"
                                                class="w-full bg-transparent border-none p-0 text-xl font-bold text-slate-900 dark:text-white focus:ring-0">
                                        </div>
                                        <div class="space-y-1 text-right">
                                            <label
                                                class="text-[9px] font-black uppercase tracking-widest text-slate-400">Order</label>
                                            <input type="number" name="order" value="{{ $item->order }}"
                                                class="w-full bg-transparent border-none p-0 text-xs font-bold text-slate-400 focus:ring-0 text-right">
                                        </div>
                                        <div class="col-span-2 space-y-1">
                                            <label
                                                class="text-[9px] font-black uppercase tracking-widest text-slate-400">Label</label>
                                            <input type="text" name="label" value="{{ $item->label }}"
                                                class="w-full bg-transparent border-none p-0 text-sm font-medium text-slate-500 dark:text-gray-400 focus:ring-0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Area -->
                                <div
                                    class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" {{ $item->is_active ? 'checked' : '' }}
                                            value="1" class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#fa9a08]">
                                        </div>
                                    </label>
                                    <div class="flex gap-2">
                                        <button type="submit"
                                            class="bg-slate-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest py-2 px-4 rounded-md transition-all active:scale-95">Update</button>
                                        <button type="button"
                                            onclick="if(confirm('Delete item?')) document.getElementById('delete-form-{{ $item->id }}').submit();"
                                            class="bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest py-2 px-4 rounded-md transition-all active:scale-95">Delete</button>
                                    </div>
                                </div>
                            </form>
                            <form id="delete-form-{{ $item->id }}"
                                action="{{ route('admin.portfolio-achievement.destroy', $item->id) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- GALLERY SECTION -->
            <section>
                <h2
                    class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-gray-500 mb-8 flex items-center gap-4">
                    Visual Gallery library
                    <span class="h-px flex-1 bg-slate-100 dark:bg-white/5"></span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($galleries as $item)
                        <div
                            class="group bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg overflow-hidden flex flex-col hover:border-[#fa9a08]/50 transition-all duration-300">
                            <div class="aspect-square bg-slate-100 dark:bg-white/5 relative overflow-hidden">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @endif
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300">
                                    <span class="text-[8px] font-black text-white uppercase tracking-widest">Order:
                                        {{ $item->order }}</span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <form action="{{ route('admin.portfolio-achievement.update', $item->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="image"
                                        class="text-[8px] text-slate-500 mb-4 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-100 dark:file:bg-white/10 file:text-[8px] file:uppercase">
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" {{ $item->is_active ? 'checked' : '' }}
                                                value="1" class="sr-only peer">
                                            <div
                                                class="w-8 h-4 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#fa9a08]">
                                            </div>
                                        </label>
                                        <div class="flex gap-1">
                                            <button type="submit"
                                                class="bg-slate-900 dark:bg-white text-white dark:text-black text-[8px] font-black uppercase px-3 py-2 rounded transition-all active:scale-95">Save</button>
                                            <button type="button"
                                                onclick="if(confirm('Delete gallery?')) document.getElementById('delete-form-gallery-{{ $item->id }}').submit();"
                                                class="bg-red-500/10 text-red-500 text-[8px] font-black uppercase px-3 py-2 rounded transition-all active:scale-95">Del</button>
                                        </div>
                                    </div>
                                </form>
                                <form id="delete-form-gallery-{{ $item->id }}"
                                    action="{{ route('admin.portfolio-achievement.destroy', $item->id) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        input:focus,
        select:focus {
            border-color: #fa9a08 !important;
            box-shadow: 0 0 0 1px rgba(250, 154, 8, 0.1) !important;
        }
    </style>
@endsection