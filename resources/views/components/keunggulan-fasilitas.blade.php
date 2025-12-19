{{-- Keunggulan Fasilitas Section --}}
@php
    $fasilitas = \App\Models\KeunggulanFasilitas::where('is_active', true)->orderBy('order')->get();
@endphp
@if($fasilitas->count() > 0)
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Keunggulan & Fasilitas Kami</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Fasilitas lengkap dan modern untuk pengalaman bermain yang maksimal</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fasilitas as $item)
            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300 hover:transform hover:-translate-y-2">
                @if($item->icon)
                <div class="text-[#fa9a08] text-4xl mb-4">
                    <i class="{{ $item->icon }}"></i>
                </div>
                @endif
                <h3 class="text-xl font-bold text-white mb-2">{{ $item->name }}</h3>
                @if($item->description)
                <p class="text-gray-400">{{ $item->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Keunggulan Fasilitas Section --}}
