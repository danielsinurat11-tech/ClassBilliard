{{-- Portfolio & Achievement Section --}}
<section class="py-16 bg-[#1a1a1a]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Portfolio & Achievement</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Prestasi dan pencapaian yang telah kami raih</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Achievement 1 --}}
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-5xl mb-4">
                    <i class="ri-trophy-fill"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">100+</h3>
                <p class="text-gray-400">Turnamen Diselenggarakan</p>
            </div>

            {{-- Achievement 2 --}}
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-5xl mb-4">
                    <i class="ri-user-heart-fill"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">5000+</h3>
                <p class="text-gray-400">Pelanggan Setia</p>
            </div>

            {{-- Achievement 3 --}}
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-5xl mb-4">
                    <i class="ri-star-fill"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">4.8</h3>
                <p class="text-gray-400">Rating Pelanggan</p>
            </div>

            {{-- Achievement 4 --}}
            <div class="bg-black p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                <div class="text-[#fa9a08] text-5xl mb-4">
                    <i class="ri-calendar-check-fill"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">10+</h3>
                <p class="text-gray-400">Tahun Pengalaman</p>
            </div>
        </div>

        {{-- Gallery Portfolio --}}
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-white mb-6 text-center">Galeri Kegiatan</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-[#2a2a2a] rounded-xl overflow-hidden aspect-square">
                    <img src="{{ asset('assets/logo.png') }}" alt="Portfolio 1" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="bg-[#2a2a2a] rounded-xl overflow-hidden aspect-square">
                    <img src="{{ asset('assets/logo.png') }}" alt="Portfolio 2" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="bg-[#2a2a2a] rounded-xl overflow-hidden aspect-square">
                    <img src="{{ asset('assets/logo.png') }}" alt="Portfolio 3" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Portfolio & Achievement Section --}}

