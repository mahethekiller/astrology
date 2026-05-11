<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRequest;
use App\Models\CallRequest;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Initiate a chat request entry in the database.
     */
    public function requestChat(Request $request)
    {
        $request->validate([
            'astrologer_id' => 'required|exists:astrologer_profiles,id',
            'twilio_sid' => 'nullable|string|max:255'
        ]);

        $chat = ChatRequest::create([
            'user_id' => $request->user()->id,
            'astrologer_id' => $request->astrologer_id,
            'status' => 'pending',
            'twilio_sid' => $request->twilio_sid,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Chat request successfully logged.',
            'data' => [
                'chat_request_id' => $chat->id,
                'status' => $chat->status
            ]
        ]);
    }

    /**
     * Complete a chat session and deduct balance while writing earnings.
     */
    public function endChat(Request $request)
    {
        $request->validate([
            'chat_request_id' => 'required|exists:chat_requests,id',
            'duration_minutes' => 'required|numeric|min:1'
        ]);

        $chat = ChatRequest::with(['user.wallet', 'astrologer.user.wallet'])->findOrFail($request->chat_request_id);

        if ($chat->status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This chat has already been completed.'
            ], 400);
        }

        $duration = $request->duration_minutes;
        $pricePerMinute = $chat->astrologer->chat_price ?? 0;
        $commissionPercent = $chat->astrologer->chat_commission_percentage ?? \App\Models\Setting::getValue('global_chat_commission', 0);

        $totalCost = $duration * $pricePerMinute;
        $commission = $totalCost * ($commissionPercent / 100);
        $earnings = $totalCost - $commission;

        $userWallet = $chat->user->wallet;
        $astroWallet = $chat->astrologer->user->wallet;

        if (!$userWallet || $userWallet->balance < $totalCost) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient user funds.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Deduct User
            $userWallet->balance -= $totalCost;
            $userWallet->save();

            WalletTransaction::create([
                'wallet_id' => $userWallet->id,
                'amount' => $totalCost,
                'type' => 'debit',
                'description' => "Chat consultation with {$chat->astrologer->display_name} ({$duration} mins)"
            ]);

            // Credit Astrologer
            if ($astroWallet) {
                $astroWallet->balance += $earnings;
                $astroWallet->save();

                WalletTransaction::create([
                    'wallet_id' => $astroWallet->id,
                    'amount' => $earnings,
                    'type' => 'credit',
                    'description' => "Earnings from chat session with {$chat->user->name} ({$duration} mins)"
                ]);
            }

            // Update Chat Model
            $chat->update([
                'status' => 'completed',
                'chat_duration' => $duration,
                'chat_cost' => $totalCost,
                'commission_amount' => $commission,
                'astrologer_earnings' => $earnings
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Chat consultation completed successfully.',
                'data' => [
                    'duration' => $duration,
                    'total_cost' => $totalCost,
                    'user_remaining_balance' => $userWallet->balance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Could not process chat completion. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initiate a call request entry in the database.
     */
    public function requestCall(Request $request)
    {
        $request->validate([
            'astrologer_id' => 'required|exists:astrologer_profiles,id',
            'twilio_sid' => 'nullable|string|max:255'
        ]);

        $call = CallRequest::create([
            'user_id' => $request->user()->id,
            'astrologer_id' => $request->astrologer_id,
            'call_status' => 'initiated',
            'twilio_sid' => $request->twilio_sid,
            'start_time' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Call request successfully logged.',
            'data' => [
                'call_request_id' => $call->id,
                'call_status' => $call->call_status,
                'start_time' => $call->start_time
            ]
        ]);
    }

    /**
     * Complete a call session and deduct balance while writing earnings.
     */
    public function endCall(Request $request)
    {
        $request->validate([
            'call_request_id' => 'required|exists:call_requests,id',
            'duration_minutes' => 'required|numeric|min:1'
        ]);

        $call = CallRequest::with(['user.wallet', 'astrologer.user.wallet'])->findOrFail($request->call_request_id);

        if ($call->call_status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This call has already been completed.'
            ], 400);
        }

        $duration = $request->duration_minutes;
        $pricePerMinute = $call->astrologer->call_price ?? 0;
        $commissionPercent = $call->astrologer->call_commission_percentage ?? \App\Models\Setting::getValue('global_voice_commission', 0);

        $totalCost = $duration * $pricePerMinute;
        $commission = $totalCost * ($commissionPercent / 100);
        $earnings = $totalCost - $commission;

        $userWallet = $call->user->wallet;
        $astroWallet = $call->astrologer->user->wallet;

        if (!$userWallet || $userWallet->balance < $totalCost) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient user funds.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Deduct User
            $userWallet->balance -= $totalCost;
            $userWallet->save();

            WalletTransaction::create([
                'wallet_id' => $userWallet->id,
                'amount' => $totalCost,
                'type' => 'debit',
                'description' => "Voice call consultation with {$call->astrologer->display_name} ({$duration} mins)"
            ]);

            // Credit Astrologer
            if ($astroWallet) {
                $astroWallet->balance += $earnings;
                $astroWallet->save();

                WalletTransaction::create([
                    'wallet_id' => $astroWallet->id,
                    'amount' => $earnings,
                    'type' => 'credit',
                    'description' => "Earnings from voice call with {$call->user->name} ({$duration} mins)"
                ]);
            }

            // Update Call Model
            $call->update([
                'call_status' => 'completed',
                'call_duration' => $duration,
                'call_cost' => $totalCost,
                'commission_amount' => $commission,
                'astrologer_earnings' => $earnings
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Call consultation completed successfully.',
                'data' => [
                    'duration' => $duration,
                    'total_cost' => $totalCost,
                    'user_remaining_balance' => $userWallet->balance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Could not process call completion. ' . $e->getMessage()
            ], 500);
        }
    }
}
