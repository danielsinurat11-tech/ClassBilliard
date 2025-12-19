{{-- Footer Section --}}
@php
    $footer = \App\Models\Footer::where('is_active', true)->first();
@endphp
@if($footer)
<footer class="bg-[#1a1a1a] border-t border-[#fa9a08]/20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            {{-- About --}}
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Tentang Kami</h3>
                @if($footer->about_text)
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    {{ $footer->about_text }}
                </p>
                @endif
                <div class="flex gap-3">
                    @if($footer->facebook_url)
                    <a href="{{ $footer->facebook_url }}" target="_blank" class="w-10 h-10 rounded-full bg-[#2a2a2a] flex items-center justify-center text-[#fa9a08] hover:bg-[#fa9a08] hover:text-white transition-all">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    @endif
                    @if($footer->instagram_url)
                    <a href="{{ $footer->instagram_url }}" target="_blank" class="w-10 h-10 rounded-full bg-[#2a2a2a] flex items-center justify-center text-[#fa9a08] hover:bg-[#fa9a08] hover:text-white transition-all">
                        <i class="ri-instagram-fill"></i>
                    </a>
                    @endif
                    @if($footer->twitter_url)
                    <a href="{{ $footer->twitter_url }}" target="_blank" class="w-10 h-10 rounded-full bg-[#2a2a2a] flex items-center justify-center text-[#fa9a08] hover:bg-[#fa9a08] hover:text-white transition-all">
                        <i class="ri-twitter-x-fill"></i>
                    </a>
                    @endif
                    @if($footer->youtube_url)
                    <a href="{{ $footer->youtube_url }}" target="_blank" class="w-10 h-10 rounded-full bg-[#2a2a2a] flex items-center justify-center text-[#fa9a08] hover:bg-[#fa9a08] hover:text-white transition-all">
                        <i class="ri-youtube-fill"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Tautan Cepat</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">Beranda</a></li>
                    <li><a href="{{ route('menu') }}" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">Menu</a></li>
                    <li><a href="#tentang-kami" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">Tentang Kami</a></li>
                    <li><a href="#fasilitas" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">Fasilitas</a></li>
                    <li><a href="#event" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">Event</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Kontak</h3>
                <ul class="space-y-3">
                    @if($footer->address)
                    <li class="flex items-start gap-3">
                        <i class="ri-map-pin-fill text-[#fa9a08] mt-1"></i>
                        <span class="text-gray-400 text-sm">
                            {!! nl2br(e($footer->address)) !!}
                        </span>
                    </li>
                    @endif
                    @if($footer->phone)
                    <li class="flex items-center gap-3">
                        <i class="ri-phone-fill text-[#fa9a08]"></i>
                        <a href="tel:{{ $footer->phone }}" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">{{ $footer->phone }}</a>
                    </li>
                    @endif
                    @if($footer->email)
                    <li class="flex items-center gap-3">
                        <i class="ri-mail-fill text-[#fa9a08]"></i>
                        <a href="mailto:{{ $footer->email }}" class="text-gray-400 hover:text-[#fa9a08] transition-colors text-sm">{{ $footer->email }}</a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- Opening Hours --}}
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Jam Operasional</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between text-gray-400">
                        <span>Senin - Jumat</span>
                        <span class="text-[#fa9a08]">{{ $footer->monday_friday_hours ?? '10:00 - 22:00' }}</span>
                    </li>
                    <li class="flex justify-between text-gray-400">
                        <span>Sabtu - Minggu</span>
                        <span class="text-[#fa9a08]">{{ $footer->saturday_sunday_hours ?? '09:00 - 23:00' }}</span>
                    </li>
                </ul>
                @if($footer->google_maps_url)
                <div class="mt-6">
                    <a href="{{ $footer->google_maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-[#fa9a08] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#e19e2b] transition-colors">
                        <i class="ri-map-pin-fill"></i>
                        Lihat di Google Maps
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-[#fa9a08]/20 pt-8 text-center">
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} Class Billiard. All rights reserved.
            </p>
        </div>
    </div>
</footer>
@endif
{{-- Footer Section --}}


