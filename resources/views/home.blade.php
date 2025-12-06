@extends('layouts.app')

@section('title', 'Beranda - Billiard Class')

@section('content')
    {{-- Hero Section --}}
    @include('components.hero-section')

    {{-- Tentang Kami Section --}}
    @include('components.tentang-kami')

    {{-- About Founder Section --}}
    @include('components.about-founder')

    {{-- Keunggulan & Fasilitas Section --}}
    @include('components.keunggulan-fasilitas')

    {{-- Portfolio & Achievement Section --}}
    @include('components.portfolio-achievement')

    {{-- Tim Kami Section --}}
    @include('components.tim-kami')

    {{-- Testimoni Pelanggan Section --}}
    @include('components.testimoni-pelanggan')

    {{-- Event Section --}}
    @include('components.event')

    {{-- Footer Section --}}
    @include('components.footer')
@endsection


