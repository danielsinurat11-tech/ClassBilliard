{{-- About Founder Section --}}
<section class="py-16 bg-[#1a1a1a]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Tentang Founder</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Kenali pendiri Class Billiard yang berdedikasi untuk memberikan pengalaman terbaik</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-black p-8 md:p-12 rounded-2xl border border-[#fa9a08]/20">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    {{-- Foto Founder --}}
                    <div class="flex-shrink-0">
                        <div class="w-48 h-48 md:w-64 md:h-64 rounded-full bg-[#2a2a2a] border-4 border-[#fa9a08] overflow-hidden">
                            <img 
                                src="{{ asset('assets/logo.png') }}" 
                                alt="Founder Class Billiard" 
                                class="w-full h-full object-cover"
                                onerror="this.src='{{ asset('assets/img/default.png') }}'">
                        </div>
                    </div>

                    {{-- Info Founder --}}
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-3xl font-bold text-[#fa9a08] mb-4">Nama Founder</h3>
                        <p class="text-gray-300 text-lg mb-6 leading-relaxed">
                            Sebagai pendiri Class Billiard, saya memiliki visi untuk menciptakan tempat yang tidak hanya untuk bermain billiard, tetapi juga sebagai wadah untuk membangun komunitas yang solid. Dengan pengalaman bertahun-tahun di industri ini, saya berkomitmen untuk memberikan pengalaman terbaik bagi setiap pengunjung.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors">
                                <i class="ri-facebook-fill text-2xl"></i>
                            </a>
                            <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors">
                                <i class="ri-instagram-fill text-2xl"></i>
                            </a>
                            <a href="#" class="text-[#fa9a08] hover:text-amber-400 transition-colors">
                                <i class="ri-linkedin-fill text-2xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- About Founder Section --}}

