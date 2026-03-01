<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::latest();

        if ($request->has('category')) {
            $category = BlogCategory::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $blogs = $query->paginate(12);
        $categories = BlogCategory::where('status', true)->withCount('blogs')->get();
        $recentPosts = Blog::latest()->take(5)->get();

        return view('frontend.pages.blogs', compact('blogs', 'categories', 'recentPosts'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $categories = BlogCategory::where('status', true)->withCount('blogs')->get();
        $recentPosts = Blog::latest()->take(5)->get();

        return view('frontend.pages.blog-details', compact('blog', 'categories', 'recentPosts'));
    }
}
