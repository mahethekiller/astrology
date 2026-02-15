<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallRequest;
use App\Models\ChatRequest;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        $calls = CallRequest::with(['user', 'astrologer'])->latest()->get();
        $chats = ChatRequest::with(['user', 'astrologer'])->latest()->get();

        $totalRevenue = $calls->sum('call_cost') + $chats->sum('chat_cost');
        $totalCommission = $calls->sum('commission_amount') + $chats->sum('commission_amount');
        $totalEarnings = $calls->sum('astrologer_earnings') + $chats->sum('astrologer_earnings');

        return view('admin.revenue.index', compact('calls', 'chats', 'totalRevenue', 'totalCommission', 'totalEarnings'));
    }
}
