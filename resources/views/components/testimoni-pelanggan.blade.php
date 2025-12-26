{{-- Testimoni Pelanggan Section --}}
@php
    try {
        $testimonis = \App\Models\TestimoniPelanggan::where('is_active', true)->orderBy('order')->get();
    } catch (\Exception $e) {
        $testimonis = collect([]);
    }
@endphp
@if($testimonis->count() > 0)
<style>
    .testimonial-section {
        background: linear-gradient(180deg, #000000 0%, #0f0f0f 25%, #1a1a1a 50%, #0f0f0f 75%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-section::before {
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
    
    .testimonial-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 4rem);
        position: relative;
        z-index: 1;
    }
    
    .testimonial-header {
        text-align: center;
        margin-bottom: 5rem;
    }
    
    .testimonial-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #fa9a08 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    
    .testimonial-subtitle {
        color: #b0b0b0;
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 300;
    }
    
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2.5rem;
    }
    
    .testimonial-card {
        background: linear-gradient(145deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(250, 154, 8, 0.15);
        border-radius: 28px;
        padding: 3rem 2.5rem;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-card::before {
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
    
    .testimonial-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(250, 154, 8, 0.4);
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.8),
                    0 0 0 1px rgba(250, 154, 8, 0.15) inset,
                    0 0 80px rgba(250, 154, 8, 0.25);
    }
    
    .testimonial-card:hover::before {
        transform: scaleX(1);
    }
    
    .testimonial-rating {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .testimonial-star {
        color: #fa9a08;
        font-size: 1.5rem;
        filter: drop-shadow(0 0 8px rgba(250, 154, 8, 0.5));
        transition: transform 0.3s;
    }
    
    .testimonial-card:hover .testimonial-star {
        transform: scale(1.1);
    }
    
    .testimonial-text {
        color: #e8e8e8;
        font-size: 1.125rem;
        line-height: 1.9;
        margin-bottom: 2.5rem;
        font-style: italic;
        position: relative;
        padding-left: 2rem;
    }
    
    .testimonial-text::before {
        content: '"';
        position: absolute;
        left: 0;
        top: -0.5rem;
        font-size: 4rem;
        color: rgba(250, 154, 8, 0.4);
        font-family: serif;
        line-height: 1;
        font-weight: bold;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    
    .testimonial-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(250, 154, 8, 0.4);
        transition: all 0.5s;
    }
    
    .testimonial-card:hover .testimonial-avatar {
        border-color: rgba(250, 154, 8, 0.7);
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(250, 154, 8, 0.4);
    }
    
    .testimonial-author-info {
        flex: 1;
    }
    
    .testimonial-author-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .testimonial-author-role {
        color: #b0b0b0;
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .testimonial-container {
            padding: 0 1rem;
        }
        
        .testimonial-header {
            margin-bottom: 3rem;
        }
        
        .testimonial-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .testimonial-card {
            padding: 2.5rem 2rem;
            border-radius: 24px;
        }
        
        .testimonial-text {
            font-size: 1rem;
            padding-left: 1.5rem;
        }
        
        .testimonial-text::before {
            font-size: 3rem;
        }
        
        .testimonial-avatar {
            width: 56px;
            height: 56px;
        }
        
        .testimonial-author-name {
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
    
    .testimonial-card {
        animation: fadeInUp 0.8s ease-out backwards;
    }
    
    .testimonial-card:nth-child(1) { animation-delay: 0.1s; }
    .testimonial-card:nth-child(2) { animation-delay: 0.2s; }
    .testimonial-card:nth-child(3) { animation-delay: 0.3s; }
</style>

<section id="testimoni" class="testimonial-section py-24 md:py-32">
    <div class="testimonial-container">
        <div class="testimonial-header">
            <h2 class="testimonial-title">Testimoni Pelanggan</h2>
            <p class="testimonial-subtitle">Apa kata pelanggan tentang Class Billiard</p>
        </div>

        <div class="testimonial-grid">
            @foreach($testimonis as $testimoni)
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    @for($i = 0; $i < $testimoni->rating; $i++)
                    <i class="ri-star-fill testimonial-star"></i>
                    @endfor
                </div>
                @if($testimoni->testimonial)
                <p class="testimonial-text">{{ $testimoni->testimonial }}</p>
                @endif
                <div class="testimonial-author">
                    <img src="{{ $testimoni->photo ? asset('storage/' . $testimoni->photo) : asset('assets/logo.png') }}" alt="{{ $testimoni->customer_name }}" class="testimonial-avatar" loading="lazy">
                    <div class="testimonial-author-info">
                        <h4 class="testimonial-author-name">{{ $testimoni->customer_name }}</h4>
                        <p class="testimonial-author-role">{{ $testimoni->customer_role }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Testimoni Pelanggan Section --}}
