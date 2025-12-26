{{-- Portfolio & Achievement Section --}}
@php
    try {
        $achievements = \App\Models\PortfolioAchievement::where('type', 'achievement')->where('is_active', true)->orderBy('order')->get();
        $galleries = \App\Models\PortfolioAchievement::where('type', 'gallery')->where('is_active', true)->orderBy('order')->get();
    } catch (\Exception $e) {
        $achievements = collect([]);
        $galleries = collect([]);
    }
@endphp
@if($achievements->count() > 0 || $galleries->count() > 0)
<style>
    .portfolio-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .portfolio-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(250, 154, 8, 0.8), rgba(250, 154, 8, 0.4), rgba(250, 154, 8, 0.8), transparent);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .portfolio-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .portfolio-header {
        text-align: center;
        margin-bottom: 5rem;
    }
    
    .portfolio-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    
    .portfolio-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 300;
    }
    
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 2rem;
        margin-bottom: 5rem;
    }
    
    .achievement-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 24px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .achievement-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #fa9a08, rgba(250, 154, 8, 0.6), #fa9a08, transparent);
        transform: scaleX(0);
        transition: transform 0.5s;
    }
    
    .achievement-card:hover {
        transform: translateY(-10px) scale(1.05);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .achievement-card:hover::before {
        transform: scaleX(1);
    }
    
    .achievement-icon {
        font-size: 4rem;
        color: #fa9a08;
        margin-bottom: 1.5rem;
        transition: all 0.5s;
        filter: drop-shadow(0 0 15px rgba(250, 154, 8, 0.5));
    }
    
    .achievement-card:hover .achievement-icon {
        transform: scale(1.15) rotate(10deg);
        filter: drop-shadow(0 0 25px rgba(250, 154, 8, 0.8));
    }
    
    .achievement-number {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.75rem;
        line-height: 1;
    }
    
    .achievement-label {
        color: #b0b0b0;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .gallery-section {
        margin-top: 5rem;
    }
    
    .gallery-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-align: center;
        margin-bottom: 3rem;
        letter-spacing: -0.02em;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 24px;
        overflow: hidden;
        background: #1a1a1a;
        border: 2px solid rgba(250, 154, 8, 0.15);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .gallery-item:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .gallery-item:hover img {
        transform: scale(1.15);
    }
    
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .portfolio-container {
            padding: 0 1rem;
        }
        
        .portfolio-header {
            margin-bottom: 3rem;
        }
        
        .achievements-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .achievement-card {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        
        .achievement-icon {
            font-size: 2.5rem;
        }
        
        .achievement-number {
            font-size: 2.5rem;
        }
        
        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .achievement-card,
    .gallery-item {
        animation: fadeInUp 0.8s ease-out backwards;
    }
</style>

<section id="portfolio" class="portfolio-section py-24 md:py-32">
    <div class="portfolio-container">
        <div class="portfolio-header">
            <h2 class="portfolio-title">Portfolio & Achievement</h2>
            <p class="portfolio-subtitle">Prestasi dan pencapaian yang telah kami raih</p>
        </div>

        @if($achievements->count() > 0)
        <div class="achievements-grid">
            @foreach($achievements as $achievement)
            <div class="achievement-card">
                @if($achievement->icon)
                <div class="achievement-icon">
                    <i class="{{ $achievement->icon }}"></i>
                </div>
                @endif
                @if($achievement->number)
                <div class="achievement-number">{{ $achievement->number }}</div>
                @endif
                @if($achievement->label)
                <div class="achievement-label">{{ $achievement->label }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if($galleries->count() > 0)
        <div class="gallery-section">
            <h3 class="gallery-title">Galeri Kegiatan</h3>
            <div class="gallery-grid">
                @foreach($galleries as $gallery)
                <div class="gallery-item">
                    <img src="{{ $gallery->image ? asset('storage/' . $gallery->image) : asset('assets/logo.png') }}" alt="Portfolio" loading="lazy">
                    <div class="gallery-overlay"></div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif
{{-- Portfolio & Achievement Section --}}
