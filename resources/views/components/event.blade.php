{{-- Event Section --}}
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Event yang Diadakan</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Berbagai event dan turnamen yang telah kami selenggarakan</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Event 1 --}}
            <div class="bg-[#1a1a1a] rounded-xl overflow-hidden border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="aspect-video bg-[#2a2a2a] overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" alt="Event 1" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-[#fa9a08] mb-2">
                        <i class="ri-calendar-event-fill"></i>
                        <span class="text-sm">15 Des 2024</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Turnamen Billiard Nasional</h3>
                    <p class="text-gray-400 text-sm mb-4">Turnamen billiard tingkat nasional dengan hadiah total 50 juta rupiah</p>
                    <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors text-sm font-semibold">
                        Lihat Detail <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>

            {{-- Event 2 --}}
            <div class="bg-[#1a1a1a] rounded-xl overflow-hidden border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="aspect-video bg-[#2a2a2a] overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" alt="Event 2" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-[#fa9a08] mb-2">
                        <i class="ri-calendar-event-fill"></i>
                        <span class="text-sm">20 Nov 2024</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Workshop Billiard untuk Pemula</h3>
                    <p class="text-gray-400 text-sm mb-4">Workshop gratis untuk pemula yang ingin belajar teknik dasar billiard</p>
                    <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors text-sm font-semibold">
                        Lihat Detail <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>

            {{-- Event 3 --}}
            <div class="bg-[#1a1a1a] rounded-xl overflow-hidden border border-[#fa9a08]/20 hover:border-[#fa9a08] transition-all duration-300">
                <div class="aspect-video bg-[#2a2a2a] overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" alt="Event 3" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-[#fa9a08] mb-2">
                        <i class="ri-calendar-event-fill"></i>
                        <span class="text-sm">10 Okt 2024</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Championship Regional</h3>
                    <p class="text-gray-400 text-sm mb-4">Kejuaraan billiard tingkat regional dengan peserta dari berbagai kota</p>
                    <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors text-sm font-semibold">
                        Lihat Detail <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="#" class="inline-block bg-[#fa9a08] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#e19e2b] transition-colors">
                Lihat Semua Event
            </a>
        </div>
    </div>
</section>
{{-- Event Section --}}

