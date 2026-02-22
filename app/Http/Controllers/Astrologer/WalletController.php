<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Show the astrologer's wallet.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure wallet exists
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }

        $transactions = $wallet->transactions()->latest()->paginate(15);

        return view('astrologer.wallet.index', compact('wallet', 'transactions'));
    }
}
