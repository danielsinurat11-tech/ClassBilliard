@extends('layouts.admin')

@section('title', 'Admin Dashboard - Billiard Class')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-8">Admin Dashboard</h1>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('admin.hero') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-image-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Hero Section</h3>
                <p class="text-gray-400 text-sm">Edit hero section dengan background dan title</p>
            </a>

            <a href="{{ route('admin.tentang-kami') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-information-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Tentang Kami</h3>
                <p class="text-gray-400 text-sm">Edit visi, misi, dan video profil</p>
            </a>

            <a href="{{ route('admin.about-founder') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-user-star-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">About Founder</h3>
                <p class="text-gray-400 text-sm">Edit informasi founder</p>
            </a>

            <a href="{{ route('admin.keunggulan-fasilitas') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-star-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Keunggulan & Fasilitas</h3>
                <p class="text-gray-400 text-sm">Kelola daftar fasilitas</p>
            </a>

            <a href="{{ route('admin.portfolio-achievement') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-trophy-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Portfolio & Achievement</h3>
                <p class="text-gray-400 text-sm">Kelola achievement dan galeri</p>
            </a>

            <a href="{{ route('admin.tim-kami') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-team-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Tim Kami</h3>
                <p class="text-gray-400 text-sm">Kelola anggota tim</p>
            </a>

            <a href="{{ route('admin.testimoni-pelanggan') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-chat-quote-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Testimoni Pelanggan</h3>
                <p class="text-gray-400 text-sm">Kelola testimoni pelanggan</p>
            </a>

            <a href="{{ route('admin.event') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-calendar-event-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Event</h3>
                <p class="text-gray-400 text-sm">Kelola event yang diadakan</p>
            </a>

            <a href="{{ route('admin.footer') }}" class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="ri-footer-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Footer</h3>
                <p class="text-gray-400 text-sm">Edit informasi footer</p>
            </a>
        </div>

        <div class="mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[#fa9a08] hover:text-amber-400 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span>Kembali ke Home</span>
            </a>
        </div>
    </div>
</div>
@endsection

