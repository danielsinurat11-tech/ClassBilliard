{{-- Event Section --}}
@php
    try {
        $events = \App\Models\Event::where('is_active', true)->orderBy('order')->get();
    } catch (\Exception $e) {
        $events = collect([]);
    }
@endphp
@if($events->count() > 0)
<style>
    .event-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .event-section::before {
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
    
    .event-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .event-header {
        text-align: center;
        margin-bottom: 5rem;
    }
    
    .event-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    
    .event-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 300;
    }
    
    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2.5rem;
    }
    
    .event-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .event-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #fa9a08, rgba(250, 154, 8, 0.6), #fa9a08, transparent);
        transform: scaleX(0);
        transition: transform 0.5s;
        z-index: 1;
    }
    
    .event-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .event-card:hover::before {
        transform: scaleX(1);
    }
    
    .event-image-wrapper {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        background: #1a1a1a;
    }
    
    .event-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .event-card:hover .event-image {
        transform: scale(1.15);
    }
    
    .event-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
    }
    
    .event-content {
        padding: 2.5rem;
    }
    
    .event-date {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fa9a08;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    
    .event-date-icon {
        font-size: 1.25rem;
        filter: drop-shadow(0 0 8px rgba(250, 154, 8, 0.5));
    }
    
    .event-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        line-height: 1.3;
    }
    
    .event-description {
        color: #b0b0b0;
        font-size: 1rem;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .event-link {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        color: #fa9a08;
        font-size: 1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .event-link:hover {
        color: #ffb020;
        gap: 1rem;
    }
    
    .event-link-icon {
        transition: transform 0.4s;
    }
    
    .event-link:hover .event-link-icon {
        transform: translateX(6px);
    }
    
    @media (max-width: 768px) {
        .event-container {
            padding: 0 1rem;
        }
        
        .event-header {
            margin-bottom: 3rem;
        }
        
        .event-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .event-card {
            border-radius: 24px;
        }
        
        .event-content {
            padding: 2rem;
        }
        
        .event-name {
            font-size: 1.5rem;
        }
        
        .event-description {
            font-size: 0.95rem;
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
    
    .event-card {
        animation: fadeInUp 0.8s ease-out backwards;
    }
    
    .event-card:nth-child(1) { animation-delay: 0.1s; }
    .event-card:nth-child(2) { animation-delay: 0.2s; }
    .event-card:nth-child(3) { animation-delay: 0.3s; }
</style>

<section id="event" class="event-section py-24 md:py-32">
    <div class="event-container">
        <div class="event-header">
            <h2 class="event-title">Event yang Diadakan</h2>
            <p class="event-subtitle">Berbagai event dan turnamen yang telah kami selenggarakan</p>
        </div>

        <div class="event-grid">
            @foreach($events as $event)
            <div class="event-card">
                <div class="event-image-wrapper">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/logo.png') }}" alt="{{ $event->event_title }}" class="event-image" loading="lazy">
                    <div class="event-image-overlay"></div>
                </div>
                <div class="event-content">
                    @if($event->event_date)
                    <div class="event-date">
                        <i class="ri-calendar-event-fill event-date-icon"></i>
                        <span>{{ $event->event_date->format('d M Y') }}</span>
                    </div>
                    @endif
                    <h3 class="event-name">{{ $event->event_title }}</h3>
                    @if($event->event_description)
                    <p class="event-description">{{ $event->event_description }}</p>
                    @endif
                    @if($event->link_url)
                    <a href="{{ $event->link_url }}" target="_blank" rel="noopener" class="event-link">
                        Lihat Detail
                        <i class="ri-arrow-right-line event-link-icon"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Event Section --}}
