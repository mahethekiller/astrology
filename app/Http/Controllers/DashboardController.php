<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function toggleTheme(Request $request)
    {
        $currentTheme = $request->cookie('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        return response()->json(['theme' => $newTheme])
            ->cookie('theme', $newTheme, 60*24*30);
    }
}
