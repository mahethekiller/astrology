<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZodiacSignController extends Controller
{
    public function index()
    {
        $zodiacSigns = \App\Models\ZodiacSign::orderBy('sort_order', 'asc')->get();
        return view('admin.zodiac_signs.index', compact('zodiacSigns'));
    }

    public function create()
    {
        return view('admin.zodiac_signs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:zodiac_signs,slug',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data = $request->except('icon');

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('zodiacs', 'public');
            $data['icon'] = 'storage/' . $path;
        }

        \App\Models\ZodiacSign::create($data);

        return redirect()->route('admin.zodiac-signs.index')->with('success', 'Zodiac Sign created successfully.');
    }

    public function edit(string $id)
    {
        $zodiacSign = \App\Models\ZodiacSign::findOrFail($id);
        return view('admin.zodiac_signs.edit', compact('zodiacSign'));
    }

    public function update(Request $request, string $id)
    {
        $zodiacSign = \App\Models\ZodiacSign::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:zodiac_signs,slug,' . $zodiacSign->id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data = $request->except('icon', '_method', '_token');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('zodiacs', 'public');
            $data['icon'] = 'storage/' . $path;
        }

        $zodiacSign->update($data);

        return redirect()->route('admin.zodiac-signs.index')->with('success', 'Zodiac Sign updated successfully.');
    }

    public function destroy(string $id)
    {
        $zodiacSign = \App\Models\ZodiacSign::findOrFail($id);
        $zodiacSign->delete();

        return redirect()->route('admin.zodiac-signs.index')->with('success', 'Zodiac Sign deleted successfully.');
    }
}
