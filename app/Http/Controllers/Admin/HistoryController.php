<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallRequest;
use App\Models\ChatRequest;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $calls = CallRequest::with(['user', 'astrologer'])->latest()->get();
        $chats = ChatRequest::with(['user', 'astrologer'])->latest()->get();

        return view('admin.history.index', compact('calls', 'chats'));
    }
}
