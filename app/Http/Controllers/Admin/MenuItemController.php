<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Menu $menu)
    {
        $items = $menu->items()->with('children')->whereNull('parent_id')->orderBy('order')->get();
        $parentItems = $menu->items()->whereNull('parent_id')->get();
        return view('admin.menus.items', compact('menu', 'items', 'parentItems'));
    }

    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:route,url',
            'url' => 'required_if:type,url',
            'route' => 'required_if:type,route',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'integer',
        ]);

        $menu->items()->create($request->all());

        return back()->with('success', 'Menu item added successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:route,url',
            'url' => 'required_if:type,url',
            'route' => 'required_if:type,route',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        $menuItem->update($data);

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return back()->with('success', 'Menu item deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        $items = $request->input('order');
        foreach ($items as $index => $id) {
            MenuItem::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['status' => 'success']);
    }
}
