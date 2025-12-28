<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Billiard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            gold: {
                                400: '#FFD700', // Yellow/Gold from mockup
                                500: '#E6C200',
                                600: '#B39700',
                            }
                        },
                        fontFamily: {
                            sans: ['Montserrat', 'sans-serif'],
                            rumonds: ['Rumonds', 'sans-serif'], // Fallback config
                        }
                    }
                }
            }
        </script>
        <style>
            @font-face {
                font-family: 'Rumonds';
                src: url('/fonts/rumonds.otf') format('opentype');
                font-weight: normal;
                font-style: normal;
            }
        </style>
    @endif

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        /* Custom Clip Paths for Diagonals */
        .clip-diagonal-hero {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .navbar-bg-shape {
            background: linear-gradient(to right, #1a1a1a 0%, #1a1a1a 80%, transparent 100%);
            transform: skewX(-20deg);
            transform-origin: top left;
        }
    </style>
</head>

<body class="bg-black text-white antialiased">

    <!-- Navbar -->
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

    <!-- Hero Section -->
    <header class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Parallax Feel -->
        <div class="absolute inset-0 z-0">
            <!-- Using image2 (Table) as background for context -->
            <img src="{{ asset('images/image2.png') }}" alt="Background"
                class="w-full h-full object-cover scale-105 filter brightness-[0.3] contrast-125">
            <!-- Vignette & Gradient -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/20 to-[#111111]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,rgba(0,0,0,0.8)_100%)]">
            </div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 flex flex-col items-center text-center px-4">
            <!-- Decorative Line -->
            <div class="h-16 w-px bg-gradient-to-b from-transparent via-gold-400 to-transparent mb-8 opacity-50"
                data-aos="fade-down" data-aos-delay="200"></div>

            <!-- Main Logo (Refined) -->
            <div class="mb-10 relative group cursor-default" data-aos="zoom-in" data-aos-duration="1500">
                <div
                    class="absolute -inset-8 bg-gold-400/10 blur-[40px] rounded-full group-hover:bg-gold-400/20 transition duration-700">
                </div>
                <img src="{{ asset('images/logo.png') }}" alt="CLASS BILLIARD"
                    class="h-40 md:h-56 w-auto object-contain drop-shadow-[0_10px_30px_rgba(0,0,0,0.5)] relative z-10 transform transition duration-700 group-hover:scale-105">
            </div>

            <!-- Typography -->
            <h1 class="text-white text-4xl md:text-6xl font-serif font-light tracking-wide mb-4" data-aos="fade-up"
                data-aos-delay="300">
                The Art of <span class="text-gold-400 font-rumonds italic pr-2">Precision</span>
            </h1>

            <p class="text-gray-400 text-sm md:text-base tracking-[0.3em] uppercase mb-12 font-light border-t border-b border-white/10 py-3 px-8"
                data-aos="fade-up" data-aos-delay="500">
                Premium Billiard Lounge & Bar
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col md:flex-row gap-6" data-aos="fade-up" data-aos-delay="700">
                <a href="#reservation"
                    class="group relative px-8 py-4 bg-transparent overflow-hidden rounded-sm transition-all duration-300">
                    <div
                        class="absolute inset-0 w-full h-full bg-gold-400/90 group-hover:bg-gold-500 transition duration-300">
                    </div>
                    <span class="relative text-black font-bold tracking-widest text-sm flex items-center gap-2">
                        BOOK A TABLE
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </span>
                </a>
                <a href="#about"
                    class="px-8 py-4 border border-white/20 text-white hover:border-white hover:bg-white/5 transition duration-300 rounded-sm font-light tracking-widest text-sm">
                    EXPLORE
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce gap-2 opacity-50"
            data-aos="fade-in" data-aos-delay="1000" data-aos-duration="1000">
            <span class="text-[10px] text-white tracking-[0.2em] uppercase">Scroll</span>
            <div class="w-px h-8 bg-gradient-to-b from-gold-400 to-transparent"></div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="relative py-24 bg-[#111111] overflow-hidden -mt-10">
        <!-- Background Elements -->
        <div
            class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-[#1a1a1a] to-transparent skew-x-12 opacity-50">
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <!-- Text Content -->
                <div class="md:w-1/2 text-left" data-aos="fade-right" data-aos-duration="1000">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-gold-400"></div>
                        <h2 class="text-4xl md:text-5xl text-white font-rumonds tracking-wide">ABOUT</h2>
                    </div>

                    <div class="text-gray-300 text-sm md:text-base leading-relaxed text-justify font-light space-y-6">
                        <p>
                            Welcome to <span class="text-gold-400 font-semibold">Class Billiard</span>, where precision
                            meets luxury.
                            We redefine the pool experience with world-class tables, an exclusive atmosphere, and a
                            community of
                            passionate players. Whether you're a seasoned pro or a casual enthusiast, our lounge offers
                            the
                            perfect setting to refine your skills.
                        </p>
                        <p>
                            Established with a vision to elevate the sport, we host regular tournaments and provide
                            top-tier equipment. Join us and experience the gold standard of billiards.
                        </p>
                    </div>
                </div>

                <!-- Image/Visual -->
                <div class="md:w-1/2 relative flex justify-center" data-aos="fade-left" data-aos-duration="1000">
                    <div class="relative w-full max-w-[500px]">
                        <!-- Image -->
                        <img src="{{ asset('images/image2.png') }}" alt="About Class Billiard"
                            class="relative z-10 w-full h-[400px] object-cover rounded-3xl grayscale hover:grayscale-0 transition duration-500 shadow-2xl block">
                        <!-- Decorative Border (Overlay) -->
                        <div class="absolute inset-0 border-4 border-gold-400 rounded-3xl z-20 pointer-events-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section id="achievements" class="py-24 bg-[#0a0a0a] relative overflow-hidden">
        <!-- Diagonal Separator Top -->
        <div class="absolute top-0 left-0 w-full -mt-1">
            <div class="h-16 w-full bg-[#111111] transform -skew-y-2 origin-top-left"></div>
            <div class="h-2 w-full bg-gold-600 transform -skew-y-2 origin-top-left translate-y-[-0.5rem] opacity-70">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <h2 class="text-3xl md:text-4xl text-white text-center mb-16 font-rumonds tracking-widest uppercase">
                OUR ACHIEVEMENT
            </h2>

            <!-- Interactive Expanding Gallery (Accordion Style) -->
            <div class="flex flex-col md:flex-row gap-2 md:gap-4 h-[500px] max-w-7xl mx-auto px-4">

                <!-- Card 1 -->
                <div
                    class="relative flex-1 hover:flex-[3] transition-all duration-700 ease-in-out rounded-3xl overflow-hidden group cursor-pointer border border-gray-800 hover:border-gold-400">
                    <img src="{{ asset('images/champion3.jpg') }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                    <div class="absolute inset-0 bg-black/60 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-gold-400 font-bold text-xl tracking-wider uppercase">WINNER</h3>
                        <p class="text-white text-sm font-light">Regional Championship</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div
                    class="relative flex-1 hover:flex-[3] transition-all duration-700 ease-in-out rounded-3xl overflow-hidden group cursor-pointer border border-gray-800 hover:border-gold-400">
                    <img src="{{ asset('images/champion2.jpg') }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                    <div class="absolute inset-0 bg-black/60 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-gold-400 font-bold text-xl tracking-wider uppercase">SEMIFINALIST</h3>
                        <p class="text-white text-sm font-light">National Open</p>
                    </div>
                </div>

                <!-- Card 3 (Champion - Center) -->
                <div
                    class="relative flex-[3] hover:flex-[4] transition-all duration-700 ease-in-out rounded-3xl overflow-hidden group cursor-pointer border-2 border-gold-400 shadow-[0_0_20px_rgba(255,215,0,0.3)]">
                    <img src="{{ asset('images/champion1.jpg') }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 w-full p-8 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-gold-400 font-bold text-3xl font-rumonds tracking-widest uppercase mb-1">
                            CHAMPION</h3>
                        <p class="text-white text-base font-light">Grand Tournament 2024</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div
                    class="relative flex-1 hover:flex-[3] transition-all duration-700 ease-in-out rounded-3xl overflow-hidden group cursor-pointer border border-gray-800 hover:border-gold-400">
                    <img src="{{ asset('images/champion2.jpg') }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                    <div class="absolute inset-0 bg-black/60 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-gold-400 font-bold text-xl tracking-wider uppercase">RUNNER UP</h3>
                        <p class="text-white text-sm font-light">City League</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div
                    class="relative flex-1 hover:flex-[3] transition-all duration-700 ease-in-out rounded-3xl overflow-hidden group cursor-pointer border border-gray-800 hover:border-gold-400">
                    <img src="{{ asset('images/champion3.jpg') }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                    <div class="absolute inset-0 bg-black/60 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-gold-400 font-bold text-xl tracking-wider uppercase">2ND PLACE</h3>
                        <p class="text-white text-sm font-light">Mayor's Cup</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- About Founder Section -->
    <section id="founder" class="py-24 bg-[#0F0F0F] relative overflow-hidden">
        <!-- Background Typography (Watermark) -->
        <div class="absolute top-10 left-0 w-full select-none pointer-events-none overflow-hidden leading-none"
            data-aos="fade-down" data-aos-duration="1500" data-aos-anchor-placement="top-bottom">
            <h2
                class="text-[150px] md:text-[250px] font-bold text-white/[0.03] text-center whitespace-nowrap tracking-tighter">
                THE FOUNDER
            </h2>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-center relative">

                <!-- Image Section (Right Side, but rendered first for stacking context usually, but here we want text on top) -->
                <div class="md:w-1/2 relative order-1 md:order-2 group md:-mt-16" data-aos="fade-up"
                    data-aos-duration="1200">
                    <div class="relative w-full h-[450px] md:h-[550px] overflow-hidden rounded-lg shadow-2xl">
                        <!-- Main Image -->
                        <img src="{{ asset('images/founde.png') }}" alt="Founder"
                            class="w-full h-full object-cover filter grayscale contrast-125 transition-all duration-1000 group-hover:grayscale-0 group-hover:scale-105"
                            style="object-position: top center;">

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent via-black/20 to-black/80"></div>

                        <!-- Gold Border Frame -->
                        <div
                            class="absolute top-4 right-4 w-full h-full border-2 border-gold-400/50 rounded-lg -z-10 translate-x-4 translate-y-4 hidden md:block transition-transform duration-500 group-hover:translate-x-6 group-hover:translate-y-6">
                        </div>
                    </div>
                </div>

                <!-- Text Content (Left Side - Overlapping) -->
                <div class="md:w-1/2 relative order-2 md:order-1 mt-[-80px] md:mt-0 md:mr-[-50px] z-20"
                    data-aos="fade-right" data-aos-delay="300" data-aos-duration="1200">
                    <div
                        class="bg-[#1a1a1a]/95 backdrop-blur-sm p-8 md:p-12 border-l-4 border-gold-400 shadow-2xl rounded-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <span class="h-px w-12 bg-gold-400"></span>
                            <span class="text-gold-400 font-bold tracking-[0.2em] uppercase text-sm">Visionary</span>
                        </div>

                        <h3 class="text-3xl md:text-5xl text-white font-serif mb-6 leading-tight">
                            Crafting the <br> <span class="italic text-gold-400">Perfect Game</span>
                        </h3>

                        <p class="text-gray-400 text-base leading-relaxed mb-8 font-light">
                            "Billiards is not just a game; it is an art of precision, patience, and strategy.
                            My vision was to create a sanctuary where every shot resonates with luxury and
                            every player feels like a champion."
                        </p>

                        <div class="flex items-center justify-between border-t border-white/10 pt-6">
                            <div>
                                <h4 class="text-white text-xl font-serif font-bold">Lorem Ipsum</h4>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Founder & CEO</p>
                            </div>
                            <!-- Signature (Visual representation) -->
                            <div class="text-gold-400/50 font-serif italic text-2xl pr-4">L.Ipsum</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="py-24 bg-[#050505] relative overflow-hidden">
        <!-- Decorative Background Glow -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-gold-400/5 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-white/5 rounded-full blur-[80px] pointer-events-none">
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div>
                    <h2 class="text-4xl md:text-6xl text-white font-rumonds tracking-wide mb-2">UPCOMING <span
                            class="text-gold-400">EVENTS</span></h2>
                    <p class="text-gray-400 font-light tracking-[0.2em] text-sm uppercase">Join the excitement at Class
                        Billiard</p>
                </div>
                <!-- Navigation Buttons (Visual only for now, can be hooked to JS) -->
                <div class="flex gap-4">
                    <button
                        class="w-12 h-12 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-400 hover:text-black hover:border-gold-400 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        class="w-12 h-12 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-400 hover:text-black hover:border-gold-400 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Events Slider (Horizontal Scroll) -->
            <div class="flex overflow-x-auto gap-8 pb-12 snap-x snap-mandatory scrollbar-hide"
                style="-ms-overflow-style: none; scrollbar-width: none;">

                <!-- Event Card 1 -->
                <div class="min-w-[320px] md:min-w-[400px] h-[550px] relative rounded-sm overflow-hidden group snap-center cursor-pointer"
                    data-aos="fade-up" data-aos-delay="100">
                    <!-- Image -->
                    <img src="{{ asset('images/bl.png') }}" alt="Event 1"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter brightness-75 group-hover:brightness-100">

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                    <!-- Content Box -->
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <!-- Date Badge -->
                        <div
                            class="absolute top-6 left-6 bg-gold-400/90 backdrop-blur-md px-4 py-3 text-center shadow-[0_0_20px_rgba(255,215,0,0.3)] transform transition-transform duration-300 group-hover:-translate-y-1">
                            <span class="block text-xs font-bold text-black uppercase tracking-wider mb-1">DEC</span>
                            <span class="block text-3xl font-rumonds text-black leading-none">25</span>
                        </div>

                        <!-- Text Info -->
                        <div
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <div
                                class="flex items-center gap-3 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                <span
                                    class="px-3 py-1 border border-gold-400 rounded-full text-[10px] font-bold text-gold-400 uppercase tracking-widest">Tournament</span>
                            </div>
                            <h3
                                class="text-3xl text-white font-serif mb-3 leading-tight group-hover:text-gold-400 transition-colors duration-300">
                                Golden Cup 2024</h3>
                            <p
                                class="text-gray-300 text-sm font-light line-clamp-2 mb-6 opacity-80 group-hover:opacity-100">
                                The most prestigious 9-ball tournament of the year. Grand prize $10,000.
                            </p>
                            <span
                                class="inline-flex items-center gap-2 text-white text-xs font-bold tracking-[0.2em] uppercase border-b border-gold-400 pb-1 group-hover:text-gold-400 transition-colors">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <!-- Hover Border -->
                    <div
                        class="absolute inset-0 border border-white/10 group-hover:border-gold-400/50 transition-colors duration-500 pointer-events-none">
                    </div>
                </div>

                <!-- Event Card 2 -->
                <div class="min-w-[320px] md:min-w-[400px] h-[550px] relative rounded-sm overflow-hidden group snap-center cursor-pointer"
                    data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ asset('images/bl2.png') }}" alt="Event 2"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter brightness-75 group-hover:brightness-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div
                            class="absolute top-6 left-6 bg-[#1a1a1a]/90 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <span class="block text-xs font-bold text-gold-400 uppercase tracking-wider mb-1">JAN</span>
                            <span class="block text-3xl font-rumonds text-white leading-none">01</span>
                        </div>
                        <div
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <div
                                class="flex items-center gap-3 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                <span
                                    class="px-3 py-1 border border-white/30 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">Social</span>
                            </div>
                            <h3
                                class="text-3xl text-white font-serif mb-3 leading-tight group-hover:text-gold-400 transition-colors duration-300">
                                New Year's Eve</h3>
                            <p
                                class="text-gray-300 text-sm font-light line-clamp-2 mb-6 opacity-80 group-hover:opacity-100">
                                Celebrate the new year with free-flow drinks and midnight countdown.
                            </p>
                            <span
                                class="inline-flex items-center gap-2 text-white text-xs font-bold tracking-[0.2em] uppercase border-b border-gold-400 pb-1 group-hover:text-gold-400 transition-colors">
                                Book Table
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 border border-white/10 group-hover:border-gold-400/50 transition-colors duration-500 pointer-events-none">
                    </div>
                </div>

                <!-- Event Card 3 -->
                <div class="min-w-[320px] md:min-w-[400px] h-[550px] relative rounded-sm overflow-hidden group snap-center cursor-pointer"
                    data-aos="fade-up" data-aos-delay="300">
                    <img src="{{ asset('images/bl3.png') }}" alt="Event 3"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter brightness-75 group-hover:brightness-100"
                        style="object-position: top center;">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div
                            class="absolute top-6 left-6 bg-[#1a1a1a]/90 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <span class="block text-xs font-bold text-gold-400 uppercase tracking-wider mb-1">JAN</span>
                            <span class="block text-3xl font-rumonds text-white leading-none">15</span>
                        </div>
                        <div
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <div
                                class="flex items-center gap-3 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                <span
                                    class="px-3 py-1 border border-white/30 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">Masterclass</span>
                            </div>
                            <h3
                                class="text-3xl text-white font-serif mb-3 leading-tight group-hover:text-gold-400 transition-colors duration-300">
                                Pro Coaching Clinic</h3>
                            <p
                                class="text-gray-300 text-sm font-light line-clamp-2 mb-6 opacity-80 group-hover:opacity-100">
                                Learn from the best. Exclusive session with our founder and pro players.
                            </p>
                            <span
                                class="inline-flex items-center gap-2 text-white text-xs font-bold tracking-[0.2em] uppercase border-b border-gold-400 pb-1 group-hover:text-gold-400 transition-colors">
                                Register
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 border border-white/10 group-hover:border-gold-400/50 transition-colors duration-500 pointer-events-none">
                    </div>
                </div>

                <!-- Event Card 4 -->
                <div class="min-w-[320px] md:min-w-[400px] h-[550px] relative rounded-sm overflow-hidden group snap-center cursor-pointer"
                    data-aos="fade-up" data-aos-delay="400">
                    <img src="{{ asset('images/bl.png') }}" alt="Event 4"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter brightness-75 group-hover:brightness-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div
                            class="absolute top-6 left-6 bg-[#1a1a1a]/90 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <span class="block text-xs font-bold text-gold-400 uppercase tracking-wider mb-1">FEB</span>
                            <span class="block text-3xl font-rumonds text-white leading-none">10</span>
                        </div>
                        <div
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <div
                                class="flex items-center gap-3 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                <span
                                    class="px-3 py-1 border border-white/30 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">League</span>
                            </div>
                            <h3
                                class="text-3xl text-white font-serif mb-3 leading-tight group-hover:text-gold-400 transition-colors duration-300">
                                City League Opening</h3>
                            <p
                                class="text-gray-300 text-sm font-light line-clamp-2 mb-6 opacity-80 group-hover:opacity-100">
                                The start of the new city league season. Come and support your local team.
                            </p>
                            <span
                                class="inline-flex items-center gap-2 text-white text-xs font-bold tracking-[0.2em] uppercase border-b border-gold-400 pb-1 group-hover:text-gold-400 transition-colors">
                                View Schedule
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 border border-white/10 group-hover:border-gold-400/50 transition-colors duration-500 pointer-events-none">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="py-24 bg-[#0a0a0a] relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <div
                class="absolute top-0 left-1/4 w-px h-full bg-gradient-to-b from-transparent via-white/10 to-transparent">
            </div>
            <div
                class="absolute top-0 right-1/4 w-px h-full bg-gradient-to-b from-transparent via-white/10 to-transparent">
            </div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-gold-400 font-bold tracking-[0.3em] text-sm uppercase mb-4 block">The Masters</span>
                <h2 class="text-4xl md:text-6xl text-white font-rumonds tracking-wide">OUR <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 to-white">ELITE TEAM</span>
                </h2>
            </div>

            <!-- Team Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Team Member 1 -->
                <div class="group relative h-[600px] overflow-hidden rounded-sm cursor-pointer" data-aos="fade-up"
                    data-aos-delay="100">
                    <!-- Background Name (Vertical) -->
                    <div
                        class="absolute top-0 left-0 h-full w-full flex items-center justify-center z-0 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                        <span
                            class="text-[120px] font-bold text-white transform -rotate-90 whitespace-nowrap font-rumonds">SARAH</span>
                    </div>

                    <!-- Image -->
                    <img src="{{ asset('images/orang1.png') }}" alt="Team Member 1"
                        class="absolute inset-0 w-full h-full object-cover filter grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-110 z-10">

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent z-20"></div>

                    <!-- Info Box -->
                    <div
                        class="absolute bottom-0 left-0 w-full p-8 z-30 transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="border-l-4 border-gold-400 pl-4">
                            <h3 class="text-3xl text-white font-serif italic mb-1">Sarah Jenkins</h3>
                            <p class="text-gold-400 text-xs font-bold tracking-[0.2em] uppercase mb-4">Senior Instructor
                            </p>
                            <p
                                class="text-gray-400 text-sm font-light opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 line-clamp-2">
                                Former National Champion with over 10 years of coaching experience. Specializes in
                                tactical gameplay.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 (Center - Highlighted) -->
                <div class="group relative h-[600px] overflow-hidden rounded-sm cursor-pointer md:-mt-8"
                    data-aos="fade-up" data-aos-delay="200">
                    <!-- Background Name (Vertical) -->
                    <div
                        class="absolute top-0 left-0 h-full w-full flex items-center justify-center z-0 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                        <span
                            class="text-[120px] font-bold text-white transform -rotate-90 whitespace-nowrap font-rumonds">DAVID</span>
                    </div>

                    <!-- Image -->
                    <img src="{{ asset('images/orang2.png') }}" alt="Team Member 2"
                        class="absolute inset-0 w-full h-full object-cover filter grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-110 z-10">

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent z-20"></div>

                    <!-- Info Box -->
                    <div
                        class="absolute bottom-0 left-0 w-full p-8 z-30 transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="border-l-4 border-gold-400 pl-4">
                            <h3 class="text-4xl text-white font-serif italic mb-1">David Chen</h3>
                            <p class="text-gold-400 text-xs font-bold tracking-[0.2em] uppercase mb-4">Head Pro</p>
                            <p
                                class="text-gray-400 text-sm font-light opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 line-clamp-2">
                                3-time World Pool Masters winner. The technical genius behind our training curriculum.
                            </p>
                        </div>
                    </div>

                    <!-- Gold Frame Effect for Center -->
                    <div
                        class="absolute inset-0 border border-gold-400/0 group-hover:border-gold-400/50 transition-colors duration-500 z-30 pointer-events-none">
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="group relative h-[600px] overflow-hidden rounded-sm cursor-pointer" data-aos="fade-up"
                    data-aos-delay="300">
                    <!-- Background Name (Vertical) -->
                    <div
                        class="absolute top-0 left-0 h-full w-full flex items-center justify-center z-0 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                        <span
                            class="text-[120px] font-bold text-white transform -rotate-90 whitespace-nowrap font-rumonds">MICHAEL</span>
                    </div>

                    <!-- Image -->
                    <img src="{{ asset('images/orang3.png') }}" alt="Team Member 3"
                        class="absolute inset-0 w-full h-full object-cover filter grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-110 z-10">

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent z-20"></div>

                    <!-- Info Box -->
                    <div
                        class="absolute bottom-0 left-0 w-full p-8 z-30 transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="border-l-4 border-gold-400 pl-4">
                            <h3 class="text-3xl text-white font-serif italic mb-1">Michael Ross</h3>
                            <p class="text-gold-400 text-xs font-bold tracking-[0.2em] uppercase mb-4">Trick Shot Artist
                            </p>
                            <p
                                class="text-gray-400 text-sm font-light opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 line-clamp-2">
                                Famous for his viral trick shots and exhibition matches. Brings creativity to the table.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Running Text Strip -->
    <div class="bg-gold-400 overflow-hidden py-3 transform -skew-y-1 relative z-20">
        <div class="flex whitespace-nowrap">
            <div class="animate-marquee flex-shrink-0 flex items-center pr-8">
                <span class="text-black font-bold text-xl tracking-widest uppercase flex items-center gap-8">
                    PREMIUM TABLES <span class="text-2xl">•</span>
                    PRO COACHING <span class="text-2xl">•</span>
                    TOURNAMENTS <span class="text-2xl">•</span>
                    VIP LOUNGE <span class="text-2xl">•</span>
                    FINE DINING <span class="text-2xl">•</span>
                </span>
            </div>
            <div class="animate-marquee flex-shrink-0 flex items-center pr-8">
                <span class="text-black font-bold text-xl tracking-widest uppercase flex items-center gap-8">
                    PREMIUM TABLES <span class="text-2xl">•</span>
                    PRO COACHING <span class="text-2xl">•</span>
                    TOURNAMENTS <span class="text-2xl">•</span>
                    VIP LOUNGE <span class="text-2xl">•</span>
                    FINE DINING <span class="text-2xl">•</span>
                </span>
            </div>
            <div class="animate-marquee flex-shrink-0 flex items-center pr-8">
                <span class="text-black font-bold text-xl tracking-widest uppercase flex items-center gap-8">
                    PREMIUM TABLES <span class="text-2xl">•</span>
                    PRO COACHING <span class="text-2xl">•</span>
                    TOURNAMENTS <span class="text-2xl">•</span>
                    VIP LOUNGE <span class="text-2xl">•</span>
                    FINE DINING <span class="text-2xl">•</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-[#080808] relative overflow-hidden">
        <!-- Background Watermark -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full text-center pointer-events-none select-none opacity-[0.02]">
            <h2 class="text-[150px] md:text-[300px] font-bold text-white leading-none">REVIEWS</h2>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-gold-400 font-bold tracking-[0.3em] text-sm uppercase mb-4 block">Client
                    Feedback</span>
                <h2 class="text-4xl md:text-6xl text-white font-rumonds tracking-wide">
                    TESTIMONIALS <br>
                    <span class="text-gray-500 text-2xl md:text-4xl font-serif italic lowercase">that speak to our
                        quality</span>
                </h2>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">

                <!-- Testimonial 1 -->
                <div class="bg-[#111] p-8 md:p-10 rounded-2xl relative group hover:bg-[#151515] transition-colors duration-300"
                    data-aos="fade-up" data-aos-delay="100">
                    <!-- Gold Accent Line -->
                    <div class="absolute top-10 left-0 w-1 h-16 bg-gold-400 rounded-r-full"></div>

                    <!-- Header: Avatar & Info -->
                    <div class="flex items-center gap-4 mb-6 pl-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gold-400/30 p-1">
                            <img src="{{ asset('images/orang1.png') }}" alt="User 1"
                                class="w-full h-full object-cover rounded-full filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div>
                            <h4 class="text-white text-xl font-bold font-serif">James Peterson</h4>
                            <p class="text-gold-400 text-xs uppercase tracking-wider">Pro Player</p>
                            <!-- Stars -->
                            <div class="flex text-gold-400 gap-1 mt-2 text-xs">
                                <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i
                                    class="fas fa-star">★</i><i class="fas fa-star">★</i>
                            </div>
                        </div>
                        <!-- Quote Icon -->
                        <div
                            class="ml-auto text-gold-400/20 text-6xl font-serif leading-none group-hover:text-gold-400/40 transition-colors">
                            "</div>
                    </div>

                    <!-- Content -->
                    <p class="text-gray-400 font-light leading-relaxed pl-4 relative z-10">
                        The tables here are absolutely world-class. The cloth speed is consistent, and the pockets are
                        cut to professional standards. Best place to practice for upcoming tournaments.
                    </p>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-[#111] p-8 md:p-10 rounded-2xl relative group hover:bg-[#151515] transition-colors duration-300"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-10 left-0 w-1 h-16 bg-gold-400 rounded-r-full"></div>

                    <div class="flex items-center gap-4 mb-6 pl-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gold-400/30 p-1">
                            <img src="{{ asset('images/orang2.png') }}" alt="User 2"
                                class="w-full h-full object-cover rounded-full filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div>
                            <h4 class="text-white text-xl font-bold font-serif">Sarah Mitchell</h4>
                            <p class="text-gold-400 text-xs uppercase tracking-wider">League Captain</p>
                            <div class="flex text-gold-400 gap-1 mt-2 text-xs">
                                <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i
                                    class="fas fa-star">★</i><i class="fas fa-star">★</i>
                            </div>
                        </div>
                        <div
                            class="ml-auto text-gold-400/20 text-6xl font-serif leading-none group-hover:text-gold-400/40 transition-colors">
                            "</div>
                    </div>

                    <p class="text-gray-400 font-light leading-relaxed pl-4 relative z-10">
                        Amazing atmosphere and top-tier service. The VIP room is stunning and perfect for private
                        events. I've never seen a billiard club with this level of luxury and attention to detail.
                    </p>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-[#111] p-8 md:p-10 rounded-2xl relative group hover:bg-[#151515] transition-colors duration-300"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute top-10 left-0 w-1 h-16 bg-gold-400 rounded-r-full"></div>

                    <div class="flex items-center gap-4 mb-6 pl-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gold-400/30 p-1">
                            <img src="{{ asset('images/orang3.png') }}" alt="User 3"
                                class="w-full h-full object-cover rounded-full filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div>
                            <h4 class="text-white text-xl font-bold font-serif">David Chen</h4>
                            <p class="text-gold-400 text-xs uppercase tracking-wider">Regular Member</p>
                            <div class="flex text-gold-400 gap-1 mt-2 text-xs">
                                <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i
                                    class="fas fa-star">★</i><i class="fas fa-star">★</i>
                            </div>
                        </div>
                        <div
                            class="ml-auto text-gold-400/20 text-6xl font-serif leading-none group-hover:text-gold-400/40 transition-colors">
                            "</div>
                    </div>

                    <p class="text-gray-400 font-light leading-relaxed pl-4 relative z-10">
                        I improved my game significantly thanks to the coaching clinic. The instructors are patient and
                        extremely knowledgeable. Highly recommended for beginners and pros alike.
                    </p>
                </div>

                <!-- Testimonial 4 -->
                <div class="bg-[#111] p-8 md:p-10 rounded-2xl relative group hover:bg-[#151515] transition-colors duration-300"
                    data-aos="fade-up" data-aos-delay="400">
                    <div class="absolute top-10 left-0 w-1 h-16 bg-gold-400 rounded-r-full"></div>

                    <div class="flex items-center gap-4 mb-6 pl-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gold-400/30 p-1">
                            <img src="{{ asset('images/champion1.jpg') }}" alt="User 4"
                                class="w-full h-full object-cover rounded-full filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                        <div>
                            <h4 class="text-white text-xl font-bold font-serif">Robert Fox</h4>
                            <p class="text-gold-400 text-xs uppercase tracking-wider">Tournament Director</p>
                            <div class="flex text-gold-400 gap-1 mt-2 text-xs">
                                <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i
                                    class="fas fa-star">★</i><i class="fas fa-star">★</i>
                            </div>
                        </div>
                        <div
                            class="ml-auto text-gold-400/20 text-6xl font-serif leading-none group-hover:text-gold-400/40 transition-colors">
                            "</div>
                    </div>

                    <p class="text-gray-400 font-light leading-relaxed pl-4 relative z-10">
                        The gold standard of billiard clubs in the city. The organization of their leagues and
                        tournaments is flawless. It's always a pleasure to compete here.
                    </p>
                </div>

            </div>

            <!-- Review Submission Form -->
            <div class="mt-24 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="500">
                <div class="relative bg-[#111] rounded-3xl p-6 md:p-8 border border-white/5 overflow-hidden">
                    <!-- Decorative Background -->
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-gold-400/5 rounded-full blur-[80px] pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full blur-[50px] pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl text-white font-serif mb-2">Share Your Experience</h3>
                            <p class="text-gray-400 font-light text-sm">We value your feedback. Tell us about your time
                                with us.</p>
                        </div>

                        <form class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name Input -->
                                <div class="group">
                                    <label
                                        class="block text-[10px] font-bold text-gold-400 uppercase tracking-widest mb-2 ml-1">Your
                                        Name</label>
                                    <input type="text"
                                        class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold-400 transition-colors duration-300"
                                        placeholder="John Doe">
                                </div>

                                <!-- Role Input -->
                                <div class="group">
                                    <label
                                        class="block text-[10px] font-bold text-gold-400 uppercase tracking-widest mb-2 ml-1">Occupation
                                        / Role (Optional)</label>
                                    <input type="text"
                                        class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold-400 transition-colors duration-300"
                                        placeholder="e.g. Amateur Player">
                                </div>
                            </div>

                            <!-- Rating Selection -->
                            <div class="group">
                                <label
                                    class="block text-[10px] font-bold text-gold-400 uppercase tracking-widest mb-2 ml-1">Rating</label>
                                <div
                                    class="flex gap-4 items-center bg-black/50 border border-white/10 rounded-lg px-4 py-3">
                                    <div class="flex gap-2 text-lg text-gray-600 hover:text-gold-400 cursor-pointer transition-colors"
                                        id="star-rating">
                                        <button type="button"
                                            class="hover:text-gold-400 focus:text-gold-400 transition-colors">★</button>
                                        <button type="button"
                                            class="hover:text-gold-400 focus:text-gold-400 transition-colors">★</button>
                                        <button type="button"
                                            class="hover:text-gold-400 focus:text-gold-400 transition-colors">★</button>
                                        <button type="button"
                                            class="hover:text-gold-400 focus:text-gold-400 transition-colors">★</button>
                                        <button type="button"
                                            class="hover:text-gold-400 focus:text-gold-400 transition-colors">★</button>
                                    </div>
                                    <span class="text-gray-500 text-xs ml-2 font-light">(Select stars)</span>
                                </div>
                            </div>

                            <!-- Message Input -->
                            <div class="group">
                                <label
                                    class="block text-[10px] font-bold text-gold-400 uppercase tracking-widest mb-2 ml-1">Your
                                    Review</label>
                                <textarea rows="3"
                                    class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold-400 transition-colors duration-300 resize-none"
                                    placeholder="Write your thoughts here..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center pt-2">
                                <button type="button"
                                    class="inline-block bg-gold-400 text-black px-8 py-3 rounded-full font-bold text-sm tracking-widest uppercase hover:bg-white transition-all duration-300 transform hover:scale-105 shadow-[0_0_20px_rgba(255,215,0,0.3)]">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="mt-20 text-center" data-aos="fade-up" data-aos-delay="600">
                <a href="#contact"
                    class="inline-block border border-gold-400 text-gold-400 px-10 py-4 rounded-full font-bold tracking-widest uppercase hover:bg-gold-400 hover:text-black transition-all duration-300 transform hover:scale-105">
                    Become a Member
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#050505] text-white pt-24 pb-12 border-t border-white/5 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-gold-400/20 rounded-full blur-[150px]">
            </div>
            <div class="absolute bottom-0 right-0 w-[40%] h-[40%] bg-gold-400/10 rounded-full blur-[100px]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

                <!-- Left Column: Map/Location (Taking up 5 columns) -->
                <div class="lg:col-span-5 space-y-8" data-aos="fade-right">
                    <div>
                        <h3 class="text-3xl font-rumonds mb-2">FIND US</h3>
                        <div class="w-16 h-1 bg-gold-400"></div>
                    </div>

                    <!-- Stylized Map Container -->
                    <div class="relative w-full h-[300px] rounded-2xl overflow-hidden border border-white/10 group">
                        <!-- Placeholder Map Image/Iframe -->
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153169!3d-37.8172099797517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4c2b349649%3A0xb6899234e561db11!2sEnvato!5e0!3m2!1sen!2sus!4v1641364582314!5m2!1sen!2sus"
                            width="100%" height="100%"
                            style="border:0; filter: grayscale(100%) invert(92%) contrast(83%);" allowfullscreen=""
                            loading="lazy">
                        </iframe>

                        <!-- Overlay Card -->
                        <div
                            class="absolute bottom-4 left-4 right-4 bg-black/90 backdrop-blur-md p-4 rounded-xl border border-white/10 flex items-center justify-between">
                            <div>
                                <p class="text-gold-400 text-xs font-bold tracking-widest uppercase">Class Billiard Main
                                    Hall</p>
                                <p class="text-gray-400 text-xs mt-1">123 Premium Street, Jakarta, ID</p>
                            </div>
                            <a href="#"
                                class="w-10 h-10 bg-gold-400 rounded-full flex items-center justify-center text-black hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Content (Taking up 7 columns) -->
                <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-12" data-aos="fade-left">

                    <!-- Contact Info -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-serif mb-6 text-white">Contact Info</h3>
                            <ul class="space-y-6">
                                <li class="flex items-start gap-4 group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-black transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Phone</p>
                                        <p class="text-white font-serif text-lg">+62 812 3456 7890</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4 group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-black transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Email</p>
                                        <p class="text-white font-serif text-lg">info@classbilliard.com</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4 group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-black transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Opening Hours
                                        </p>
                                        <p class="text-white font-serif text-lg">Mon - Sun: 10AM - 02AM</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Quick Links & Social -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-serif mb-6 text-white">Quick Links</h3>
                            <ul class="space-y-3">
                                <li><a href="#"
                                        class="text-gray-400 hover:text-gold-400 transition-colors text-sm tracking-wide">Home</a>
                                </li>
                                <li><a href="#about"
                                        class="text-gray-400 hover:text-gold-400 transition-colors text-sm tracking-wide">About
                                        Us</a></li>
                                <li><a href="#events"
                                        class="text-gray-400 hover:text-gold-400 transition-colors text-sm tracking-wide">Events
                                        & Tournaments</a></li>
                                <li><a href="#menu"
                                        class="text-gray-400 hover:text-gold-400 transition-colors text-sm tracking-wide">Food
                                        & Beverage</a></li>
                                <li><a href="#reservation"
                                        class="text-gray-400 hover:text-gold-400 transition-colors text-sm tracking-wide">Book
                                        a Table</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-2xl font-serif mb-6 text-white">Follow Us</h3>
                            <div class="flex gap-4">
                                <a href="#"
                                    class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-400 hover:border-gold-400 hover:text-black transition-all duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-400 hover:border-gold-400 hover:text-black transition-all duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-400 hover:border-gold-400 hover:text-black transition-all duration-300">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Bottom -->
            <div
                class="border-t border-white/10 mt-16 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                        class="w-8 h-8 object-contain opacity-50 grayscale hover:grayscale-0 transition-all">
                    <p class="text-gray-500 text-xs tracking-widest">© 2024 CLASS BILLIARD. ALL RIGHTS RESERVED.</p>
                </div>
                <div class="flex gap-6">
                    <a href="#"
                        class="text-gray-600 hover:text-white text-xs tracking-widest uppercase transition-colors">Privacy
                        Policy</a>
                    <a href="#"
                        class="text-gray-600 hover:text-white text-xs tracking-widest uppercase transition-colors">Terms
                        of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 1000,
            easing: 'ease-out-cubic',
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function () {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-black/90', 'backdrop-blur-md', 'shadow-lg');
                nav.classList.remove('h-24');
                nav.classList.add('h-20');
            } else {
                nav.classList.remove('bg-black/90', 'backdrop-blur-md', 'shadow-lg');
                nav.classList.remove('h-20');
                nav.classList.add('h-24');
            }
        });
    </script>
</body>

</html>