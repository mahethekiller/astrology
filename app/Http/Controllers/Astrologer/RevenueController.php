<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\CallRequest;
use App\Models\ChatRequest;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $astrologer = AstrologerProfile::where('user_id', $userId)->firstOrFail();

        // Base queries
        $callQuery = CallRequest::with('user')->where('astrologer_id', $astrologer->id)->where('call_status', 'completed');
        $chatQuery = ChatRequest::with('user')->where('astrologer_id', $astrologer->id)->where('status', 'completed');

        $calls = $callQuery->latest()->get();
        $chats = $chatQuery->latest()->get();

        // Calculate Totals
        $totalEarnings = $calls->sum('astrologer_earnings') + $chats->sum('astrologer_earnings');
        $totalSessions = $calls->count() + $chats->count();

        // Periodic Stats
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        $todayEarnings = $callQuery->clone()->where('created_at', '>=', $today)->sum('astrologer_earnings') +
            $chatQuery->clone()->where('created_at', '>=', $today)->sum('astrologer_earnings');

        $weeklyEarnings = $callQuery->clone()->where('created_at', '>=', $thisWeek)->sum('astrologer_earnings') +
            $chatQuery->clone()->where('created_at', '>=', $thisWeek)->sum('astrologer_earnings');

        $monthlyEarnings = $callQuery->clone()->where('created_at', '>=', $thisMonth)->sum('astrologer_earnings') +
            $chatQuery->clone()->where('created_at', '>=', $thisMonth)->sum('astrologer_earnings');

        return view('astrologer.revenue.index', compact(
            'calls',
            'chats',
            'totalEarnings',
            'totalSessions',
            'todayEarnings',
            'weeklyEarnings',
            'monthlyEarnings'
        ));
    }
}
