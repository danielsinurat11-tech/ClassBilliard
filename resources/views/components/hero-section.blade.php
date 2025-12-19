{{-- Hero Section --}}
@php
    try {
        $hero = \App\Models\HeroSection::where('is_active', true)->first();
        if (!$hero) {
            $hero = \App\Models\HeroSection::first();
        }
        $logoImage = ($hero && $hero->logo_image) ? asset('storage/' . $hero->logo_image) : null;
        $title = $hero ? $hero->title : 'CLASS';
        $subtitle = $hero && $hero->subtitle ? $hero->subtitle : 'BILLIARD';
    } catch (\Exception $e) {
        $logoImage = null;
        $title = 'CLASS';
        $subtitle = 'BILLIARD';
    }
@endphp

<style>
    @font-face {
        font-family: 'Rumonds';
        src: url('{{ asset('assets/font/rumonds-font/rumonds.otf') }}') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    .hero-class-text {
        font-family: 'Rumonds', serif;
        font-weight: normal;
        letter-spacing: 0.02em;
    }
    
    .hero-container {
        position: relative;
        overflow: hidden;
        background-image: url('{{ asset('assets/Hero Section.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    /* Left Section with Text - Padding disesuaikan dengan garis merah */
    .hero-left-section {
        position: absolute;
        top: 0;
        left: 0;
        width: 60%;
        height: 100%;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding-left: clamp(3rem, 8vw, 6rem);
        padding-top: clamp(2rem, 5vh, 4rem);
    }
    
    /* Text Container */
    .hero-text-container {
        position: absolute;
        inset: 0;
        z-index: 20;
    }
    
    /* CLASS Text - Large, White, Gothic */
    .hero-class-title {
        font-family: 'Rumonds', serif;
        font-size: 380px;
        font-weight: normal;
        color: white;
        line-height: 0.9;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        letter-spacing: 0.02em;
        position: absolute;
        left: clamp(12rem, 20vw, 26rem);
        top: 43%;
        transform: translateY(-50%);
    }
    
    /* BILLIARD Text - Disesuaikan dengan banner merah di background */
    .hero-billiard-text {
        font-family: 'Hina Mincho', serif;
        font-size: 50px;
        font-weight: bold;
        color: white;
        letter-spacing: 0.1em;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        position: absolute;
        left: clamp(20rem, 36vw, 36rem);
        top: 75%;
        transform: translateY(-50%);
    }
    
    /* Right Overlay for White Logo */
    .hero-right-overlay {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .hero-logo-white {
        filter: drop-shadow(0 4px 25px rgba(0, 0, 0, 0.9));
        max-width: 450px;
        width: 100%;
        height: auto;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-left-section {
            width: 100%;
            padding-left: 1.5rem;
            padding-top: 2rem;
        }
        .hero-class-title {
            left: 3rem;
            top: 44%;
        }
        .hero-billiard-text {
            left: 3.25rem;
            top: 80%;
        }
        .hero-right-overlay {
            width: 100%;
            padding: 1rem;
        }
        .hero-logo-white {
            max-width: 250px;
        }
    }
</style>

<div class="hero-background hero-container relative w-full h-[calc(100vh-6rem)] min-h-[500px] max-md:h-[calc(100vh-6rem)] max-md:min-h-[400px]">
    {{-- Left Section: Text Content --}}
    <div class="hero-left-section">
        {{-- Text Container --}}
        <div class="hero-text-container">
            {{-- CLASS Text (Large, White, Gothic) --}}
            <h1 class="hero-class-title">
                {{ $title }}
            </h1>
            
            {{-- BILLIARD Text - Disesuaikan dengan banner merah di background --}}
            @if($subtitle)
            <div class="hero-billiard-text">
                {{ $subtitle }}
            </div>
            @endif
        </div>
    </div>
    
    {{-- Right Overlay: White Logo with Crown and 8-Ball --}}
    <div class="hero-right-overlay">
        @if($logoImage)
            <img src="{{ $logoImage }}" alt="CLASS BILLIARD Logo" class="hero-logo-white">
        @else
            {{-- Default Logo Placeholder --}}
            <div class="text-white text-center bg-black/70 p-6 md:p-8 rounded-2xl backdrop-blur-sm border-2 border-white/20">
                
            </div>
        @endif
    </div>
</div>
{{-- Hero Section --}}
