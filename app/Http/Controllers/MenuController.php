<?php

namespace App\Http\Controllers;

use App\Models\CategoryMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Menampilkan halaman menu dengan data dinamis dari database
     */
    public function index()
    {
        // Ambil semua kategori dengan menu yang aktif (tidak dihapus)
        $categories = CategoryMenu::with(['menus' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('order_priority', 'asc')->get();

        return view('orders.create', compact('categories'));
    }
}

