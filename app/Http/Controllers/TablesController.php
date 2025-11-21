<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TablesController extends Controller
{
    // General tables page
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin', 'status' => 'active'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Manager', 'status' => 'active'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'User', 'status' => 'pending'],
        ];

        $products = [
            ['name' => 'iPhone 14', 'category' => 'Electronics', 'price' => 999, 'stock' => 45],
            ['name' => 'MacBook Pro', 'category' => 'Electronics', 'price' => 1999, 'stock' => 25],
        ];

        return view('tables', compact('users', 'products'));
    }

    // Admin tables page
    public function adminIndex()
    {
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin', 'status' => 'active', 'last_login' => '2023-05-15'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Manager', 'status' => 'active', 'last_login' => '2023-05-14'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'User', 'status' => 'pending', 'last_login' => '2023-05-10'],
        ];

        $system_logs = [
            ['action' => 'User created', 'user' => 'Admin', 'time' => '2023-05-15 10:30:00'],
            ['action' => 'Role updated', 'user' => 'Admin', 'time' => '2023-05-15 09:15:00'],
        ];

        return view('tables', compact('users', 'system_logs'));
    }

    // Manager tables page
    public function managerIndex()
    {
        $users = [
            ['id' => 1, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'position' => 'Developer', 'projects' => 3, 'performance' => '95%', 'role' => 'Developer', 'status' => 'active'],
            ['id' => 2, 'name' => 'Charlie Wilson', 'email' => 'charlie@example.com', 'position' => 'Designer', 'projects' => 2, 'performance' => '88%', 'role' => 'Designer', 'status' => 'active'],
            ['id' => 3, 'name' => 'David Lee', 'email' => 'david@example.com', 'position' => 'Tester', 'projects' => 4, 'performance' => '91%', 'role' => 'Tester', 'status' => 'active'],
            ['id' => 4, 'name' => 'Eva Garcia', 'email' => 'eva@example.com', 'position' => 'Project Manager', 'projects' => 2, 'performance' => '96%', 'role' => 'Project Manager', 'status' => 'active'],
        ];

        $projects = [
            ['name' => 'Website Redesign', 'status' => 'In Progress', 'deadline' => '2023-06-15', 'progress' => '75%', 'team_lead' => 'Alice Brown'],
            ['name' => 'Mobile App', 'status' => 'Planning', 'deadline' => '2023-07-20', 'progress' => '25%', 'team_lead' => 'Charlie Wilson'],
            ['name' => 'API Development', 'status' => 'Completed', 'deadline' => '2023-05-10', 'progress' => '100%', 'team_lead' => 'David Lee'],
            ['name' => 'Database Migration', 'status' => 'In Progress', 'deadline' => '2023-06-30', 'progress' => '60%', 'team_lead' => 'Eva Garcia'],
        ];

        $pending_approvals = [
            ['type' => 'Leave Request', 'employee' => 'Alice Brown', 'date' => '2023-06-01', 'status' => 'Pending'],
            ['type' => 'Expense Report', 'employee' => 'Charlie Wilson', 'date' => '2023-05-28', 'status' => 'Pending'],
            ['type' => 'Project Budget', 'employee' => 'Eva Garcia', 'date' => '2023-05-25', 'status' => 'Pending'],
        ];

        return view('tables', compact('users', 'projects', 'pending_approvals'));
    }
}
