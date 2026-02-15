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

        $calls = CallRequest::where('astrologer_id', $astrologer->id)
            ->where('call_status', 'completed')
            ->latest()
            ->get();

        $chats = ChatRequest::where('astrologer_id', $astrologer->id)
            ->where('status', 'completed')
            ->latest()
            ->get();

        $totalEarnings = $calls->sum('astrologer_earnings') + $chats->sum('astrologer_earnings');
        $totalSessions = $calls->count() + $chats->count();

        return view('astrologer.revenue.index', compact('calls', 'chats', 'totalEarnings', 'totalSessions'));
    }
}
