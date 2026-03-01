<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::latest()->paginate(10);
        return view('admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog_categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        if ($blogCategory->blogs()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated blogs.');
        }
        $blogCategory->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
