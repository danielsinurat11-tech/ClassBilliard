{{-- Navbar Component --}}
<nav class="absolute top-0 left-0 w-full z-50 h-24 transition-all duration-300" data-aos="fade-down"
    data-aos-duration="1000">
    <div class="container mx-auto px-6 h-full flex items-center justify-between">
        <!-- Left: Brand/Logo (Small) -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                class="w-12 h-12 object-contain drop-shadow-[0_0_5px_rgba(255,215,0,0.5)]">
            <span class="text-white font-bold tracking-[0.2em] text-sm hidden md:block">CLASS BILLIARD</span>
        </div>

        <!-- Right: Menu Links -->
        <div class="hidden md:flex space-x-12 items-center">
            <a href="#" class="text-gold-400 text-sm font-bold tracking-widest relative group">
                HOME
                <span
                    class="absolute -bottom-2 left-1/2 w-1 h-1 bg-gold-400 rounded-full transform -translate-x-1/2"></span>
            </a>
            <a href="#menu"
                class="text-gray-400 hover:text-white text-sm font-semibold tracking-widest transition duration-300">MENU</a>
            <a href="#reservation"
                class="text-gray-400 hover:text-white text-sm font-semibold tracking-widest transition duration-300">RESERVATION</a>
            <a href="#contact"
                class="px-6 py-2 border border-gold-400/30 text-gold-400 hover:bg-gold-400 hover:text-black text-sm font-bold tracking-widest transition duration-300 rounded-sm">
                CONTACT US
            </a>
        </div>
    </div>
</nav>

