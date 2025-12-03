@extends('layouts.app')

@section('title', 'Beranda - Billiard Class')

@section('content')
    <section>
        {{-- Title --}}
        <div class="hero-title">
            <h1>Selamat Datang di Billiard Class</h1>
        </div>
{{-- Title --}}
        
{{-- Hero Image and pargraf --}}
        <div class="hero-layout">
            <div class="hero-image-wrapper">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo Billiard Class">
            </div>

            <div class="hero-copy">
                <p>
                    Tempat terbaik untuk bermain billiard dengan suasana nyaman, fasilitas lengkap, dan harga
                    bersahabat. Rasakan pengalaman bermain yang seru bersama teman, komunitas, ataupun keluarga.
                </p>
            </div>
        </div>

        {{-- Hero Image and pargraf --}}

        {{-- Fasilitas --}}
        <div class="section-fasilitas">
            <div class="fasilitas">
                <h1>Fasilitas</h1>
                <p>Ruangan Reguler | Ruangan Vip | Free WiFi | Cafe and Eatry | Toilet</p>
            </div>
        </div>
        {{-- Fasilitas --}}
    </section>
@endsection


