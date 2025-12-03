@extends('layouts.app')

@section('title', 'Kontak - Billiard Class')

@section('content')
    <section>
        <div class="sub">
            
            <h1>Alamat Class Billiard</h1>
        </div>
      
        <div class="contact-map-layout">
            <div class="contact-card">
                <div>
                    <h2>Class Billiard</h2>
                    <p class="contact-address">
                        Jl. Alpukat, Madurejo, Kec. Arut Sel., Kabupaten Kotawaringin Barat,
<br>
Kalimantan Tengah 74117
                    </p>
                    <br>
                    <a
                    class="link-map"
                    href="https://maps.app.goo.gl/s6s6nLvYNbmADmPm6"
                    target="_blank"
                    rel="noopener"
                >
                <i class="ri-send-plane-fill"></i>
                    Lihat peta lebih besar
                </a>
                </div>
               
            </div>
            <div class="map-wrapper">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15941.671025822048!2d111.62662440598528!3d-2.6912569260294203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e08efbcfdf37653%3A0x5350eb691dd0f8f8!2sClass%20Billiard!5e0!3m2!1sid!2sid!4v1764753142570!5m2!1sid!2sid"
                    width="600"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="sub">
                <h1>Hubungi Kami</h1>
        </div>
        <div class="sosmed-grid">
            <a class="social-card whatsapp" href="https://wa.me/6289513594316" target="_blank" rel="noopener">
                <span class="icon"><i class="ri-whatsapp-line"></i></span>
                <div>
                    <p>WhatsApp</p>
                    <strong>Admin Class Billiard</strong>
                </div>
                <i class="ri-arrow-right-up-line arrow"></i>
            </a>
            <a class="social-card instagram" href="https://instagram.com/class_billiard" target="_blank" rel="noopener">
                <span class="icon"><i class="ri-instagram-line"></i></span>
                <div>
                    <p>Instagram</p>
                    <strong>@class_billiard</strong>
                </div>
                <i class="ri-arrow-right-up-line arrow"></i>
            </a>
            <a class="social-card tiktok" href="https://www.tiktok.com/@classbilliard" target="_blank" rel="noopener">
                <span class="icon"><i class="ri-tiktok-line"></i></span>
                <div>
                    <p>TikTok</p>
                    <strong>@classbilliard</strong>
                </div>
                <i class="ri-arrow-right-up-line arrow"></i>
            </a>
            <a class="social-card youtube" href="https://www.youtube.com/@CLASSBILLIARDTV" target="_blank" rel="noopener">
                <span class="icon"><i class="ri-youtube-line"></i></span>
                <div>
                    <p>YouTube</p>
                    <strong>Class Billiard TV</strong>
                </div>
                <i class="ri-arrow-right-up-line arrow"></i>
            </a>
        </div>
    </section>
    <style>
        .sosmed-grid {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .social-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 1.25rem;
            border: 1px solid rgba(20, 20, 20, 0.12);
            background: rgba(15, 23, 42, 0.65);
            color: #fff;
            transition: transform 0.2s ease, border 0.2s ease;
        }

        .social-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .social-card .icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
        }

        .social-card p {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .social-card strong {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .social-card .arrow {
            margin-left: auto;
            font-size: 1.1rem;
            opacity: 0.7;
        }


        .sub {
                padding-block: 4rem 2rem;
            }

            .sub h1 {
                font-size: clamp(1.875rem, 4vw, 2.5rem);
                line-height: 1.2;
                font-weight: 650;
                text-align: center;
            }

        .contact-map-layout {
            margin-top: 2.5rem;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            align-items: stretch;
        }

        .contact-card {
            flex: 1 1 320px;
            min-width: 280px;
            background: #000000;
            color: #ffffff;
            border-radius: 1.5rem;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-card h2 {
            font-size: 2.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .contact-address {
            font-size: 1rem;
            line-height: 1.6;
            color: #f6f6f6;
        }

        .rating-row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 1rem;
        }

        .rating-score {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .rating-stars {
            color: #fbbf24;
            letter-spacing: 0.2rem;
        }

        .rating-count {
            color: #ffffff;
            font-size: 0.95rem;
        }

        .btn-route {
            margin-top: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            background: #0d6efd;
            color: #fff;
            border-radius: 999px;
            font-weight: 500;
            width: fit-content;
        }

        .btn-route .indicator {
            width: 0.85rem;
            height: 0.85rem;
            border-radius: 50%;
            background: #fff;
        }

        .link-map {
            margin-top: auto;
            color: #0d6efd;
            font-weight: 500;
        }

        .map-wrapper {
            flex: 2 1 420px;
            border-radius: 1.5rem;
            overflow: hidden;
            min-height: 320px;
            background: #0f172a;
        }

        .map-wrapper iframe {
            width: 100%;
            height: 100%;
        }
    </style>
@endsection


