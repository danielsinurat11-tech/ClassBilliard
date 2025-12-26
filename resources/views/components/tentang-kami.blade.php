@php
    $tentangKami = \App\Models\TentangKami::where('is_active', true)->first();
    $bodyText = '';
    if ($tentangKami) {
        if (!empty($tentangKami->subtitle)) {
            $bodyText = trim($tentangKami->subtitle);
        }
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
    .about-title {
        font-family: 'OpenRing', serif;
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        color: #fff;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .about-body {
        color: #e5e7eb;
        font-size: clamp(1rem, 2.1vw, 1.125rem);
        line-height: 1.9;
        letter-spacing: 0.04em;
        margin-left: clamp(1rem, 5vw, 4rem);
    }
    .about-body p:first-letter {
        font-size: 1.8em;
        line-height: 1;
        padding-right: 0.08em;
    }
    .about-panel {
        background: #000;
        border-radius: 1.25rem;
        padding: clamp(1.25rem, 3.2vw, 2rem);
    }
    .about-section { background: #000; }
    .about-container {
        max-width: 72rem;
        margin-left: clamp(0.5rem, 3vw, 2rem);
        margin-right: auto;
        padding-left: clamp(0rem, 1vw, 0.5rem);
        padding-right: 1rem;
    }

    /* Responsive for Mobile and Tablet */
    @media (max-width: 768px) {
        .about-container {
            margin-left: 0.5rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .about-body {
            margin-left: 0.5rem;
            font-size: 0.95rem;
        }
        .about-panel {
            padding: 1rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .about-container {
            margin-left: 1rem;
            padding-left: 0.75rem;
            padding-right: 1rem;
        }
        .about-body {
            margin-left: 1.5rem;
        }
    }
    .visimisi-section {
        position: relative;
        background: transparent;
        border-radius: 0;
        overflow: visible;
    }
    .visimisi-overlay { display: none; }
    .skew-card {
        background: transparent;
        background-image: url('{{ asset('assets/bg-visimisi.png') }}');
        background-size: 300% 100%;
        background-position: 50% center;
        background-repeat: no-repeat;
        border-radius: 1rem;
        transform: skew(-12deg);
        padding: 1.95rem;
        height: clamp(300px, 42vw, 540px);
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0,0,0,0.35);
        border: none;
    }
    .skew-card.card-left { background-position: 0% center; }
    .skew-card.card-center { background-position: 50% center; }
    .skew-card.card-right { background-position: 100% center; }
    .skew-content {
        transform: skew(12deg);
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: clamp(1rem, 2.5vw, 1.75rem) clamp(1rem, 3vw, 2rem);
        align-items: center;
    }
    .card-title {
        font-family: 'OpenRing', serif;
        color: #fff;
        font-size: clamp(1.5rem, 3vw, 2rem);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        text-align: center;
    }
    .card-body {
        font-family: 'OpenRing', sans-serif;
        color: #e5e7eb;
        font-size: clamp(0.95rem, 1.2vw, 1.05rem);
        line-height: 1.6;
        letter-spacing: 0.03em;
        flex: 1 1 auto;
        position: relative;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 10;
        text-align: center;
    }
    .card-body::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 2.25rem;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(176,20,20,0.45) 100%);
        pointer-events: none;
    }
    
    .skew-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(176, 20, 20, 0.45);
        z-index: 0;
    }
    @media (max-width: 768px) {
        .about-title { font-size: 2rem; }
        .about-body { font-size: 1rem; margin-left: 0; }
        .skew-card { transform: skew(-10deg); padding: 1.25rem; height: clamp(240px, 55vw, 420px); }
        .skew-content { transform: skew(10deg); }
        .card-body { -webkit-line-clamp: 7; }
    }
</style>
<section class="py-16 about-section">
    <div class="about-container">
        <div class="about-panel mb-12">
            <h2 class="about-title mb-6">{{ strtoupper($tentangKami->title ?? 'Tentang Kami') }}</h2>
            @if($bodyText)
            <div class="about-body">
                @foreach(preg_split('/\r?\n\r?\n/', $bodyText) as $para)
                    <p class="mb-4">{{ $para }}</p>
                @endforeach
            </div>
            @endif
        </div>

        

        @if(!empty($tentangKami->video_url))
        @php
            $videoSrc = $tentangKami->video_url;
            $isYouTube = preg_match('/(youtube\.com|youtu\.be)/', $videoSrc);
            if ($isYouTube) {
                $hasQuery = str_contains($videoSrc, '?');
                $params = 'autoplay=1&mute=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&vq=hd1080';
                $parts = parse_url($videoSrc);
                $id = null;
                if (!empty($parts['path'])) {
                    if (preg_match('#/embed/([^/?]+)#', $parts['path'], $m)) { $id = $m[1]; }
                }
                if ($id) { $params .= '&loop=1&playlist=' . $id; }
                $videoSrc = $hasQuery ? ($videoSrc . '&' . $params) : ($videoSrc . '?' . $params);
            }
        @endphp
        <div class="bg-black p-8 rounded-2xl">
            <div class="relative aspect-video bg-black rounded-lg overflow-hidden">
                <iframe id="profileVideoIframe" class="w-full h-full" src="{{ $videoSrc }}" title="Video Profil" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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
                                ev.target.unMute();
                                ev.target.setVolume(100);
                                ev.target.playVideo();
                            } catch(e) {}
                        },
                        'onStateChange': function(ev){
                            if (ev.data === YT.PlayerState.ENDED) {
                                try {
                                    ev.target.seekTo(0, true);
                                    ev.target.playVideo();
                                } catch(e) {}
                            }
                        }
                    }
                });
            }
        </script>
        @endif
        @endif
    </div>
</section>
@endif
    
