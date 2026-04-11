<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    /**
     * Get only the wallet balance for the authenticated user.
     */
    public function balance(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet not found for this user.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'wallet_balance' => $wallet->balance
            ]
        ]);
    }

    /**
     * Get paginated transactions for the authenticated user's wallet.
     */
    public function transactions(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet not found for this user.'
            ], 404);
        }

        $query = WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate($request->get('limit', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'wallet_balance' => $wallet->balance,
                'transactions' => $transactions
            ]
        ]);
    }

    /**
     * Add funds to the authenticated user's wallet.
     */
    public function addFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet not found for this user.'
            ], 404);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $wallet->balance += $request->amount;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $request->amount,
                'type' => 'credit',
                'description' => $request->description ?? 'Added funds via API',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Funds added successfully',
                'data' => [
                    'wallet_balance' => $wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add funds. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deduct funds from the authenticated user's wallet.
     */
    public function deductFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet not found for this user.'
            ], 404);
        }

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient funds.'
            ], 400);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $wallet->balance -= $request->amount;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $request->amount,
                'type' => 'debit',
                'description' => $request->description ?? 'Deducted funds via API',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Funds deducted successfully',
                'data' => [
                    'wallet_balance' => $wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to deduct funds. ' . $e->getMessage()
            ], 500);
        }
    }
}
