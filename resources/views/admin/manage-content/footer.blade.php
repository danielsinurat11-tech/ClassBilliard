@extends('layouts.admin')

@section('title', 'Edit Footer - Admin')

@section('content')
<div class="min-h-screen bg-white dark:bg-[#050505] p-6 lg:p-10 transition-colors duration-300">
    
    <!-- HEADER STANDARD -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-10">
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#fa9a08] transition-all duration-300 mb-2">
                <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i> Back to Dashboard
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Footer <span class="text-[#fa9a08]">Configuration</span></h1>
            <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Manajemen informasi global, media sosial, dan data operasional outlet.</p>
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

    <form action="{{ route('admin.footer.update') }}" method="POST" class="space-y-12">
        @csrf

        <!-- SECTION 1: BRAND IDENTITY -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Brand Narrative</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Informasi singkat mengenai perusahaan yang akan muncul di area utama footer.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">About Company Text</label>
                    <textarea name="about_text" rows="4" 
                              class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all leading-relaxed"
                              placeholder="Gambarkan visi singkat perusahaan...">{{ $footer->about_text ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: SOCIAL CONNECTORS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-[#fa9a08]">Social Ecosystem</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Tautkan akun media sosial resmi untuk meningkatkan engagement digital.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-facebook-box-fill mr-1"></i> Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $footer->facebook_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-instagram-line mr-1"></i> Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $footer->instagram_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-twitter-x-line mr-1"></i> Twitter / X URL</label>
                        <input type="url" name="twitter_url" value="{{ $footer->twitter_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500"><i class="ri-youtube-line mr-1"></i> YouTube URL</label>
                        <input type="url" name="youtube_url" value="{{ $footer->youtube_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: CONTACT & LOCATION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Physical Access</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Data lokasi fisik dan kontak bantuan pelanggan.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8 space-y-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Business Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all leading-relaxed">{{ $footer->address ?? '' }}</textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Official Phone</label>
                        <input type="text" name="phone" value="{{ $footer->phone ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Inquiry Email</label>
                        <input type="email" name="email" value="{{ $footer->email ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Google Maps Integration URL</label>
                        <input type="url" name="google_maps_url" value="{{ $footer->google_maps_url ?? '' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: OPERATIONAL HOURS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Business Hours</h2>
                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 leading-relaxed">Atur waktu operasional rutin untuk menginformasikan pelanggan.</p>
            </div>
            <div class="lg:col-span-8 bg-white dark:bg-[#0A0A0A] border border-slate-200 dark:border-white/5 rounded-lg p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Monday - Friday</label>
                        <input type="text" name="monday_friday_hours" value="{{ $footer->monday_friday_hours ?? '10:00 - 22:00' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm font-bold text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Saturday - Sunday</label>
                        <input type="text" name="saturday_sunday_hours" value="{{ $footer->saturday_sunday_hours ?? '09:00 - 23:00' }}" 
                               class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md px-4 py-2.5 text-sm font-bold text-slate-900 dark:text-white focus:border-[#fa9a08] outline-none transition-all">
                    </div>
                </div>

                <!-- GLOBAL VISIBILITY TOGGLE -->
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-white/5">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ ($footer && $footer->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#fa9a08]"></div>
                        <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Footer Visibility Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- STICKY ACTION AREA -->
        <div class="pt-12 border-t border-slate-200 dark:border-white/5 flex justify-end">
            <button type="submit" class="w-full md:w-auto bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest py-4 px-16 rounded-md transition-all shadow-lg shadow-orange-500/10 active:scale-95">
                Save Global Footer Configuration
            </button>
        </div>
    </form>
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