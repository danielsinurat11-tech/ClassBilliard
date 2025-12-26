{{-- Tim Kami Section --}}
@php
    try {
        $timKami = \App\Models\TimKami::where('is_active', true)->orderBy('order')->get();
    } catch (\Exception $e) {
        $timKami = collect([]);
    }
@endphp
@if($timKami->count() > 0)
<style>
    .team-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .team-section::before {
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
    
    .team-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .team-header {
        text-align: center;
        margin-bottom: 5rem;
    }
    
    .team-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    
    .team-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 300;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.5rem;
    }
    
    .team-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 28px;
        padding: 3rem 2.5rem;
        text-align: center;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .team-card::before {
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
    
    .team-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .team-card:hover::before {
        transform: scaleX(1);
    }
    
    .team-photo-wrapper {
        width: 160px;
        height: 160px;
        margin: 0 auto 2rem;
        position: relative;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(250, 154, 8, 0.3) 0%, rgba(250, 154, 8, 0.15) 100%);
        padding: 5px;
        transition: all 0.5s;
    }
    
    .team-card:hover .team-photo-wrapper {
        transform: scale(1.1);
        box-shadow: 0 0 40px rgba(250, 154, 8, 0.5);
    }
    
    .team-photo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(250, 154, 8, 0.4);
        transition: border-color 0.5s;
    }
    
    .team-card:hover .team-photo {
        border-color: rgba(250, 154, 8, 0.7);
    }
    
    .team-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    
    .team-position {
        color: #fa9a08;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    
    .team-social {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    
    .team-social-link {
        width: 48px;
        height: 48px;
        background: rgba(250, 154, 8, 0.1);
        border: 2px solid rgba(250, 154, 8, 0.25);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #b0b0b0;
        font-size: 1.25rem;
        transition: all 0.4s;
        text-decoration: none;
    }
    
    .team-social-link:hover {
        background: rgba(250, 154, 8, 0.25);
        border-color: rgba(250, 154, 8, 0.5);
        color: #fa9a08;
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 10px 30px rgba(250, 154, 8, 0.3);
    }
    
    @media (max-width: 768px) {
        .team-container {
            padding: 0 1rem;
        }
        
        .team-header {
            margin-bottom: 3rem;
        }
        
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .team-card {
            padding: 2.5rem 2rem;
            border-radius: 24px;
        }
        
        .team-photo-wrapper {
            width: 140px;
            height: 140px;
            margin-bottom: 1.5rem;
        }
        
        .team-name {
            font-size: 1.25rem;
        }
        
        .team-position {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .team-social-link {
            width: 42px;
            height: 42px;
            font-size: 1.125rem;
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
    
    .team-card {
        animation: fadeInUp 0.8s ease-out backwards;
    }
    
    .team-card:nth-child(1) { animation-delay: 0.1s; }
    .team-card:nth-child(2) { animation-delay: 0.2s; }
    .team-card:nth-child(3) { animation-delay: 0.3s; }
    .team-card:nth-child(4) { animation-delay: 0.4s; }
    .team-card:nth-child(5) { animation-delay: 0.5s; }
    .team-card:nth-child(6) { animation-delay: 0.6s; }
</style>

<section id="tim-kami" class="team-section py-24 md:py-32">
    <div class="team-container">
        <div class="team-header">
            <h2 class="team-title">Tim Kami</h2>
            <p class="team-subtitle">Tim profesional yang siap melayani Anda dengan sepenuh hati</p>
        </div>

        <div class="team-grid">
            @foreach($timKami as $member)
            <div class="team-card">
                <div class="team-photo-wrapper">
                    <img src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/logo.png') }}" alt="{{ $member->name }}" class="team-photo" loading="lazy">
                </div>
                <h3 class="team-name">{{ $member->name }}</h3>
                <p class="team-position">{{ $member->position }}</p>
                <div class="team-social">
                    @if($member->facebook_url)
                    <a href="{{ $member->facebook_url }}" target="_blank" rel="noopener" class="team-social-link">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    @endif
                    @if($member->instagram_url)
                    <a href="{{ $member->instagram_url }}" target="_blank" rel="noopener" class="team-social-link">
                        <i class="ri-instagram-fill"></i>
                    </a>
                    @endif
                    @if($member->linkedin_url)
                    <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener" class="team-social-link">
                        <i class="ri-linkedin-fill"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Tim Kami Section --}}
