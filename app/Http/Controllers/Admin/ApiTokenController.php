<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the tokens.
     */
    public function index()
    {
        $tokens = Auth::user()->tokens;
        return view('admin.api-tokens.index', compact('tokens'));
    }

    /**
     * Store a newly created token in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255',
        ]);

        $tokenName = $request->token_name;
        $token = Auth::user()->createToken($tokenName);

        // Flash the token to the session so it can be displayed once
        return redirect()->route('admin.api-tokens.index')
            ->with('success', 'Token created successfully!')
            ->with('new_token', $token->plainTextToken);
    }

    /**
     * Remove the specified token from storage.
     */
    public function destroy($id)
    {
        Auth::user()->tokens()->where('id', $id)->delete();

        return redirect()->route('admin.api-tokens.index')
            ->with('success', 'Token revoked successfully!');
    }
}
