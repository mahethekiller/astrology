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

        $topAstrologers = AstrologerProfile::active()->approved()->latest()->take(6)->get();
        $zodiacSigns = \App\Models\ZodiacSign::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('frontend.pages.home', compact('testimonials', 'blogs', 'topAstrologers', 'zodiacSigns'));
    }
}
