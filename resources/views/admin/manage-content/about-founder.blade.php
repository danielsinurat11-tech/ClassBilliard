@extends('layouts.admin')

@section('title', 'Edit About Founder - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit About Founder</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.about-founder.update') }}" method="POST" class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20">
            @csrf

            <div class="mb-6">
                <label class="block text-white mb-2">Title</label>
                <input type="text" name="title" value="{{ $aboutFounder->title ?? 'Tentang Founder' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Subtitle</label>
                <textarea name="subtitle" rows="2" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $aboutFounder->subtitle ?? '' }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Nama Founder</label>
                <input type="text" name="name" value="{{ $aboutFounder->name ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Description</label>
                <textarea name="description" rows="6" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $aboutFounder->description ?? '' }}</textarea>
            </div>

            

            <div class="mb-6">
                <label class="block text-white mb-2">Facebook URL</label>
                <input type="url" name="facebook_url" value="{{ $aboutFounder->facebook_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Instagram URL</label>
                <input type="url" name="instagram_url" value="{{ $aboutFounder->instagram_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">LinkedIn URL</label>
                <input type="url" name="linkedin_url" value="{{ $aboutFounder->linkedin_url ?? '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-white">
                    <input type="checkbox" name="is_active" {{ ($aboutFounder && $aboutFounder->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
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

