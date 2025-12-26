<?php

namespace App\Http\Controllers;

use App\Models\CategoryMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
    /**
     * Menampilkan daftar kategori untuk diatur urutannya (UX: Order Priority).
     */
    public function index()
    {
        $categories = CategoryMenu::orderBy('order_priority', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_menus,name',
            'order_priority' => 'required|integer'
        ]);

        CategoryMenu::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order_priority' => $request->order_priority
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update kategori (misal mau ganti nama atau urutan tampil).
     */
    public function update(Request $request, CategoryMenu $categoryMenu)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_menus,name,' . $categoryMenu->id,
            'order_priority' => 'required|integer'
        ]);

        $categoryMenu->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order_priority' => $request->order_priority
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(CategoryMenu $categoryMenu)
    {
        // Karena di migrasi kita pakai onDelete('cascade'), 
        // semua menu di bawah kategori ini akan ikut terhapus otomatis.
        $categoryMenu->delete();
        return back()->with('success', 'Kategori dan semua menu di dalamnya telah dihapus.');
    }
}
