@extends('layouts.app')

@section('title', 'Beranda - Billiard Class')

@section('content')
    {{-- Hero Section --}}
    @include('components.hero-section')

    {{-- Stats Section (Optional - bisa ditambahkan jika diperlukan) --}}
    @php
        try {
            $achievements = \App\Models\PortfolioAchievement::where('type', 'achievement')->where('is_active', true)->orderBy('order')->limit(4)->get();
        } catch (\Exception $e) {
            $achievements = collect([]);
        }
    @endphp
    @if($achievements->count() > 0)
    <style>
        .stats-section {
            position: relative;
        }
        
        .stats-item {
            padding: 2rem 1rem;
            border-radius: 20px;
            background: linear-gradient(145deg, rgba(20, 20, 20, 0.8) 0%, rgba(15, 15, 15, 0.9) 100%);
            border: 1px solid rgba(250, 154, 8, 0.1);
            transition: all 0.4s;
        }
        
        .stats-item:hover {
            transform: translateY(-8px);
            border-color: rgba(250, 154, 8, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6),
                        0 0 40px rgba(250, 154, 8, 0.15);
        }
        
        .stats-icon {
            filter: drop-shadow(0 0 15px rgba(250, 154, 8, 0.5));
            transition: transform 0.4s;
        }
        
        .stats-item:hover .stats-icon {
            transform: scale(1.15) rotate(5deg);
        }
        
        .stats-number {
            background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <section class="stats-section py-16 bg-gradient-to-b from-black via-[#0a0a0a] to-black border-y border-[#fa9a08]/10">
        <div class="max-w-[1400px] mx-auto px-4 md:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($achievements->take(4) as $achievement)
                <div class="stats-item text-center">
                    @if($achievement->icon)
                    <div class="text-[#fa9a08] text-4xl md:text-5xl mb-3 stats-icon">
                        <i class="{{ $achievement->icon }}"></i>
                    </div>
                    @endif
                    @if($achievement->number)
                    <div class="text-3xl md:text-4xl font-bold mb-2 stats-number">{{ $achievement->number }}</div>
                    @endif
                    @if($achievement->label)
                    <div class="text-sm md:text-base text-gray-400">{{ $achievement->label }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Tentang Kami Section --}}
    @include('components.tentang-kami')

    {{-- About Founder Section --}}
    @include('components.about-founder')

    {{-- Keunggulan & Fasilitas Section --}}
    @include('components.keunggulan-fasilitas')

    {{-- Portfolio & Achievement Section --}}
    @include('components.portfolio-achievement')

    {{-- Tim Kami Section --}}
    @include('components.tim-kami')

    {{-- Testimoni Pelanggan Section --}}
    @include('components.testimoni-pelanggan')

    {{-- Event Section --}}
    @include('components.event')

    {{-- CTA Section --}}
    <section class="cta-section py-20 md:py-28 bg-gradient-to-b from-black via-[#0a0a0a] to-black relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#fa9a08]/10 via-transparent to-[#fa9a08]/10"></div>
        <div class="max-w-[1400px] mx-auto px-4 md:px-6 relative z-10">
            <div class="text-center">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">
                    Siap untuk Pengalaman Bermain Terbaik?
                </h2>
                <p class="text-lg md:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                    Pesan meja Anda sekarang dan nikmati fasilitas premium kami
                </p>
                <a href="{{ route('menu') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-[#fa9a08] to-[#e19e2b] text-black px-8 py-4 rounded-xl font-bold text-lg transition-all hover:shadow-2xl hover:shadow-[#fa9a08]/40 hover:scale-105">
                    <i class="ri-shopping-cart-line text-xl"></i>
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Footer Section --}}
    @include('components.footer')
@endsection
