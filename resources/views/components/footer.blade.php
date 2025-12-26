{{-- Footer Section --}}
@php
    try {
        $footer = \App\Models\Footer::where('is_active', true)->first();
    } catch (\Exception $e) {
        $footer = null;
    }
@endphp
@if($footer)
<style>
    .footer-section {
        background: linear-gradient(180deg, #000000 0%, #0a0a0a 50%, #000000 100%);
        border-top: 2px solid rgba(250, 154, 8, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .footer-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(250, 154, 8, 0.8), rgba(250, 154, 8, 0.4), rgba(250, 154, 8, 0.8), transparent);
    }
    
    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 4rem clamp(1.5rem, 5vw, 4rem) 2rem;
        position: relative;
        z-index: 1;
    }
    
    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .footer-column h3 {
        font-size: 1.375rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1.5rem;
        letter-spacing: -0.01em;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .footer-column h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #fa9a08, transparent);
    }
    
    .footer-about-text {
        color: #b0b0b0;
        font-size: 0.95rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }
    
    .footer-social {
        display: flex;
        gap: 0.75rem;
    }
    
    .footer-social-link {
        width: 44px;
        height: 44px;
        background: rgba(250, 154, 8, 0.1);
        border: 2px solid rgba(250, 154, 8, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fa9a08;
        font-size: 1.25rem;
        transition: all 0.4s;
        text-decoration: none;
    }
    
    .footer-social-link:hover {
        background: rgba(250, 154, 8, 0.2);
        border-color: rgba(250, 154, 8, 0.4);
        color: #fff;
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 10px 30px rgba(250, 154, 8, 0.3);
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 0.75rem;
    }
    
    .footer-links a {
        color: #b0b0b0;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }
    
    .footer-links a:hover {
        color: #fa9a08;
        transform: translateX(4px);
    }
    
    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.25rem;
        color: #b0b0b0;
        font-size: 0.95rem;
        line-height: 1.7;
    }
    
    .footer-contact i {
        color: #fa9a08;
        font-size: 1.125rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }
    
    .footer-contact a {
        color: #b0b0b0;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .footer-contact a:hover {
        color: #fa9a08;
    }
    
    .footer-hours {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }
    
    .footer-hours li {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(250, 154, 8, 0.1);
        color: #b0b0b0;
        font-size: 0.95rem;
    }
    
    .footer-hours li:last-child {
        border-bottom: none;
    }
    
    .footer-hours .hours-time {
        color: #fa9a08;
        font-weight: 600;
    }
    
    .footer-maps-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #fa9a08 0%, #e19e2b 100%);
        color: #000;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s;
        box-shadow: 0 4px 20px rgba(250, 154, 8, 0.3);
    }
    
    .footer-maps-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(250, 154, 8, 0.4);
        background: linear-gradient(135deg, #ffb020 0%, #fa9a08 100%);
    }
    
    .footer-copyright {
        border-top: 1px solid rgba(250, 154, 8, 0.2);
        padding-top: 2rem;
        text-align: center;
        color: #808080;
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .footer-container {
            padding: 3rem 1rem 1.5rem;
        }
        
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        
        .footer-social-link {
            width: 40px;
            height: 40px;
            font-size: 1.125rem;
        }
    }
</style>

<footer class="footer-section">
    <div class="footer-container">
        <div class="footer-grid">
            {{-- About --}}
            <div class="footer-column">
                <h3>Tentang Kami</h3>
                @if($footer->about_text)
                <p class="footer-about-text">
                    {{ $footer->about_text }}
                </p>
                @endif
                <div class="footer-social">
                    @if($footer->facebook_url)
                    <a href="{{ $footer->facebook_url }}" target="_blank" rel="noopener" class="footer-social-link">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    @endif
                    @if($footer->instagram_url)
                    <a href="{{ $footer->instagram_url }}" target="_blank" rel="noopener" class="footer-social-link">
                        <i class="ri-instagram-fill"></i>
                    </a>
                    @endif
                    @if($footer->twitter_url)
                    <a href="{{ $footer->twitter_url }}" target="_blank" rel="noopener" class="footer-social-link">
                        <i class="ri-twitter-x-fill"></i>
                    </a>
                    @endif
                    @if($footer->youtube_url)
                    <a href="{{ $footer->youtube_url }}" target="_blank" rel="noopener" class="footer-social-link">
                        <i class="ri-youtube-fill"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="footer-column">
                <h3>Tautan Cepat</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('menu') }}">Menu</a></li>
                    <li><a href="#tentang-kami">Tentang Kami</a></li>
                    <li><a href="#fasilitas">Fasilitas</a></li>
                    <li><a href="#event">Event</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="footer-column">
                <h3>Kontak</h3>
                <ul class="footer-contact">
                    @if($footer->address)
                    <li>
                        <i class="ri-map-pin-fill"></i>
                        <span>{!! nl2br(e($footer->address)) !!}</span>
                    </li>
                    @endif
                    @if($footer->phone)
                    <li>
                        <i class="ri-phone-fill"></i>
                        <a href="tel:{{ $footer->phone }}">{{ $footer->phone }}</a>
                    </li>
                    @endif
                    @if($footer->email)
                    <li>
                        <i class="ri-mail-fill"></i>
                        <a href="mailto:{{ $footer->email }}">{{ $footer->email }}</a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- Opening Hours --}}
            <div class="footer-column">
                <h3>Jam Operasional</h3>
                <ul class="footer-hours">
                    <li>
                        <span>Senin - Jumat</span>
                        <span class="hours-time">{{ $footer->monday_friday_hours ?? '10:00 - 22:00' }}</span>
                    </li>
                    <li>
                        <span>Sabtu - Minggu</span>
                        <span class="hours-time">{{ $footer->saturday_sunday_hours ?? '09:00 - 23:00' }}</span>
                    </li>
                </ul>
                @if($footer->google_maps_url)
                <a href="{{ $footer->google_maps_url }}" target="_blank" rel="noopener" class="footer-maps-btn">
                    <i class="ri-map-pin-fill"></i>
                    Lihat di Google Maps
                </a>
                @endif
            </div>
        </div>

        {{-- Copyright --}}
        <div class="footer-copyright">
            <p>&copy; {{ date('Y') }} Class Billiard. All rights reserved.</p>
        </div>
    </div>
</footer>
@endif
{{-- Footer Section --}}
