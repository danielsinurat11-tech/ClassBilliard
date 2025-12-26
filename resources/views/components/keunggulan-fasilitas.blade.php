{{-- Keunggulan Fasilitas Section --}}
@php
    try {
        $fasilitas = \App\Models\KeunggulanFasilitas::where('is_active', true)->orderBy('order')->get();
    } catch (\Exception $e) {
        $fasilitas = collect([]);
    }
@endphp
@if($fasilitas->count() > 0)
<style>
    .facilities-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .facilities-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(250, 154, 8, 0.8), rgba(250, 154, 8, 0.4), rgba(250, 154, 8, 0.8), transparent);
        animation: shimmer 3s infinite;
    }
    
    .facilities-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 30%, rgba(250, 154, 8, 0.03) 0%, transparent 50%);
        pointer-events: none;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .facilities-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .facilities-header {
        text-align: center;
        margin-bottom: 5rem;
    }
    
    .facilities-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }
    
    .facilities-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 300;
    }
    
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2.5rem;
    }
    
    .facility-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 28px;
        padding: 3rem 2.5rem;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .facility-card::before {
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
    
    .facility-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(250, 154, 8, 0.15) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    .facility-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .facility-card:hover::before {
        transform: scaleX(1);
    }
    
    .facility-card:hover::after {
        opacity: 1;
    }
    
    .facility-icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(250, 154, 8, 0.15) 0%, rgba(250, 154, 8, 0.08) 100%);
        border: 2px solid rgba(250, 154, 8, 0.25);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        transition: all 0.5s;
        position: relative;
        z-index: 1;
    }
    
    .facility-card:hover .facility-icon-wrapper {
        background: linear-gradient(135deg, rgba(250, 154, 8, 0.3) 0%, rgba(250, 154, 8, 0.15) 100%);
        border-color: rgba(250, 154, 8, 0.5);
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 10px 30px rgba(250, 154, 8, 0.3);
    }
    
    .facility-icon {
        font-size: 2.5rem;
        color: #fa9a08;
        transition: transform 0.5s;
        filter: drop-shadow(0 0 10px rgba(250, 154, 8, 0.5));
    }
    
    .facility-card:hover .facility-icon {
        transform: scale(1.2) rotate(-5deg);
    }
    
    .facility-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
    }
    
    .facility-description {
        color: #b0b0b0;
        font-size: 1rem;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .facilities-container {
            padding: 0 1rem;
        }
        
        .facilities-header {
            margin-bottom: 3rem;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .facility-card {
            padding: 2.5rem 2rem;
            border-radius: 24px;
        }
        
        .facility-icon-wrapper {
            width: 70px;
            height: 70px;
            margin-bottom: 1.5rem;
        }
        
        .facility-icon {
            font-size: 2rem;
        }
        
        .facility-name {
            font-size: 1.25rem;
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
    
    .facility-card {
        animation: fadeInUp 0.8s ease-out backwards;
    }
    
    .facility-card:nth-child(1) { animation-delay: 0.1s; }
    .facility-card:nth-child(2) { animation-delay: 0.2s; }
    .facility-card:nth-child(3) { animation-delay: 0.3s; }
    .facility-card:nth-child(4) { animation-delay: 0.4s; }
    .facility-card:nth-child(5) { animation-delay: 0.5s; }
    .facility-card:nth-child(6) { animation-delay: 0.6s; }
</style>

<section id="fasilitas" class="facilities-section py-24 md:py-32">
    <div class="facilities-container">
        <div class="facilities-header">
            <h2 class="facilities-title">Keunggulan & Fasilitas Kami</h2>
            <p class="facilities-subtitle">Fasilitas lengkap dan modern untuk pengalaman bermain yang maksimal</p>
        </div>

        <div class="facilities-grid">
            @foreach($fasilitas as $item)
            <div class="facility-card">
                @if($item->icon)
                <div class="facility-icon-wrapper">
                    <i class="{{ $item->icon }} facility-icon"></i>
                </div>
                @endif
                <h3 class="facility-name">{{ $item->name }}</h3>
                @if($item->description)
                <p class="facility-description">{{ $item->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Keunggulan Fasilitas Section --}}
