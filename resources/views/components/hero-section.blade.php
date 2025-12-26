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
    
    .hero-container {
        position: relative;
        overflow: hidden;
        background-image: url('{{ asset('assets/Hero Section.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    
    .hero-container::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.5) 100%);
        z-index: 1;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: calc(100vh - 6rem);
        padding: 0 clamp(1.5rem, 5vw, 6rem);
    }
    
    .hero-left {
        flex: 1;
        max-width: 60%;
        padding-right: 3rem;
    }
    
    .hero-text-container {
        position: relative;
    }
    
    .hero-class-title {
        font-family: 'Rumonds', serif !important;
        font-size: clamp(120px, 25vw, 420px);
        font-weight: normal;
        color: #fff;
        line-height: 0.85;
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.9),
                     0 4px 16px rgba(0, 0, 0, 0.8);
        letter-spacing: 0.02em;
        position: relative;
        margin-bottom: 1.5rem;
        animation: fadeInDown 1s ease-out;
    }
    
    .hero-class-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 0;
        width: 250px;
        height: 5px;
        background: linear-gradient(90deg, #fa9a08, rgba(250, 154, 8, 0.6), transparent);
        animation: slideInLeft 1.2s ease-out 0.5s backwards;
        box-shadow: 0 0 20px rgba(250, 154, 8, 0.5);
    }
    
    .hero-billiard-text {
        font-family: 'Hina Mincho', serif !important;
        font-size: clamp(28px, 3.5vw, 56px);
        font-weight: bold;
        color: #fff;
        letter-spacing: 0.15em;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8),
                     0 2px 10px rgba(0, 0, 0, 0.6);
        margin-bottom: 2.5rem;
        animation: fadeInUp 1s ease-out 0.3s backwards;
    }
    
    .hero-description {
        color: #e8e8e8;
        font-size: clamp(1rem, 1.5vw, 1.25rem);
        line-height: 1.8;
        margin-bottom: 3rem;
        max-width: 600px;
        animation: fadeInUp 1s ease-out 0.6s backwards;
    }
    
    .hero-cta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        animation: fadeInUp 1s ease-out 0.9s backwards;
    }
    
    .hero-btn-primary {
        background: linear-gradient(135deg, #fa9a08 0%, #e19e2b 100%);
        color: #000;
        font-size: 1.125rem;
        font-weight: 700;
        padding: 1rem 2.5rem;
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.4s;
        box-shadow: 0 8px 30px rgba(250, 154, 8, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .hero-btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(250, 154, 8, 0.5);
        background: linear-gradient(135deg, #ffb020 0%, #fa9a08 100%);
    }
    
    .hero-btn-secondary {
        background: rgba(250, 154, 8, 0.1);
        border: 2px solid rgba(250, 154, 8, 0.3);
        color: #fa9a08;
        font-size: 1.125rem;
        font-weight: 700;
        padding: 1rem 2.5rem;
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.4s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .hero-btn-secondary:hover {
        background: rgba(250, 154, 8, 0.2);
        border-color: rgba(250, 154, 8, 0.5);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(250, 154, 8, 0.3);
    }
    
    .hero-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 3rem;
    }
    
    .hero-logo-white {
        filter: drop-shadow(0 8px 32px rgba(0, 0, 0, 0.9))
                drop-shadow(0 4px 16px rgba(250, 154, 8, 0.4));
        max-width: clamp(250px, 35vw, 500px);
        width: 100%;
        height: auto;
        animation: fadeInScale 1.2s ease-out 0.5s backwards;
        transition: transform 0.6s ease;
    }
    
    .hero-logo-white:hover {
        transform: scale(1.05);
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes slideInLeft {
        from {
            width: 0;
            opacity: 0;
        }
        to {
            width: 250px;
            opacity: 1;
        }
    }
    
    @media (max-width: 1024px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
            padding: 3rem 2rem;
            min-height: auto;
        }
        
        .hero-left {
            max-width: 100%;
            padding-right: 0;
            margin-bottom: 3rem;
        }
        
        .hero-right {
            padding-left: 0;
        }
        
        .hero-class-title::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .hero-cta {
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .hero-container {
            background-attachment: scroll;
        }
        
        .hero-content {
            padding: 2rem 1rem;
        }
        
        .hero-class-title {
            font-size: 120px;
            margin-bottom: 1rem;
        }
        
        .hero-class-title::after {
            width: 120px;
        }
        
        .hero-billiard-text {
            font-size: 28px;
            margin-bottom: 1.5rem;
        }
        
        .hero-description {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        .hero-cta {
            flex-direction: column;
            gap: 1rem;
        }
        
        .hero-btn-primary,
        .hero-btn-secondary {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 2rem;
            font-size: 1rem;
        }
        
        .hero-logo-white {
            max-width: 220px;
        }
    }
</style>

<div class="hero-background hero-container relative w-full">
    <div class="hero-content">
        <div class="hero-left">
            <div class="hero-text-container">
                <h1 class="hero-class-title">
                    {{ $title }}
                </h1>
                
                @if($subtitle)
                <div class="hero-billiard-text">
                    {{ $subtitle }}
                </div>
                @endif
                
                <p class="hero-description">
                    Pengalaman bermain billiard terbaik dengan fasilitas lengkap dan pelayanan profesional. 
                    Nikmati suasana premium untuk setiap momen bermain Anda.
                </p>
                
                <div class="hero-cta">
                    <a href="{{ route('menu') }}" class="hero-btn-primary">
                        <i class="ri-shopping-cart-line"></i>
                        Pesan Sekarang
                    </a>
                    <a href="#tentang-kami" class="hero-btn-secondary">
                        <i class="ri-information-line"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
        
        <div class="hero-right">
            @if($logoImage)
                <img src="{{ $logoImage }}" alt="CLASS BILLIARD Logo" class="hero-logo-white">
            @else
                <div class="text-white text-center bg-black/70 p-6 md:p-8 rounded-2xl backdrop-blur-sm border-2 border-white/20">
                </div>
            @endif
        </div>
    </div>
</div>
{{-- Hero Section --}}
