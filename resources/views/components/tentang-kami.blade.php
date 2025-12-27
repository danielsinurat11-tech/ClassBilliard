@php
    try {
    $tentangKami = \App\Models\TentangKami::where('is_active', true)->first();
    $bodyText = '';
    if ($tentangKami) {
        if (!empty($tentangKami->subtitle)) {
            $bodyText = trim($tentangKami->subtitle);
        }
        }
    } catch (\Exception $e) {
        $tentangKami = null;
        $bodyText = '';
    }
@endphp
@if($tentangKami)
<style>
    @font-face {
        font-family: 'OpenRing';
        src: url('{{ asset('assets/font/openring/OpenRing-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
    .about-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .about-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(250, 154, 8, 0.8), rgba(250, 154, 8, 0.4), rgba(250, 154, 8, 0.8), transparent);
        animation: shimmer 3s infinite;
    }
    
    .about-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(250, 154, 8, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(250, 154, 8, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .about-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .about-header {
        text-align: center;
        margin-bottom: 5rem;
        position: relative;
    }
    
    .about-title {
        font-family: 'OpenRing', serif;
        font-size: clamp(2.5rem, 7vw, 5rem);
        color: #fff;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        font-weight: 400;
        position: relative;
        display: inline-block;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
    }
    
    .about-title::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -60px;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #fa9a08);
        transform: translateY(-50%);
    }
    
    .about-title::after {
        content: '';
        position: absolute;
        bottom: -0.75rem;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 5px;
        background: linear-gradient(90deg, transparent, #fa9a08 20%, #fa9a08 80%, transparent);
        border-radius: 2px;
        box-shadow: 0 0 20px rgba(250, 154, 8, 0.5);
    }
    
    .about-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2.2vw, 1.375rem);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.9;
        font-weight: 300;
    }
    
    .about-content-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        gap: 4rem;
        margin-bottom: 4rem;
    }
    
    .about-text-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 32px;
        padding: clamp(2.5rem, 5vw, 4.5rem);
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.7),
                    0 0 0 1px rgba(250, 154, 8, 0.08) inset,
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .about-text-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #fa9a08, rgba(250, 154, 8, 0.6), #fa9a08, transparent);
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    .about-text-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(250, 154, 8, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    .about-text-card:hover {
        transform: translateY(-6px);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 35px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 60px rgba(250, 154, 8, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .about-text-card:hover::before {
        opacity: 1;
    }
    
    .about-text-card:hover::after {
        opacity: 1;
    }
    
    .about-body {
        color: #e8e8e8;
        font-size: clamp(1.05rem, 2vw, 1.25rem);
        line-height: 2.2;
        letter-spacing: 0.03em;
        position: relative;
        z-index: 1;
    }
    
    .about-body p {
        margin-bottom: 2rem;
        text-align: justify;
    }
    
    .about-body p:first-child::first-letter {
        font-size: 5em;
        line-height: 0.85;
        float: left;
        padding-right: 0.15em;
        padding-top: 0.1em;
        color: #fa9a08;
        font-weight: bold;
        text-shadow: 0 0 20px rgba(250, 154, 8, 0.5);
    }
    
    .video-wrapper {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 32px;
        padding: 2.5rem;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.7),
                    0 0 0 1px rgba(250, 154, 8, 0.08) inset;
        position: relative;
        overflow: hidden;
    }
    
    .video-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #fa9a08, rgba(250, 154, 8, 0.6), #fa9a08, transparent);
    }
    
    .video-container {
        position: relative;
        aspect-ratio: 16/9;
        border-radius: 20px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.9),
                    inset 0 0 0 2px rgba(250, 154, 8, 0.2);
    }
    
    .video-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    @media (max-width: 768px) {
        .about-container {
            padding: 0 1rem;
        }
        
        .about-header {
            margin-bottom: 3rem;
        }
        
        .about-title::before {
            display: none;
        }
        
        .about-text-card {
            padding: 2rem;
            border-radius: 24px;
        }
        
        .about-body {
            font-size: 1rem;
            line-height: 1.9;
        }
        
        .about-body p:first-child::first-letter {
            font-size: 3.5em;
        }
        
        .video-wrapper {
            padding: 1.5rem;
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
    
    .about-text-card,
    .video-wrapper {
        animation: fadeInUp 1s ease-out backwards;
    }
    
    .video-wrapper {
        animation-delay: 0.2s;
    }
</style>

<section id="tentang-kami" class="about-section py-24 md:py-32">
    <div class="about-container">
        <div class="about-header">
            <h2 class="about-title">{{ strtoupper($tentangKami->title ?? 'Tentang Kami') }}</h2>
            @if($tentangKami->subtitle)
            <p class="about-subtitle">{{ Str::limit($bodyText, 150) }}</p>
            @endif
        </div>

        <div class="about-content-wrapper">
            @if($bodyText)
            <div class="about-text-card">
            <div class="about-body">
                @foreach(preg_split('/\r?\n\r?\n/', $bodyText) as $para)
                        <p>{{ trim($para) }}</p>
                @endforeach
                </div>
            </div>
            @endif

        @if(!empty($tentangKami->video_url))
        @php
            $videoSrc = $tentangKami->video_url;
            $isYouTube = preg_match('/(youtube\.com|youtu\.be)/', $videoSrc);
            if ($isYouTube) {
                $hasQuery = str_contains($videoSrc, '?');
                    $params = 'autoplay=0&mute=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&vq=hd1080';
                $parts = parse_url($videoSrc);
                $id = null;
                if (!empty($parts['path'])) {
                    if (preg_match('#/embed/([^/?]+)#', $parts['path'], $m)) { $id = $m[1]; }
                }
                $videoSrc = $hasQuery ? ($videoSrc . '&' . $params) : ($videoSrc . '?' . $params);
            }
        @endphp
            <div class="video-wrapper">
                <div class="video-container">
                    <iframe id="profileVideoIframe" src="{{ $videoSrc }}" title="Video Profil" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
        @if($isYouTube)
        <script src="https://www.youtube.com/iframe_api"></script>
        <script>
            var ytPlayer;
            function onYouTubeIframeAPIReady(){
                ytPlayer = new YT.Player('profileVideoIframe', {
                    events: {
                        'onReady': function(ev){
                            try {
                                ev.target.setPlaybackQuality('hd1080');
                                } catch(e) {}
                        }
                    }
                });
            }
        </script>
        @endif
        @endif
        </div>
    </div>
</section>
@endif
