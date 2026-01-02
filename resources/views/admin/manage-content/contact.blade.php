@extends('layouts.admin')

@section('title', 'Edit Contact - Admin')

@section('content')
<div class="min-h-screen bg-white dark:bg-[#050505] p-6 lg:p-10 transition-colors duration-300">
    
    <!-- HEADER STANDARD -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-10">
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 transition-all duration-300 mb-2"
                @mouseenter="$el.style.color = 'var(--primary-color)'"
                @mouseleave="$el.style.color = ''">
                <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i> Back to Dashboard
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Contact <span style="color: var(--primary-color);">Page</span></h1>
            <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Manajemen konten halaman Contact Us yang akan ditampilkan di website.</p>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="mb-8 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 rounded-md animate-in fade-in slide-in-from-top-4 duration-300">
            <i class="ri-checkbox-circle-fill text-emerald-500"></i>
            <span class="text-[11px] font-black uppercase tracking-widest text-emerald-500">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.cms.contact.update') }}" method="POST" class="space-y-12">
        @csrf

        <!-- SECTION 1: PAGE HEADER -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Page Header</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Judul dan deskripsi yang akan muncul di bagian atas halaman Contact Us.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Page Title</label>
                    <input type="text" name="title" value="{{ $contact->title ?? 'Contact Us' }}" 
                           class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                           placeholder="e.g. Contact Us">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ $contact->subtitle ?? '' }}" 
                           class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                           placeholder="e.g. Get in Touch with Us">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-3 text-sm text-slate-900 dark:text-white outline-none transition-all leading-relaxed"
                              @focus="$el.style.borderColor = 'var(--primary-color)'"
                              @blur="$el.style.borderColor = ''"
                              placeholder="Deskripsi singkat tentang bagaimana pelanggan dapat menghubungi Anda...">{{ $contact->description ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: CONTACT INFORMATION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em]" style="color: var(--primary-color);">Contact Information</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Informasi kontak utama yang akan ditampilkan di halaman Contact Us.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Location Name</label>
                    <input type="text" name="location_name" value="{{ $contact->location_name ?? '' }}" 
                           class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                           placeholder="e.g. Class Billiard Main Hall">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Business Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-3 text-sm text-slate-900 dark:text-white outline-none transition-all leading-relaxed" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                              placeholder="Alamat lengkap bisnis...">{{ $contact->address ?? '' }}</textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-phone-line mr-1"></i> Phone Number</label>
                        <input type="text" name="phone" value="{{ $contact->phone ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                               placeholder="e.g. +62 812 3456 7890">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-mail-line mr-1"></i> Email Address</label>
                        <input type="email" name="email" value="{{ $contact->email ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                               placeholder="e.g. info@classbilliard.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-whatsapp-line mr-1"></i> WhatsApp URL</label>
                        <input type="url" name="whatsapp" value="{{ $contact->whatsapp ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                               placeholder="https://wa.me/6281234567890">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-time-line mr-1"></i> Opening Hours</label>
                        <input type="text" name="opening_hours" value="{{ $contact->opening_hours ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                               placeholder="e.g. Mon - Sun: 10AM - 02AM">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: MAP INTEGRATION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Map Location</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Integrasikan Google Maps untuk menampilkan lokasi bisnis di halaman Contact Us.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Google Maps Embed URL</label>
                    <p class="text-[9px] text-slate-500 dark:text-gray-600 mb-2">
                        <strong>Cara:</strong><br>
                        1. Buka Google Maps → cari lokasi<br>
                        2. Click "Share" → "Embed a map"<br>
                        3. Copy seluruh iframe code (atau hanya URL)<br>
                        4. Paste di field bawah ini
                    </p>
                    <textarea name="google_maps_url" rows="4"
                           class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all font-mono" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''" placeholder="Paste full iframe HTML atau embed URL">{{ $contact->google_maps_url ?? '' }}</textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Map URL (Alternative)</label>
                    <input type="url" name="map_url" value="{{ $contact->map_url ?? '' }}" 
                           class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''"
                           placeholder="https://maps.app.goo.gl/...">
                </div>
            </div>
        </div>

        <!-- SECTION 4: SOCIAL MEDIA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em]" style="color: var(--primary-color);">Social Media</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Tautkan akun media sosial untuk meningkatkan engagement.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-facebook-box-fill mr-1"></i> Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $contact->facebook_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-instagram-line mr-1"></i> Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $contact->instagram_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-twitter-x-line mr-1"></i> Twitter / X URL</label>
                        <input type="url" name="twitter_url" value="{{ $contact->twitter_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-youtube-line mr-1"></i> YouTube URL</label>
                        <input type="url" name="youtube_url" value="{{ $contact->youtube_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white outline-none transition-all" @focus="$el.style.borderColor = 'var(--primary-color)'" @blur="$el.style.borderColor = ''">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: VISIBILITY TOGGLE -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Page Visibility</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Aktifkan atau nonaktifkan tampilan halaman Contact Us di website.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <label class="relative inline-flex items-center cursor-pointer"
                    x-data="{ isActive: {{ ($contact && $contact->is_active) ? 'true' : 'false' }} }">
                    <input type="checkbox" name="is_active" value="1" {{ ($contact && $contact->is_active) ? 'checked' : '' }} class="sr-only peer" @change="isActive = !isActive">
                    <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"
                        :style="{ backgroundColor: isActive ? 'var(--primary-color)' : '#cbd5e1' }"></div>
                    <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Contact Page Active</span>
                </label>
            </div>
        </div>

        <!-- STICKY ACTION AREA -->
        <div class="pt-12 border-t border-slate-200 dark:border-white/5 flex justify-end">
            <button type="submit" class="w-full md:w-auto text-black text-[10px] font-black uppercase tracking-widest py-4 px-16 rounded-md transition-all shadow-lg active:scale-95" style="background-color: var(--primary-color); box-shadow: 0 10px 15px -3px rgba(var(--primary-color-rgb), 0.1);" @mouseenter="$el.style.opacity = '0.85'" @mouseleave="$el.style.opacity = '1'">
                Save Contact Page Configuration
            </button>
        </div>
    </form>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    input:focus, textarea:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 1px rgba(var(--primary-color-rgb), 0.1) !important;
    }
</style>
@endsection

