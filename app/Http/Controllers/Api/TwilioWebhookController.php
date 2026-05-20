<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CallRequest;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    /**
     * Handle Twilio Voice Webhooks (statusCallback)
     */
    public function voiceWebhook(Request $request)
    {
        // Twilio sends CallSid, CallStatus, CallDuration
        $callSid = $request->input('CallSid');
        $callStatus = $request->input('CallStatus');
        $callDuration = $request->input('CallDuration', 0); // in seconds

        Log::info('Twilio Voice Webhook Received', ['CallSid' => $callSid, 'Status' => $callStatus, 'Duration' => $callDuration]);

        if (!$callSid) {
            return response('Missing CallSid', 400);
        }

        // Only process if the call has ended
        if (!in_array($callStatus, ['completed', 'failed', 'busy', 'no-answer', 'canceled'])) {
            return response('Status not final', 200);
        }

        $call = CallRequest::with(['user.wallet', 'astrologer.user.wallet'])
            ->where('twilio_sid', $callSid)
            ->first();

        if (!$call) {
            return response('Call not found in system', 404);
        }

        // If the call is already completed, do nothing
        if ($call->call_status === 'completed' || $call->call_status === 'failed' || $call->call_status === 'canceled') {
            return response('Call already processed', 200);
        }

        // Convert duration to minutes (ceil)
        $durationMinutes = ceil($callDuration / 60);
        if ($durationMinutes < 1) {
            $durationMinutes = 1; // Minimum 1 minute charge usually, or 0 depending on policy. Let's use max(1, $val) if completed, or 0 if failed.
        }

        if ($callStatus !== 'completed') {
            // Failed, busy, no-answer -> mark as failed/canceled and don't charge
            $call->update([
                'call_status' => $callStatus,
                'end_time' => now()
            ]);
            return response('Call marked as ' . $callStatus, 200);
        }

        // Processing Completed Call
        $pricePerMinute = $call->astrologer->call_price ?? 0;
        $commissionPercent = $call->astrologer->call_commission_percentage ?? \App\Models\Setting::getValue('global_voice_commission', 0);

        $totalCost = $durationMinutes * $pricePerMinute;
        $commission = $totalCost * ($commissionPercent / 100);
        $earnings = $totalCost - $commission;

        $userWallet = $call->user->wallet;
        $astroWallet = $call->astrologer->user->wallet;

        DB::beginTransaction();
        try {
            if ($userWallet && $totalCost > 0) {
                // If user doesn't have enough, we still deduct (balance goes negative) or we just deduct what they have.
                // Usually Astro apps stop the call before they run out. If Twilio overshot, we deduct full.
                $userWallet->balance -= $totalCost;
                $userWallet->save();

                WalletTransaction::create([
                    'wallet_id' => $userWallet->id,
                    'amount' => $totalCost,
                    'type' => 'debit',
                    'description' => "Voice call consultation with {$call->astrologer->display_name} ({$durationMinutes} mins)"
                ]);
            }

            if ($astroWallet && $earnings > 0) {
                $astroWallet->balance += $earnings;
                $astroWallet->save();

                WalletTransaction::create([
                    'wallet_id' => $astroWallet->id,
                    'amount' => $earnings,
                    'type' => 'credit',
                    'description' => "Earnings from voice call with {$call->user->name} ({$durationMinutes} mins)"
                ]);
            }

            $call->update([
                'call_status' => 'completed',
                'call_duration' => $durationMinutes,
                'call_cost' => $totalCost,
                'commission_amount' => $commission,
                'astrologer_earnings' => $earnings,
                'end_time' => now()
            ]);

            DB::commit();
            return response('Call processed successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Twilio Voice Webhook Processing Error: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }
}
