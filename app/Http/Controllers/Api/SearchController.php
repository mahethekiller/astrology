<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\Blog;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search across astrologers and blogs
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'astrologers' => [],
                    'blogs' => []
                ]
            ]);
        }

        // Search Astrologers (assuming relationship or model exists)
        // We will fetch up to 10 matching astrologers
        $astrologers = AstrologerProfile::whereHas('user', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })->orWhere('display_name', 'like', "%{$query}%")
            ->orWhere('about', 'like', "%{$query}%")
            ->with(['user', 'specializations', 'languages'])
            ->limit(10)
            ->get();

        // Search Blogs
        $blogs = Blog::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'astrologers' => $astrologers,
                'blogs' => $blogs
            ]
        ]);
    }
}
