@extends('layouts.app')

@section('title', 'Menu - Billiard Class')

@section('content')
    <section style="padding: 0; margin: 0;">
        <div class="find-food-section">
            <div class="find-food-header">
                <h2>Menu Class Billiard</h2>
            </div>
            <div class="menu-category">
                <button class="category-btn active" data-category="all">Semua</button>
                <button class="category-btn" data-category="makanan">Makanan</button>
                <button class="category-btn" data-category="minuman">Minuman</button>
                <button class="category-btn" data-category="cemilan">Cemilan</button>
            </div>
            <div class="menu-items-grid">
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Mie Goreng" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Mie Goreng</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Mie Rebus" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Mie Rebus</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="makanan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Chicken Katsu" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Chicken Katsu Rice</h3>
                            <p class="menu-item-price">Rp30.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Es Kopi Susu" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Es Kopi Susu</h3>
                            <p class="menu-item-price">Rp20.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="minuman">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Teh Manis" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Teh Manis</h3>
                            <p class="menu-item-price">Rp8.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
                <div class="menu-item-card" data-category="cemilan">
                    <div class="menu-item-image-wrapper">
                        <img src="{{ asset('assets/miegoreng.webp') }}" alt="Kentang Goreng" class="menu-item-image">
                    </div>
                    <div class="menu-item-info">
                        <div class="menu-item-header">
                            <h3>Kentang Goreng</h3>
                            <p class="menu-item-price">Rp18.000</p>
                        </div>
                        <p class="menu-item-description">Dengan bumbu spesial, telur, dan sayuran segar</p>
                        <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .find-food-section {
            max-width: 100%;
            margin: 0;
            padding: 2rem 1rem 2rem 0;
        }

        .find-food-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 0 1.5rem 0;
            padding: 0;
        }

        .find-food-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .view-all-link {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .view-all-link:hover {
            color: #0d9488;
            text-decoration: underline;
        }

        .menu-category {
            display: flex;
            flex-direction: row;
            gap: 0.75rem;
            margin: 0 0 1.5rem 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: #000000;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .category-btn:hover {
            background: #e2e8f0;
            color: #040505;
        }

        .category-btn.active {
            background: #fa9a08;
            color: #fff;
        }
    
        .category-btn.active:hover {
            background: #fa9a08;
        }

        .menu-items-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .menu-item-card {
            background: #ffffff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .menu-item-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .menu-item-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem 1rem 1rem 1rem;
        }

        .menu-item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
        }

        .menu-item-info {
            padding: 0 1rem 1rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .menu-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .menu-item-info h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .menu-item-price {
            font-size: 1rem;
            color: #fa9a08;
            font-weight: 600;
            margin: 0;
        }

        .menu-item-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
            line-height: 1.4;
        }

        .add-to-cart-btn {
            align-self: flex-end;
            background: #fa9a08;
            color: #000000;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-top: auto;
        }

        .add-to-cart-btn:hover {
            background: #e19e2b;
        }

        .menu-item-card.hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .find-food-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .menu-category {
                gap: 0.5rem;
            }

            .menu-items-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .menu-item-image {
                width: 100px;
                height: 100px;
            }

            .category-btn {
                padding: 0.55rem 1rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .menu-items-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Category filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const category = btn.dataset.category;
                
                // Filter menu items
                const menuItems = document.querySelectorAll('.menu-item-card');
                menuItems.forEach(item => {
                    if (category === 'all') {
                        item.classList.remove('hidden');
                    } else {
                        if (item.dataset.category === category) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
@endsection


