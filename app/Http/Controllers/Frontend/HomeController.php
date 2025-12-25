<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use Illuminate\Http\Request;

use App\Models\Testimonial;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        $blogs = Blog::latest()->take(6)->get();

        $topAstrologers = AstrologerProfile::latest()->take(6)->get();

        return view('frontend.pages.home', compact('testimonials', 'blogs', 'topAstrologers'));
    }
}
