<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use Illuminate\Http\Request;

class AstrologerController extends Controller
{
    /**
     * Get paginated list of active and approved astrologers.
     */
    public function index(Request $request)
    {
        $query = AstrologerProfile::active()
            ->approved()
            ->with(['specializations', 'languages']);

        // Search by name or about
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                    ->orWhere('about', 'like', "%{$search}%");
            });
        }

        // Filter by specialization slug
        if ($request->filled('specialization')) {
            $slug = $request->specialization;
            $query->whereHas('specializations', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $astrologers = $query->paginate($request->get('limit', 12));

        return response()->json([
            'status' => 'success',
            'data' => $astrologers
        ]);
    }

    /**
     * Get detailed information for a specific astrologer.
     */
    public function show($id)
    {
        try {
            $astrologer = AstrologerProfile::active()
                ->approved()
                ->with(['specializations', 'languages', 'ratings.user:id,name,profile_image'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $astrologer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Astrologer not found'
            ], 404);
        }
    }
}
