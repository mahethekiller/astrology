<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\AstrologerProfile;
use App\Models\ChatRequest;
use App\Models\CallRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'astrologer_profile_id' => 'required|exists:astrologer_profiles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'ratable_type' => 'required|string|in:ChatRequest,CallRequest',
            'ratable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Ensure user is authorized
        $ratableModel = "App\\Models\\" . $request->ratable_type;
        $ratable = $ratableModel::findOrFail($request->ratable_id);

        if ($ratable->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Check if already rated
        $existing = Rating::where('ratable_type', $ratableModel)
            ->where('ratable_id', $request->ratable_id)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'You have already rated this session.'], 422);
        }

        DB::beginTransaction();
        try {
            Rating::create([
                'user_id' => $user->id,
                'astrologer_profile_id' => $request->astrologer_profile_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'ratable_type' => $ratableModel,
                'ratable_id' => $request->ratable_id,
                'status' => 'approved'
            ]);

            // Update Astrologer Profile Cache
            $profile = AstrologerProfile::find($request->astrologer_profile_id);
            $avgRating = Rating::where('astrologer_profile_id', $profile->id)
                ->where('status', 'approved')
                ->avg('rating');
            $totalReviews = Rating::where('astrologer_profile_id', $profile->id)
                ->where('status', 'approved')
                ->count();

            $profile->update([
                'rating' => round($avgRating ?? 0, 1),
                'total_reviews' => $totalReviews
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Thank you for your rating!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to save rating: ' . $e->getMessage()], 500);
        }
    }
}
