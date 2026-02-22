<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    protected $standardSlugs = ['about-us', 'contact-us', 'policy', 'terms-condition', 'careers', 'disclaimer', 'sitemap'];

    public function index()
    {
        // Only show custom pages (those not in the standard list)
        $pages = Page::whereNotIn('slug', $this->standardSlugs)->latest()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function standardPages()
    {
        // Only show standard pages
        $pages = Page::whereIn('slug', $this->standardSlugs)->get();
        return view('admin.pages.standard', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'canonical_url' => 'nullable|url',
        ]);

        if ($request->filled('slug')) {
            $slug = Str::slug($request->slug);
        } else {
            $slug = Str::slug($request->title);
        }

        // Prevent creating a page with a standard slug
        if (in_array($slug, $this->standardSlugs)) {
            return back()->withInput()->with('error', 'This slug is reserved for system use.');
        }

        // Check for uniqueness
        $existing = Page::where('slug', $slug)->first();
        if ($existing) {
            return back()->withInput()->with('error', 'The slug "/' . $slug . '" is already in use.');
        }

        $data = $request->all();
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Custom page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Page $page)
    {
        $isStandard = in_array($page->slug, $this->standardSlugs);
        return view('admin.pages.edit', compact('page', 'isStandard'));
    }

    public function update(Request $request, Page $page)
    {
        $isStandard = in_array($page->slug, $this->standardSlugs);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'canonical_url' => 'nullable|url',
        ]);

        $data = $request->all();

        // Handle Slug Update
        if (!$isStandard) {
            $requestSlug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);

            if ($requestSlug !== $page->slug) {
                if (in_array($requestSlug, $this->standardSlugs)) {
                    return back()->withInput()->with('error', 'This slug is reserved for system use.');
                }

                $existing = Page::where('slug', $requestSlug)->where('id', '!=', $page->id)->first();
                if ($existing) {
                    return back()->withInput()->with('error', 'The slug "/' . $requestSlug . '" is already in use.');
                }
                $data['slug'] = $requestSlug;
            }
        } else {
            // Keep original slug for standard pages
            unset($data['slug']);
        }

        // Handle Banner Image
        if ($request->hasFile('image')) {
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        // Handle OG Image
        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $page->update($data);

        $route = $isStandard ? 'admin.pages.standard' : 'admin.pages.index';
        return redirect()->route($route)->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted successfully.');
    }
}
