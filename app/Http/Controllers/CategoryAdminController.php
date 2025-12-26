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
    public function update(Request $request, CategoryMenu $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_menus,name,' . $category->id,
            'order_priority' => 'required|integer'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'order_priority' => $request->order_priority
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    // UBAH: $categoryMenu menjadi $category
    public function destroy(CategoryMenu $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
