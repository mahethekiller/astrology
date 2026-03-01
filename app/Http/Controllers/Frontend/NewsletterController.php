<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'newsletter')->withInput();
        }

        Newsletter::create([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Subscribed successfully!');
    }
}
