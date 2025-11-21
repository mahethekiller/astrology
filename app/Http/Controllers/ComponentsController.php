<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentsController extends Controller
{
    // General components page
    public function index()
    {
        return view('components');
    }

    // Admin components page
    public function adminIndex()
    {
        return view('components');
    }

    // Manager components page
    public function managerIndex()
    {
        return view('components');
    }
}
