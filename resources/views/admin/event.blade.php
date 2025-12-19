@extends('layouts.admin')

@section('title', 'Edit Event - Admin')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-4xl font-bold text-white mb-8">Edit Event</h1>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1a1a1a] p-8 rounded-xl border border-[#fa9a08]/20 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6">Tambah Event</h2>
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white mb-2">Event Title</label>
                        <input type="text" name="event_title" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Event Date</label>
                        <input type="date" name="event_date" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-white mb-2">Event Description</label>
                        <textarea name="event_description" rows="3" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-white mb-2">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Link URL</label>
                        <input type="url" name="link_url" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
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
                    Tambah Event
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($events as $event)
            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20">
                @if($event->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->event_title }}" class="w-full max-w-md rounded-lg">
                </div>
                @endif
                <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-white mb-2">Event Title</label>
                        <input type="text" name="event_title" value="{{ $event->event_title }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Event Date</label>
                        <input type="date" name="event_date" value="{{ $event->event_date ? $event->event_date->format('Y-m-d') : '' }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-white mb-2">Event Description</label>
                        <textarea name="event_description" rows="3" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">{{ $event->event_description }}</textarea>
                    </div>
                    <div>
                        <label class="block text-white mb-2">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Link URL</label>
                        <input type="url" name="link_url" value="{{ $event->link_url }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Order</label>
                        <input type="number" name="order" value="{{ $event->order }}" class="w-full px-4 py-2 bg-[#2a2a2a] border border-[#fa9a08]/20 rounded-lg text-white">
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-white">
                            <input type="checkbox" name="is_active" {{ $event->is_active ? 'checked' : '' }} class="w-4 h-4 text-[#fa9a08] bg-[#2a2a2a] border-[#fa9a08]/20 rounded">
                            <span>Active</span>
                        </label>
                        <button type="submit" class="bg-[#fa9a08] text-white px-4 py-2 rounded-lg hover:bg-[#e19e2b] transition-colors">Update</button>
                        <a href="{{ route('admin.event.destroy', $event->id) }}" onclick="event.preventDefault(); if(confirm('Yakin hapus?')) document.getElementById('delete-form-{{ $event->id }}').submit();" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">Hapus</a>
                    </div>
                </form>
                <form id="delete-form-{{ $event->id }}" action="{{ route('admin.event.destroy', $event->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

