<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // General dashboard (fallback)
    public function index()
    {
        $stats = [
            'total_users' => 1254,
            'total_orders' => 524,
            'total_revenue' => 12850,
            'page_views' => 8520,
        ];

        $recent_orders = [
            [
                'id' => 'ORD-001',
                'customer' => 'John Doe',
                'date' => '2023-05-15',
                'amount' => 245.99,
                'status' => 'completed'
            ],
            [
                'id' => 'ORD-002',
                'customer' => 'Jane Smith',
                'date' => '2023-05-14',
                'amount' => 189.50,
                'status' => 'pending'
            ],
        ];

        return view('dashboard', compact('stats', 'recent_orders'));
    }

    // Admin Dashboard
    public function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::role('user')->count(),
            'total_astrologers' => \App\Models\AstrologerProfile::count(),
            'total_revenue' => \App\Models\ChatRequest::sum('commission_amount') + \App\Models\CallRequest::sum('commission_amount'),
            'pending_verifications' => \App\Models\AstrologerProfile::where('verification_status', 'pending')->count(),
        ];

        // Chart Data: Last 6 Months Revenue
        $months = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $monthRevenue = \App\Models\ChatRequest::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('commission_amount') +
                \App\Models\CallRequest::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('commission_amount');

            $revenueData[] = $monthRevenue;
        }

        // Recent Activity
        $recent_consultations = \App\Models\ChatRequest::with('user', 'astrologer')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'months', 'revenueData', 'recent_consultations'));
    }

    // Manager Dashboard
    public function managerDashboard()
    {
        $stats = [
            'team_members' => 15,
            'projects' => 8,
            'completed_tasks' => 45,
            'pending_approvals' => 3,
            'team_performance' => '87%'
        ];

        $recent_activities = [
            ['type' => 'task', 'message' => 'Project Alpha completed', 'time' => '2 hours ago'],
            ['type' => 'approval', 'message' => 'Budget request needs approval', 'time' => '4 hours ago'],
            ['type' => 'meeting', 'message' => 'Team sync meeting at 3 PM', 'time' => '6 hours ago'],
        ];

        return view('manager.dashboard', compact('stats', 'recent_activities'));
    }

    // User Dashboard
    public function userDashboard()
    {
        $stats = [
            'my_tasks' => 5,
            'completed' => 12,
            'pending' => 3,
            'performance' => '92%'
        ];

        $my_tasks = [
            ['name' => 'Design homepage', 'due_date' => '2023-05-20', 'priority' => 'high'],
            ['name' => 'Write documentation', 'due_date' => '2023-05-25', 'priority' => 'medium'],
            ['name' => 'Code review', 'due_date' => '2023-05-18', 'priority' => 'low'],
        ];

        return view('user.dashboard', compact('stats', 'my_tasks'));
    }

    public function astrologerDashboard()
    {
        $user = Auth::user();

        // Ensure user is an astrologer
        if (!$user->hasRole('astrologer')) {
            abort(403, 'Unauthorized');
        }

        $profile = $user->astrologerProfile;

        if (!$profile) {
            return view('astrologer.dashboard', [
                'stats' => ['total_consultations' => 0, 'today_earnings' => 0, 'rating' => 0, 'total_earnings' => 0],
                'incomingRequests' => collect()
            ]);
        }

        // Calculate Stats
        $today = now()->startOfDay();

        $todayChatEarnings = \App\Models\ChatRequest::where('astrologer_id', $profile->id)
            ->whereDate('created_at', $today)
            ->sum('astrologer_earnings');

        $todayCallEarnings = \App\Models\CallRequest::where('astrologer_id', $profile->id)
            ->whereDate('created_at', $today)
            ->sum('astrologer_earnings');

        $totalChatConsultations = \App\Models\ChatRequest::where('astrologer_id', $profile->id)
            ->where('status', 'completed')
            ->count();

        $totalCallConsultations = \App\Models\CallRequest::where('astrologer_id', $profile->id)
            ->where('call_status', 'completed')
            ->count();

        $totalEarnings = \App\Models\ChatRequest::where('astrologer_id', $profile->id)->sum('astrologer_earnings') +
            \App\Models\CallRequest::where('astrologer_id', $profile->id)->sum('astrologer_earnings');

        $stats = [
            'total_consultations' => $totalChatConsultations + $totalCallConsultations,
            'today_earnings' => $todayChatEarnings + $todayCallEarnings,
            'rating' => $profile->rating,
            'total_earnings' => $totalEarnings,
            'total_chats' => $totalChatConsultations,
            'total_calls' => $totalCallConsultations,
        ];

        // Fetch recent Chat Requests
        $incomingRequests = \App\Models\ChatRequest::where('astrologer_id', $profile->id)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('astrologer.dashboard', compact('stats', 'incomingRequests'));
    }

    public function getPendingRequests()
    {
        $user = Auth::user();
        if (!$user->astrologerProfile) {
            return response()->json(['error' => 'Not Authorized'], 403);
        }

        $profile = $user->astrologerProfile;

        // Fetch pending chat requests
        $chatRequests = \App\Models\ChatRequest::where('astrologer_id', $profile->id)
            ->where('status', 'pending')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'phone_number');
                }
            ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'user_name' => $request->user->name,
                    'user_phone' => $request->user->phone_number,
                    'user_initial' => strtoupper(substr($request->user->name, 0, 1)),
                    'created_at_human' => $request->created_at->diffForHumans(),
                    'accept_url' => route('astrologer.chat.accept', $request->id),
                    'reject_url' => route('astrologer.chat.reject', $request->id),
                ];
            });

        // Fetch pending call requests
        $callRequests = \App\Models\CallRequest::where('astrologer_id', $profile->id)
            ->where('call_status', 'pending')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'phone_number');
                }
            ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'user_name' => $request->user->name,
                    'user_phone' => $request->user->phone_number,
                    'user_initial' => strtoupper(substr($request->user->name, 0, 1)),
                    'created_at_human' => $request->created_at->diffForHumans(),
                    // 'accept_url' => route('astrologer.call.accept', $request->id), // Adjust as needed
                    // 'reject_url' => route('astrologer.call.reject', $request->id),
                ];
            });

        return response()->json([
            'chatRequests' => $chatRequests,
            'callRequests' => $callRequests,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function toggleTheme(Request $request)
    {
        $currentTheme = $request->cookie('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        return response()->json(['theme' => $newTheme])
            ->cookie('theme', $newTheme, 60 * 24 * 30);
    }
}
