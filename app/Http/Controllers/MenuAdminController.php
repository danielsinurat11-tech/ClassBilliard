<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\CategoryMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuAdminController extends Controller
{
    public function index()
    {
        // Mengambil menu dengan relasi kategorinya
        $menus = Menu::with('categoryMenu')->latest()->paginate(12);

        // AMBIL DATA KATEGORI (Ini yang tadi hilang)
        $categories = CategoryMenu::orderBy('name', 'asc')->get();

        // Kirim keduanya ke view
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = CategoryMenu::orderBy('name', 'asc')->get();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_menu_id' => 'required|exists:category_menus,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric', // Tetap numeric karena kita kirim angka murni
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time() . '-' . Str::slug($request->name) . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/menus'), $imageName);

        $labels = $request->labels ? array_map('trim', explode(',', $request->labels)) : [];

        Menu::create([
            'category_menu_id' => $request->category_menu_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'short_description' => Str::limit(strip_tags($request->description), 120),
            'description' => $request->description,
            'image_path' => 'uploads/menus/' . $imageName,
            'labels' => $labels,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu Berhasil Dibuat!');
    }

    public function edit(Menu $menu)
    {
        // Kita butuh data kategori untuk dropdown di halaman edit
        $categories = CategoryMenu::orderBy('name', 'asc')->get();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Memproses pembaruan data menu
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_menu_id' => 'required|exists:category_menus,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle Array Labels (dari input teks koma)
        if ($request->labels) {
            $data['labels'] = array_map('trim', explode(',', $request->labels));
        }

        // Handle Image Upload jika ada foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama jika perlu (opsional)
            if ($menu->image_path && file_exists(public_path($menu->image_path))) {
                unlink(public_path($menu->image_path));
            }

            $imageName = time() . '-' . $data['slug'] . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/menus'), $imageName);
            $data['image_path'] = 'uploads/menus/' . $imageName;
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu telah dihapus.');
    }
}
