<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{
    public function index()
    {
        $seoMetas = SeoMeta::latest()->get();
        return view('admin.seo.index', compact('seoMetas'));
    }

    public function create()
    {
        return view('admin.seo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url_path' => 'required|string|unique:seo_metas,url_path',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'canonical_url' => 'nullable|url',
        ]);

        $data = $request->all();

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        SeoMeta::create($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO record created successfully.');
    }

    public function edit(SeoMeta $seo)
    {
        return view('admin.seo.edit', compact('seo'));
    }

    public function update(Request $request, SeoMeta $seo)
    {
        $request->validate([
            'url_path' => 'required|string|unique:seo_metas,url_path,' . $seo->id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'canonical_url' => 'nullable|url',
        ]);

        $data = $request->all();

        if ($request->hasFile('og_image')) {
            if ($seo->og_image) {
                Storage::disk('public')->delete($seo->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO record updated successfully.');
    }

    public function destroy(SeoMeta $seo)
    {
        if ($seo->og_image) {
            Storage::disk('public')->delete($seo->og_image);
        }
        $seo->delete();
        return back()->with('success', 'SEO record deleted successfully.');
    }
}
