@extends('layouts.admin')

@section('title', 'Edit Tim Kami - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit Tim Kami</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6">Tambah Anggota Tim</h2>
            <form action="{{ route('admin.tim-kami.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white mb-2">Nama</label>
                        <input type="text" name="name" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Position</label>
                        <input type="text" name="position" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Order</label>
                        <input type="number" name="order" value="0" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Facebook URL</label>
                        <input type="url" name="facebook_url" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Instagram URL</label>
                        <input type="url" name="instagram_url" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 text-white">
                            <input type="checkbox" name="is_active" checked class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-[#fa9a08] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#e19e2b] transition-colors">
                    Tambah Anggota
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($timKami as $member)
            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20">
                <div class="flex items-start gap-4 mb-4">
                    @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-20 h-20 rounded-full object-cover">
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $member->name }}</h3>
                        <p class="text-[#fa9a08]">{{ $member->position }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.tim-kami.update', $member->id) }}" method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-white mb-2">Nama</label>
                        <input type="text" name="name" value="{{ $member->name }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Position</label>
                        <input type="text" name="position" value="{{ $member->position }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Order</label>
                        <input type="number" name="order" value="{{ $member->order }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ $member->facebook_url }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $member->instagram_url }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="{{ $member->linkedin_url }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-white">
                            <input type="checkbox" name="is_active" {{ $member->is_active ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                            <span>Active</span>
                        </label>
                        <button type="submit" class="bg-[#fa9a08] text-white px-4 py-2 rounded-lg hover:bg-[#e19e2b] transition-colors">Update</button>
                        <a href="{{ route('admin.tim-kami.destroy', $member->id) }}" onclick="event.preventDefault(); if(confirm('Yakin hapus?')) document.getElementById('delete-form-{{ $member->id }}').submit();" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">Hapus</a>
                    </div>
                </form>
                <form id="delete-form-{{ $member->id }}" action="{{ route('admin.tim-kami.destroy', $member->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

