{{-- Testimoni Pelanggan Section --}}
@php
    $testimonis = \App\Models\TestimoniPelanggan::where('is_active', true)->orderBy('order')->get();
@endphp
@if($testimonis->count() > 0)
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Testimoni Pelanggan</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Apa kata pelanggan tentang Class Billiard</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonis as $testimoni)
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20">
                <div class="flex items-center gap-1 mb-4 text-[#fa9a08]">
                    @for($i = 0; $i < $testimoni->rating; $i++)
                    <i class="ri-star-fill"></i>
                    @endfor
                </div>
                @if($testimoni->testimonial)
                <p class="text-gray-300 mb-6 leading-relaxed">
                    "{{ $testimoni->testimonial }}"
                </p>
                @endif
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#2a2a2a] border-2 border-[#fa9a08] overflow-hidden">
                        <img src="{{ $testimoni->photo ? asset('storage/' . $testimoni->photo) : asset('assets/logo.png') }}" alt="{{ $testimoni->customer_name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-white font-semibold">{{ $testimoni->customer_name }}</h4>
                        <p class="text-gray-400 text-sm">{{ $testimoni->customer_role }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Testimoni Pelanggan Section --}}

