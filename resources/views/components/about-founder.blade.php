@php
    try {
        $aboutFounder = \App\Models\AboutFounder::where('is_active', true)->first();
        $hero = \App\Models\HeroSection::where('is_active', true)->first();
        if (!$hero) { $hero = \App\Models\HeroSection::first(); }
        $logoImage = ($hero && $hero->logo_image) ? asset('storage/' . $hero->logo_image) : null;
        $roleText = null;
        $subtitleRemainder = '';
        if ($aboutFounder && !empty($aboutFounder->subtitle)) {
            $subtitle = trim($aboutFounder->subtitle);
            $lines = preg_split('/\r?\n/', $subtitle);
            if (count($lines) > 1) {
                $roleText = $lines[0];
                $subtitleRemainder = implode("\n", array_slice($lines, 1));
            } else {
                $sentenceSplit = preg_split('/([.!?])\s+/', $subtitle, 2, PREG_SPLIT_DELIM_CAPTURE);
                if (count($sentenceSplit) >= 3) {
                    $roleText = $sentenceSplit[0] . $sentenceSplit[1];
                    $subtitleRemainder = $sentenceSplit[2];
                } else {
                    $roleText = mb_strimwidth($subtitle, 0, 64, '');
                    $subtitleRemainder = mb_substr($subtitle, mb_strlen($roleText));
                }
            }
        }
    } catch (\Exception $e) {
        $aboutFounder = null;
        $hero = null;
        $logoImage = null;
        $roleText = null;
        $subtitleRemainder = '';
    }
@endphp
@if($aboutFounder)
<style>
    @font-face {
        font-family: 'OpenRing';
        src: url('{{ asset('assets/font/openring/OpenRing-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
    .founder-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
        min-height: 85vh;
        background-image: url('{{ asset('assets/bg-tentangpemilik.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    
    .founder-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(250, 154, 8, 0.8), rgba(250, 154, 8, 0.4), rgba(250, 154, 8, 0.8), transparent);
        animation: shimmer 3s infinite;
        z-index: 1;
    }
    
    .founder-section::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0.8) 100%);
        z-index: 0;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .founder-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .founder-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5rem;
        align-items: center;
        min-height: 85vh;
        padding: 5rem 0;
    }
    
    .founder-info {
        position: relative;
    }
    
    .founder-title {
        font-family: 'OpenRing', serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        color: #fff;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        font-weight: 400;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
    }
    
    .title-accent {
        width: 100px;
        height: 5px;
        background: linear-gradient(90deg, transparent, #fa9a08 20%, #fa9a08 80%, transparent);
        border-radius: 2px;
        margin-bottom: 2rem;
        box-shadow: 0 0 20px rgba(250, 154, 8, 0.5);
    }
    
    .founder-role {
        font-family: 'OpenRing', serif;
        color: #fa9a08;
        font-size: clamp(1rem, 2vw, 1.25rem);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 2rem;
        font-weight: 600;
        text-shadow: 0 0 15px rgba(250, 154, 8, 0.5);
    }
    
    .founder-description {
        font-family: 'OpenRing', serif;
        color: #e8e8e8;
        font-size: clamp(1.05rem, 1.8vw, 1.25rem);
        line-height: 2.2;
        letter-spacing: 0.04em;
    }
    
    .founder-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .founder-logo {
        position: absolute;
        top: 8%;
        right: 0;
        width: clamp(140px, 18vw, 250px);
        height: auto;
        filter: drop-shadow(0 8px 32px rgba(0, 0, 0, 0.9))
                drop-shadow(0 4px 16px rgba(250, 154, 8, 0.4));
        z-index: 2;
        opacity: 0.95;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .founder-name-overlay {
        position: absolute;
        top: 32%;
        right: 0;
        transform: translateX(25%);
        color: #fff;
        font-family: 'OpenRing', serif;
        font-size: clamp(1.75rem, 4vw, 3rem);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        z-index: 2;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.9),
                     0 0 30px rgba(250, 154, 8, 0.3);
    }
    
    .founder-photo-wrapper {
        position: relative;
        width: 100%;
        max-width: 550px;
        aspect-ratio: 4/5;
        border-radius: 28px;
        overflow: hidden;
        border: 3px solid rgba(250, 154, 8, 0.25);
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.9),
                    0 0 0 1px rgba(250, 154, 8, 0.1) inset,
                    0 0 60px rgba(250, 154, 8, 0.2);
        transition: all 0.6s;
    }
    
    .founder-photo-wrapper:hover {
        border-color: rgba(250, 154, 8, 0.5);
        box-shadow: 0 35px 100px rgba(0, 0, 0, 0.95),
                    0 0 0 1px rgba(250, 154, 8, 0.2) inset,
                    0 0 80px rgba(250, 154, 8, 0.35);
        transform: scale(1.02);
    }
    
    .founder-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s;
    }
    
    .founder-photo-wrapper:hover .founder-photo {
        transform: scale(1.08);
    }
    
    @media (max-width: 1024px) {
        .founder-content {
            grid-template-columns: 1fr;
            gap: 4rem;
            padding: 4rem 0;
        }
        
        .founder-visual {
            order: -1;
        }
        
        .founder-logo {
            top: 5%;
            right: 5%;
        }
        
        .founder-name-overlay {
            top: 18%;
            right: 5%;
            transform: translateX(0);
        }
        
        .founder-photo-wrapper {
            max-width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .founder-container {
            padding: 0 1rem;
        }
        
        .founder-content {
            padding: 3rem 0;
            gap: 3rem;
        }
        
        .founder-logo {
            width: 120px;
            top: 3%;
            right: 3%;
        }
        
        .founder-name-overlay {
            font-size: 1.5rem;
            top: 12%;
            right: 3%;
        }
        
        .founder-photo-wrapper {
            border-radius: 24px;
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
    
    .founder-info,
    .founder-photo-wrapper {
        animation: fadeInUp 1s ease-out backwards;
    }
    
    .founder-photo-wrapper {
        animation-delay: 0.3s;
    }
</style>

<section id="about-founder" class="founder-section py-24 md:py-32">
    <div class="founder-container">
        <div class="founder-content">
            <div class="founder-info">
                <h2 class="founder-title">{{ strtoupper($aboutFounder->title ?? 'Tentang Pemilik') }}</h2>
                <div class="title-accent"></div>
                <div class="founder-role">{{ $roleText ?? 'Pemilik Class' }}</div>
                <div class="founder-description">
                    @if(!empty($aboutFounder->description))
                        {!! nl2br(e($aboutFounder->description)) !!}
                    @endif
                    @if(!empty($subtitleRemainder))
                        {!! nl2br(e($subtitleRemainder)) !!}
                    @endif
                </div>
            </div>
            
            <div class="founder-visual">
                @if($logoImage)
                <img src="{{ $logoImage }}" alt="Logo" class="founder-logo">
                @endif
                @if(!empty($aboutFounder?->name))
                <div class="founder-name-overlay">{{ $aboutFounder->name }}</div>
                @endif
                @if($aboutFounder->photo)
                <div class="founder-photo-wrapper">
                    <img src="{{ asset('storage/' . $aboutFounder->photo) }}" alt="{{ $aboutFounder->name }}" class="founder-photo" loading="lazy">
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
