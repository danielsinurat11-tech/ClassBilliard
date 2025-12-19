@php
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
@endphp
<style>
    @font-face {
        font-family: 'OpenRing';
        src: url('{{ asset('assets/font/openring/OpenRing-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    .about-founder-section { font-family: 'OpenRing', serif; position: relative; min-height: 87vh; background: url('{{ asset('assets/bg-tentangpemilik.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; }
    .founder-title { position: absolute; z-index: 3; top: 0%; font-family: 'OpenRing', serif; font-size: clamp(2.25rem, 4.8vw, 3.25rem); color: #fff; letter-spacing: 0.08em; text-transform: uppercase; }
    .title-accent { width: clamp(64px, 8vw, 96px); height: 4px; background: #D61F1F; border-radius: 2px; margin-bottom: clamp(10px, 2vw, 16px); }
    .founder-name { font-family: 'OpenRing', serif; color: #e5e7eb; font-size: clamp(1.1rem, 2.2vw, 1.375rem); letter-spacing: 0.06em; margin-bottom: 0.5rem; }
    .founder-name-overlay { position: absolute; z-index: 3; top: 35%; left: 62%; transform: translate(-50%, -50%); color: #fff; font-family: 'OpenRing', serif; font-size: clamp(1.75rem, 3.8vw, 2.5rem); letter-spacing: 0.08em; }
    @media (max-width: 768px) { .founder-name-overlay { top: 32%; left: 50%; font-size: clamp(1.25rem, 5vw, 1.75rem); } }
    .founder-logo-overlay { position: absolute; z-index: 3; top: 10%; right: 4%; width: clamp(120px, 14vw, 220px); height: auto; filter: drop-shadow(0 4px 25px rgba(0,0,0,0.6)); }
    .founder-role-inline { font-family: 'OpenRing', serif; color: #e5e7eb; font-size: clamp(0.9rem, 1.8vw, 1rem); letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 0.75rem; }
    .founder-desc { position: absolute; z-index: 3; top: 34%; left: 62%; font-family: 'OpenRing', serif; color: #e5e7eb; font-size: clamp(1rem, 2vw, 1.125rem); line-height: 1.9; letter-spacing: 0.06em; }
    .founder-info { display: flex; flex-direction: column; justify-content: flex-start; }
    .founder-card { position: relative; }
    .founder-photo-wrap { position: relative; width: clamp(420px, 52vw, 780px); height: clamp(320px, 50vw, 660px); }
    .founder-photo { position: absolute; right: 0; bottom: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 0; z-index: 1; }
    @media (max-width: 768px) { .founder-photo-wrap { width: 88vw; height: 92vw; } }
</style>
<section class="about-founder-section py-16">
    <div class="max-w-7xl mx-auto px-4">
        @if($logoImage)
        <img src="{{ $logoImage }}" alt="Logo" class="founder-logo-overlay">
        @endif
        @if(!empty($aboutFounder?->name))
        <div class="founder-name-overlay">{{ $aboutFounder->name }}</div>
        @endif
        <div class="grid md:grid-cols-2 gap-10 items-start">
            <div class="founder-info">
                <h2 class="founder-title mb-2">{{ strtoupper($aboutFounder->title ?? 'Tentang Pemilik') }}</h2>
                <div class="title-accent"></div>
                <div class="founder-role-inline">{{ $roleText ?? 'Pemilik class' }}</div>
                <div class="founder-desc">
                    @if(!empty($aboutFounder->description))
                        {!! nl2br(e($aboutFounder->description)) !!}
                    @endif
                    @if(!empty($subtitleRemainder))
                        {!! nl2br(e($subtitleRemainder)) !!}
                    @endif
                </div>
            </div>
            
            
        </div>
    </div>
</section>
