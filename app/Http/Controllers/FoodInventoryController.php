<?php

namespace App\Http\Controllers;

use App\Models\FoodInventory;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = FoodInventory::with('menu')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $menus = Menu::orderBy('name')->get();

        return view('admin.inventory.index', compact('inventories', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id|unique:food_inventories,menu_id',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $inventory = FoodInventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory untuk ' . $inventory->menu->name . ' berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodInventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $validated['last_restocked_at'] = now();
        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory ' . $inventory->menu->name . ' berhasil diperbarui');
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(FoodInventory $inventory)
    {
        $menuName = $inventory->menu->name;
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory ' . $menuName . ' berhasil dihapus');
    }

    /**
     * Get inventory status (for API)
     */
    public function getStatus(Menu $menu)
    {
        $inventory = FoodInventory::where('menu_id', $menu->id)->first();
        
        if (!$inventory) {
            return response()->json(['status' => 'unknown']);
        }

        return response()->json([
            'status' => $inventory->isInStock() ? 'in_stock' : 'out_of_stock',
            'quantity' => $inventory->quantity,
            'below_reorder' => $inventory->isBelowReorderLevel(),
        ]);
    }
}
