<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('wallet');

        // Ensure wallet exists (backward compatibility for existing users)
        if (!$user->wallet) {
            $user->wallet()->create(['balance' => 0]);
            $user->load('wallet');
        }

        return view('frontend.wallet.index', compact('user'));
    }

    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if (!$user->wallet) {
            $user->wallet()->create(['balance' => 0]);
        }

        $amount = $request->amount;

        // In a real app, this would be a Payment Gateway callback.
        // For now, we instantly credit.

        $user->wallet->increment('balance', $amount);

        $user->wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => 'Added money to wallet',
        ]);

        return redirect()->back()->with('success', 'Money added successfully!');
    }
}
