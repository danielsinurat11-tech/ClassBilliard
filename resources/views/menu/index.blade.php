<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Billiard - Create Order</title>
    <!-- Google Font Barlow -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.min.css" rel="stylesheet">
    <!-- Tailwind CSS 4.0 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-bg-dark: #0a0a0a;
            --color-bg-sidebar: #1a1a1a;
            --color-primary: #FFD700;
            --color-text-gray: #abbbc2;
            --color-border-base: #393c49;
            --font-barlow: "Barlow", sans-serif;
        }

        body {
            background-color: var(--color-bg-dark);
            color: white;
            font-family: var(--font-barlow);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #393c49; border-radius: 10px; }
    </style>
</head>

<body class="antialiased">

    <div class="flex h-screen w-full overflow-hidden">

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto overflow-x-auto transition-all duration-300"
            style="width: calc(100% - 0px); padding-top: 8rem;" id="mainContent">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-0 px-4 md:px-8 fixed top-0 left-0 right-0 bg-bg-dark z-30 py-4"
                style="width: calc(100% - 0px); transition: width 0.3s ease;">
                <div class="min-w-0">
                    <h1 class="text-2xl md:text-3xl font-semibold mb-1">Class Billiard</h1>
                    <p class="text-text-gray font-medium text-xs md:text-base">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</p>
                </div>
                <div class="relative w-full md:w-72">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </span>
                    <input type="text" id="searchInput" placeholder="Search for food, menu, etc.."
                        class="w-full bg-bg-sidebar border border-border-base rounded-lg py-2.5 md:py-3 pl-10 pr-4 text-xs md:text-sm focus:outline-none focus:border-primary placeholder:text-text-gray">
                </div>
            </header>

            <!-- Tabs Navigation -->
            <div class="flex gap-8 border-b border-border-base mb-8 overflow-x-auto no-scrollbar px-8 fixed top-24 left-0 right-0 bg-bg-dark z-20" style="width: calc(100% - 0px); transition: width 0.3s ease;">
                <button
                    class="category-tab pb-3 text-primary border-b-2 border-primary font-semibold text-sm whitespace-nowrap"
                    data-category="all">All</button>
                @foreach($categories as $category)
                    <button
                        class="category-tab pb-3 text-white font-semibold text-sm opacity-60 hover:opacity-100 whitespace-nowrap"
                        data-category="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>

            <!-- Section Title -->
            <div class="flex justify-between items-center mb-16 px-8 pt-8">
                <h2 class="text-xl font-semibold">Choose Dishes</h2>
            </div>

            <!-- Grid of Dishes (Fixed card size, responsive grid) -->
            <div class="px-8 pb-20">
                <div class="grid gap-x-6 gap-y-12" id="menuGrid" style="grid-template-columns: repeat(5, 240px);">
                    @php
                        $allMenus = collect();
                        foreach ($categories as $category) {
                            foreach ($category->menus as $menu) {
                                $allMenus->push((object) [
                                    'menu' => $menu,
                                    'category' => $category
                                ]);
                            }
                        }
                        $urlParams = request()->query();
                        $hasBarcodeParams = isset($urlParams['table']) || isset($urlParams['room']) || isset($urlParams['order_id']);
                    @endphp

                    @forelse($allMenus as $item)
                        @php
                            $menu = $item->menu;
                            $category = $item->category;
                        @endphp
                        <div class="menu-card bg-bg-sidebar rounded-xl pt-16 pb-4 px-4 text-center relative group cursor-pointer"
                            data-category="{{ $category->slug }}" data-name="{{ $menu->name }}"
                            data-price="{{ $menu->price }}"
                            data-image="{{ $menu->image_path ? asset($menu->image_path) : '' }}"
                            data-label="{{ $menu->labels ?? '' }}">
                            <img src="{{ $menu->image_path ? asset($menu->image_path) : 'https://via.placeholder.com/400' }}"
                                alt="{{ $menu->name }}"
                                class="w-48 h-48 rounded-full mx-auto -mt-36 object-cover group-hover:scale-105 transition-transform">
                            <h3 class="text-[15px] font-medium mb-2 px-4 -mt-6 leading-snug line-clamp-2">{{ $menu->name }}</h3>
                            @if($menu->short_description)
                                <p class="text-xs text-text-gray mb-2 px-2 line-clamp-2">{{ $menu->short_description }}</p>
                            @endif
                            <p class="text-sm mb-3 font-medium">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                            <div class="flex gap-2 items-center justify-between">
                                <div class="flex gap-1 flex-wrap flex-1">
                                    @if($menu->labels)
                                        @php
                                            // Handle both JSON array and string formats
                                            $labelText = $menu->labels;
                                            if (str_starts_with($labelText, '[')) {
                                                $labelText = json_decode($labelText, true)[0] ?? $labelText;
                                            }
                                            $label = strtolower($labelText);
                                            if(strpos($label, 'best seller') !== false || strpos($label, 'rekomendasi') !== false) {
                                                $bgClass = 'bg-primary/20';
                                                $textClass = 'text-primary';
                                            } elseif(strpos($label, 'new') !== false || strpos($label, 'baru') !== false) {
                                                $bgClass = 'bg-emerald-500/20';
                                                $textClass = 'text-emerald-500';
                                            } else {
                                                $bgClass = 'bg-red-500/20';
                                                $textClass = 'text-red-500';
                                            }
                                        @endphp
                                        <span class="text-xs px-2 py-0.5 rounded {{ $bgClass }} {{ $textClass }}">{{ $labelText }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-text-gray">
                            <p>Belum ada menu yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

    </div>

    <script>
        // Category filtering only
        document.querySelectorAll('.category-tab').forEach(btn => {
            btn.addEventListener('click', function () {
                const category = this.getAttribute('data-category');

                // Update tab active state
                document.querySelectorAll('.category-tab').forEach(b => {
                    b.classList.remove('text-primary', 'border-b-2', 'border-primary');
                    b.classList.add('opacity-60');
                });
                this.classList.add('text-primary', 'border-b-2', 'border-primary');
                this.classList.remove('opacity-60');

                // Filter cards
                document.querySelectorAll('.menu-card').forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function () {
            const search = this.value.toLowerCase();
            document.querySelectorAll('.menu-card').forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                card.style.display = name.includes(search) ? 'block' : 'none';
            });
        });
    </script>

</body>

</html>