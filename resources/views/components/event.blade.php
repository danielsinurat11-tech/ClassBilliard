{{-- Event Section --}}
@php
    $events = \App\Models\Event::where('is_active', true)->orderBy('order')->get();
@endphp
@if($events->count() > 0)
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Event yang Diadakan</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Berbagai event dan turnamen yang telah kami selenggarakan</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
            <div class="bg-[#1a1a1a] rounded-xl overflow-hidden border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="aspect-video bg-[#2a2a2a] overflow-hidden">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/logo.png') }}" alt="{{ $event->event_title }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    @if($event->event_date)
                    <div class="flex items-center gap-2 text-[#fa9a08] mb-2">
                        <i class="ri-calendar-event-fill"></i>
                        <span class="text-sm">{{ $event->event_date->format('d M Y') }}</span>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold text-white mb-2">{{ $event->event_title }}</h3>
                    @if($event->event_description)
                    <p class="text-gray-400 text-sm mb-4">{{ $event->event_description }}</p>
                    @endif
                    @if($event->link_url)
                    <a href="{{ $event->link_url }}" class="text-[#fa9a08] hover:text-amber-400 transition-colors text-sm font-semibold">
                        Lihat Detail <i class="ri-arrow-right-line"></i>
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
