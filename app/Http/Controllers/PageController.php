<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 1)->firstOrFail();

        // Try to find a specific view for this slug
        $view = "frontend.pages.{$slug}";
        if (view()->exists($view)) {
            return view($view, compact('page'));
        }

        return view('frontend.pages.show', compact('page'));
    }
}
