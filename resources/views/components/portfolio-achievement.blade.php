{{-- Portfolio & Achievement Section --}}
@php
    $achievements = \App\Models\PortfolioAchievement::where('type', 'achievement')->where('is_active', true)->orderBy('order')->get();
    $galleries = \App\Models\PortfolioAchievement::where('type', 'gallery')->where('is_active', true)->orderBy('order')->get();
@endphp
@if($achievements->count() > 0 || $galleries->count() > 0)
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Portfolio & Achievement</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Prestasi dan pencapaian yang telah kami raih</p>
        </div>

        @if($achievements->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($achievements as $achievement)
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                @if($achievement->icon)
                <div class="text-[#fa9a08] text-5xl mb-4">
                    <i class="{{ $achievement->icon }}"></i>
                </div>
                @endif
                @if($achievement->number)
                <h3 class="text-3xl font-bold text-white mb-2">{{ $achievement->number }}</h3>
                @endif
                @if($achievement->label)
                <p class="text-gray-400">{{ $achievement->label }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Gallery Portfolio --}}
        @if($galleries->count() > 0)
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-white mb-6 text-center">Galeri Kegiatan</h3>
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($galleries as $gallery)
                <div class="bg-[#2a2a2a] rounded-xl overflow-hidden aspect-square">
                    <img src="{{ $gallery->image ? asset('storage/' . $gallery->image) : asset('assets/logo.png') }}" alt="Portfolio" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif
{{-- Portfolio & Achievement Section --}}
