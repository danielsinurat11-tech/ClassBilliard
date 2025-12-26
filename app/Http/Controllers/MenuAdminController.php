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
        // Menampilkan daftar menu terbaru dengan paginasi (10 item per halaman)
        $menus = Menu::with('categoryMenu')->latest()->paginate(10);
        return view('admin.menus.index', compact('menus'));
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
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses simpan gambar ke public/uploads/menus
        $imageName = time() . '-' . Str::slug($request->name) . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/menus'), $imageName);

        Menu::create([
            'category_menu_id' => $request->category_menu_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'short_description' => Str::limit(strip_tags($request->description), 120),
            'description' => $request->description,
            'image_path' => 'uploads/menus/' . $imageName,
            'labels' => $request->labels, // Disimpan sebagai JSON array
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu Berhasil Dibuat!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu telah dihapus.');
    }
}
