@extends('layouts.admin')

@section('title', 'Edit Testimoni Pelanggan - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit Testimoni Pelanggan</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6">Tambah Testimoni</h2>
            <form action="{{ route('admin.testimoni-pelanggan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white mb-2">Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Role</label>
                        <input type="text" name="customer_role" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-white mb-2">Testimonial</label>
                        <textarea name="testimonial" rows="4" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-white mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" min="1" max="5" value="5" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Order</label>
                        <input type="number" name="order" value="0" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 text-white">
                            <input type="checkbox" name="is_active" checked class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-[#fa9a08] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#e19e2b] transition-colors">
                    Tambah Testimoni
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($testimonis as $testimoni)
            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20">
                <div class="flex items-start gap-4 mb-4">
                    @if($testimoni->photo)
                    <img src="{{ asset('storage/' . $testimoni->photo) }}" alt="{{ $testimoni->customer_name }}" class="w-16 h-16 rounded-full object-cover">
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $testimoni->customer_name }}</h3>
                        <p class="text-gray-400">{{ $testimoni->customer_role }}</p>
                        <div class="flex gap-1 text-[#fa9a08] mt-2">
                            @for($i = 0; $i < $testimoni->rating; $i++)
                            <i class="ri-star-fill"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.testimoni-pelanggan.update', $testimoni->id) }}" method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-white mb-2">Nama Pelanggan</label>
                        <input type="text" name="customer_name" value="{{ $testimoni->customer_name }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Role</label>
                        <input type="text" name="customer_role" value="{{ $testimoni->customer_role }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-white mb-2">Testimonial</label>
                        <textarea name="testimonial" rows="3" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $testimoni->testimonial }}</textarea>
                    </div>
                    <div>
                        <label class="block text-white mb-2">Rating</label>
                        <input type="number" name="rating" min="1" max="5" value="{{ $testimoni->rating }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Order</label>
                        <input type="number" name="order" value="{{ $testimoni->order }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-white">
                            <input type="checkbox" name="is_active" {{ $testimoni->is_active ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                            <span>Active</span>
                        </label>
                        <button type="submit" class="bg-[#fa9a08] text-white px-4 py-2 rounded-lg hover:bg-[#e19e2b] transition-colors">Update</button>
                        <a href="{{ route('admin.testimoni-pelanggan.destroy', $testimoni->id) }}" onclick="event.preventDefault(); if(confirm('Yakin hapus?')) document.getElementById('delete-form-{{ $testimoni->id }}').submit();" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">Hapus</a>
                    </div>
                </form>
                <form id="delete-form-{{ $testimoni->id }}" action="{{ route('admin.testimoni-pelanggan.destroy', $testimoni->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

