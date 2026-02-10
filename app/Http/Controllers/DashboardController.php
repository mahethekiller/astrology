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
            'total_users' => 1254,
            'total_orders' => 524,
            'total_revenue' => 12850,
            'page_views' => 8520,
            'admin_tasks' => 23,
            'system_health' => '98%'
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

        $system_alerts = [
            ['type' => 'info', 'message' => 'System update scheduled for tonight'],
            ['type' => 'success', 'message' => 'Backup completed successfully'],
        ];

        return view('admin.dashboard', compact('stats', 'recent_orders', 'system_alerts'));
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

    // Astrologer Dashboard
    public function astrologerDashboard()
    {
        $user = Auth::user();

        // Ensure user is an astrologer
        if (!$user->hasRole('astrologer')) {
            abort(403, 'Unauthorized');
        }

        $profile = $user->astrologerProfile;

        $stats = [
            'total_chats' => 0, // Placeholder
            'today_earnings' => 0, // Placeholder
            'rating' => $profile ? $profile->rating : 0,
        ];

        // Fetch recent Chat Requests
        $incomingRequests = [];
        if ($profile) {
            $incomingRequests = \App\Models\ChatRequest::where('astrologer_id', $profile->id)
                ->where('status', 'pending')
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // If no profile, we can't have incoming requests tied to this astrologer
            $incomingRequests = collect();
        }

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
