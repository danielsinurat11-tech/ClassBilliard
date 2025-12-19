@extends('layouts.admin')

@section('title', 'Edit Hero Section - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit Hero Section</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.hero.update') }}" method="POST" enctype="multipart/form-data" class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20">
            @csrf

            <div class="mb-6">
                <div class="bg-blue-500/20 border border-blue-500 text-blue-400 px-4 py-3 rounded-lg mb-4">
                    <p class="text-sm">Background menggunakan gambar statis dari <code class="bg-black/30 px-2 py-1 rounded">assets/Hero Section.png</code></p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Logo Image (Logo dengan Crown dan 8-Ball)</label>
                @if($hero && $hero->logo_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $hero->logo_image) }}" alt="Current logo" class="w-full max-w-md rounded-lg">
                    </div>
                @endif
                <input type="file" name="logo_image" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Title (Text besar di atas)</label>
                <input type="text" name="title" value="{{ $hero->title ?? 'CLASS' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Subtitle (Text di banner merah)</label>
                <input type="text" name="subtitle" value="{{ $hero->subtitle ?? 'BILLIARD' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-white">
                    <input type="checkbox" name="is_active" {{ ($hero && $hero->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
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

