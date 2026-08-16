<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRequest;
use App\Models\CallRequest;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $user = $request->user();

        if ($user->isAstrologer()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Astrologers cannot request consultations.'
            ], 403);
        }

        $astrologer = \App\Models\AstrologerProfile::findOrFail($request->astrologer_id);

        if (!$astrologer->is_online || !$astrologer->is_chat_online) {
            return response()->json([
                'status' => 'error',
                'message' => 'Astrologer is currently offline for chat.'
            ], 400);
        }

        $chatPrice = $astrologer->chat_price ?? 0;
        $userWallet = $user->wallet;

        if (!$userWallet || $userWallet->balance < $chatPrice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient wallet balance.'
            ], 400);
        }

        $chat = ChatRequest::create([
            'user_id' => $user->id,
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
     * Check the status of a chat request.
     */
    public function checkChatStatus(Request $request, $id)
    {
        $chat = ChatRequest::findOrFail($id);

        // Ensure user is authorized
        if ($chat->user_id !== $request->user()->id && $chat->astrologer->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'chat_request_id' => $chat->id,
                'status' => $chat->status,
                'twilio_sid' => $chat->twilio_sid,
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

        $user = $request->user();

        if ($user->isAstrologer()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Astrologers cannot request consultations.'
            ], 403);
        }

        $astrologer = \App\Models\AstrologerProfile::findOrFail($request->astrologer_id);

        if (!$astrologer->is_online || !$astrologer->is_call_online) {
            return response()->json([
                'status' => 'error',
                'message' => 'Astrologer is currently offline for calls.'
            ], 400);
        }

        $callPrice = $astrologer->call_price ?? 0;
        $userWallet = $user->wallet;

        if (!$userWallet || $userWallet->balance < $callPrice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient wallet balance.'
            ], 400);
        }

        $call = CallRequest::create([
            'user_id' => $user->id,
            'astrologer_id' => $request->astrologer_id,
            'call_status' => 'initiated',
            'twilio_sid' => $request->twilio_sid,
            'start_time' => now()
        ]);

        Log::channel('calls')->info("Call Request Initiated via API: Call ID {$call->id}, User {$user->id}, Astrologer {$request->astrologer_id}, Twilio SID: " . ($request->twilio_sid ?? 'none'));

        $status = $call->call_status;
        if ($status === 'in-progress') {
            $status = 'accepted';
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Call request successfully logged.',
            'data' => [
                'call_request_id' => $call->id,
                'call_status' => $call->call_status,
                'status' => $status,
                'start_time' => $call->start_time
            ]
        ]);
    }

    /**
     * Check the status of a call request.
     */
    public function checkCallStatus(Request $request, $id)
    {
        $call = CallRequest::findOrFail($id);

        // Ensure user is authorized
        if ($call->user_id !== $request->user()->id && $call->astrologer->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
        }

        $status = $call->call_status;
        if ($status === 'in-progress') {
            $status = 'accepted';
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'call_request_id' => $call->id,
                'call_status' => $call->call_status,
                'status' => $status,
                'twilio_sid' => $call->twilio_sid,
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

            Log::channel('calls')->info("Call Request Completed via API: Call ID {$call->id}, Duration {$duration} mins, Cost {$totalCost}, User Balance remaining: {$userWallet->balance}");

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

            Log::channel('calls')->error("Error completing call via API: Call ID {$call->id}, Error: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Could not process call completion. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for a chat request.
     */
    public function getMessages($id)
    {
        $user = auth()->user();
        $chatRequest = \App\Models\ChatRequest::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('astrologer', function($astroQ) use ($user) {
                      $astroQ->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        $messages = \App\Models\ChatMessage::where('chat_request_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    /**
     * Send a message for a chat request.
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $user = auth()->user();
        $chatRequest = \App\Models\ChatRequest::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('astrologer', function($astroQ) use ($user) {
                      $astroQ->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        $senderIdentity = 'user_' . $user->id;
        if ($chatRequest->astrologer && $chatRequest->astrologer->user_id == $user->id) {
            $senderIdentity = 'astrologer_' . $chatRequest->astrologer->id;
        }

        $message = \App\Models\ChatMessage::create([
            'chat_request_id' => $id,
            'sender_identity' => $senderIdentity,
            'body' => $request->body
        ]);

        // Auto-reply for simulated testing purposes (if the user is chatting)
        if (str_starts_with($senderIdentity, 'user_')) {
            $responses = [
                "Namaste. I am looking at your birth chart. I see Jupiter transiting your 10th house, indicating positive changes in career soon.",
                "Let me analyze your planetary alignments. What is your primary question regarding love or career?",
                "The Saturn transit suggests some patience is needed right now. Performing daily meditation will help.",
                "The cosmic energies will align in your favor soon. Trust the timing of your life."
            ];
            $replyText = $responses[array_rand($responses)];

            \App\Models\ChatMessage::create([
                'chat_request_id' => $id,
                'sender_identity' => 'astrologer_' . $chatRequest->astrologer_id,
                'body' => $replyText
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $message
        ]);
    }
}
