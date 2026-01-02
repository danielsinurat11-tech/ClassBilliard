<?php

namespace App\Http\Controllers;

use App\Models\CategoryMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Menampilkan halaman browsing menu untuk guests (tanpa fitur order)
     */
    public function index()
    {
        // Ambil semua kategori dengan menu yang aktif (tidak dihapus)
        $categories = CategoryMenu::with(['menus' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('order_priority', 'asc')->get();

        return view('menu.index', compact('categories'));
    }

    /**
     * Menampilkan halaman order dengan fitur pemesanan lengkap
     */
    public function createOrder()
    {
        // Ambil semua kategori dengan menu yang aktif (tidak dihapus)
        $categories = CategoryMenu::with(['menus' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('order_priority', 'asc')->get();

        return view('orders.create', compact('categories'));
    }
}

