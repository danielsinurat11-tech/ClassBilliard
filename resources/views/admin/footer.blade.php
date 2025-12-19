@extends('layouts.admin')

@section('title', 'Edit Footer - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit Footer</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.footer.update') }}" method="POST" class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20">
            @csrf

            <div class="mb-6">
                <label class="block text-white mb-2">About Text</label>
                <textarea name="about_text" rows="4" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $footer->about_text ?? '' }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-white mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ $footer->facebook_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-white mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ $footer->instagram_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-white mb-2">Twitter URL</label>
                    <input type="url" name="twitter_url" value="{{ $footer->twitter_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-white mb-2">YouTube URL</label>
                    <input type="url" name="youtube_url" value="{{ $footer->youtube_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $footer->address ?? '' }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-white mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ $footer->phone ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-white mb-2">Email</label>
                    <input type="email" name="email" value="{{ $footer->email ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Google Maps URL</label>
                <input type="url" name="google_maps_url" value="{{ $footer->google_maps_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-white mb-2">Monday - Friday Hours</label>
                    <input type="text" name="monday_friday_hours" value="{{ $footer->monday_friday_hours ?? '10:00 - 22:00' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-white mb-2">Saturday - Sunday Hours</label>
                    <input type="text" name="saturday_sunday_hours" value="{{ $footer->saturday_sunday_hours ?? '09:00 - 23:00' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-white">
                    <input type="checkbox" name="is_active" {{ ($footer && $footer->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                    <span>Active</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-[#fa9a08] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#e19e2b] transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection

