<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\CallRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    private $twilioSid;
    private $twilioApiKey;
    private $twilioApiSecret;
    private $twilioVoiceAppSid;
    private $twilioTrial;

    public function __construct()
    {
        $this->twilioSid = config('services.twilio.sid');
        $this->twilioApiKey = config('services.twilio.chat.api_key'); // Reusing Chat API Key if valid, else need separate
        $this->twilioApiSecret = config('services.twilio.chat.api_secret');
        $this->twilioVoiceAppSid = config('services.twilio.voice.app_sid'); // Must be set in config
        $this->twilioTrial = config('services.twilio.trial', false);
    }

    public function token(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            // If not logged in user (maybe astrologer via different guard? or same table)
            // For now assuming User logic handles Astrologers too since Astrologer extends/Link to User
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Identify if User or Astrologer
        // In this system, Astrologers are Users too.
        // We'll use a prefix. `user_{id}` or if they are ON the astrologer dashboard, `astrologer_{id}`?
        // Let's rely on the requested identity type.

        $identityPrefix = 'user';
        if ($request->has('is_astrologer') && $request->is_astrologer) {
            // Verify this user IS an astrologer
            if (!$user->isAstrologer()) {
                return response()->json(['error' => 'Not an astrologer'], 403);
            }
            $identityPrefix = 'astrologer';
        }

        $identity = $identityPrefix . '_' . $user->id;

        if (empty($this->twilioVoiceAppSid)) {
            return response()->json(['error' => 'Twilio Voice App SID not configured.'], 500);
        }

        $token = new AccessToken(
            $this->twilioSid,
            $this->twilioApiKey,
            $this->twilioApiSecret,
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($this->twilioVoiceAppSid);
        $voiceGrant->setIncomingAllow(true); // Allow incoming calls

        $token->addGrant($voiceGrant);

        return response()->json(['token' => $token->toJWT(), 'identity' => $identity]);
    }

    public function voiceCallback(Request $request)
    {
        Log::info('Twilio Voice Callback Hit', $request->all());

        $response = new VoiceResponse();

        try {
            // request header 'From' -> client:user_{id}
            // request body 'astrologer_id' -> passed from client

            $from = $request->input('From'); // e.g., client:user_1 or client:astrologer_2
            $toAstrologerId = $request->input('astrologer_id');

            Log::info("Voice Callback - Raw From: $from, Raw Astrologer ID: $toAstrologerId");

            if (!$toAstrologerId) {
                Log::error('Voice Callback - No Astrologer ID provided in request');
                $response->say('Invalid request. No Astrologer ID provided.');
                return response($response->asXML())->header('Content-Type', 'text/xml');
            }

            // Parse User ID robustly
            // 1. Remove 'client:' if present
            // 2. Remove 'user_' or 'astrologer_' if present
            $cleanFrom = preg_replace('/^(client:)?(user_|astrologer_)?/', '', $from);
            $userId = $cleanFrom;

            Log::info("Voice Callback - Parsed User ID: $userId, To Astrologer ID: $toAstrologerId");

            $user = User::find($userId);

            // Try finding astrologer profile by ID first
            $astrologer = AstrologerProfile::find($toAstrologerId);

            // Fallback: If not found, check if the ID passed was actually the USER ID of the astrologer
            if (!$astrologer) {
                Log::warning("Astrologer not found by ID ($toAstrologerId), trying as user_id");
                $astrologer = AstrologerProfile::where('user_id', $toAstrologerId)->first();
            }

            if (!$user || !$astrologer) {
                $missing = !$user ? 'User' : 'Astrologer';
                if (!$user && !$astrologer)
                    $missing = 'User and Astrologer';

                Log::error("Voice Callback - $missing not found. UserID: $userId, AstrologerID: $toAstrologerId");

                $response->say("Sorry, your profile or the astrologer could not be identified. Please try again.");
                return response($response->asXML())->header('Content-Type', 'text/xml');
            }

            // Check Balance
            // 1. Ensure wallet
            if (!$user->wallet) {
                Log::error("User $userId has no wallet");
                $response->say('Insufficient balance.');
                return response($response->asXML())->header('Content-Type', 'text/xml');
            }

            $pricePerMin = $astrologer->call_price;

            // Log price and balance
            Log::info("Price: $pricePerMin, Balance: " . $user->wallet->balance);

            $maxTwilioLimit = $this->twilioTrial ? 600 : 14400;

            if ($pricePerMin <= 0) {
                // Free call? Or not allowed? Assuming allowed.
                $timeLimit = min(3600, $maxTwilioLimit); // cap by trial limit
            } else {
                $balance = $user->wallet->balance;
                // Safeguard against division by zero (already checked <=0 but good to cover)
                $maxDuration = floor(($balance / ($pricePerMin > 0 ? $pricePerMin : 1)) * 60);

                if ($maxDuration < 60) {
                    $response->say('You have insufficient balance for this call.');
                    return response($response->asXML())->header('Content-Type', 'text/xml');
                }
                // Twilio max timeLimit is 4 hours (14400s) or 10 mins (600s) for trial. Ensure reasonable int.
                $timeLimit = (int) min($maxDuration, $maxTwilioLimit);
            }

            // Create DB Record
            Log::info("Creating CallRequest for User $userId and Astrologer $toAstrologerId");
            $callRequest = CallRequest::create([
                'user_id' => $user->id,
                'astrologer_id' => $astrologer->id,
                'twilio_sid' => $request->input('CallSid'),
                'call_status' => 'initiated',
                'start_time' => now(),
                'call_cost' => 0 // Calculated at end
            ]);

            $dial = $response->dial('', [
                'timeLimit' => $timeLimit,
                'action' => route('call.status'), // Webhook when call ends
                'method' => 'POST'
            ]);

            // Client identity: astrologer_{user_id_of_astrologer}
            // Wait, AstrologerProfile has user_id.
            $astrologerIdentity = 'astrologer_' . $astrologer->user_id;
            Log::info("Dialing client identity: $astrologerIdentity");

            $dial->client($astrologerIdentity);

            return response($response->asXML())->header('Content-Type', 'text/xml');

        } catch (\Exception $e) {
            Log::error('Voice Callback Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            $response = new VoiceResponse(); // Reset response to say error
            $response->say('An internal error occurred: ' . $e->getMessage());
            return response($response->asXML())->header('Content-Type', 'text/xml');
        }
    }

    public function toggleOnlineStatus(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->isAstrologer()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'is_online' => 'required|boolean'
        ]);

        $profile = $user->astrologerProfile;
        if (!$profile) {
            return response()->json(['error' => 'Astrologer profile not found'], 404);
        }

        $isOnline = $request->input('is_online');

        $profile->update([
            'is_call_online' => $isOnline,
            'is_online' => $isOnline // Sync general online status for now
        ]);

        return response()->json([
            'success' => true,
            'is_call_online' => $profile->is_call_online
        ]);
    }

    /**
     * Billing Ping - Called every minute during the call from the frontend
     */
    public function billingPing(Request $request)
    {
        $request->validate([
            'sid' => 'required'
        ]);

        $user = Auth::user();
        $callRequest = CallRequest::where('twilio_sid', $request->sid)
            ->where('user_id', $user->id)
            ->first();

        if (!$callRequest) {
            return response()->json(['error' => 'Call sequence not found'], 404);
        }

        $astrologer = $callRequest->astrologer;
        $price = $astrologer->call_price;

        if ($price > 0) {
            if ($user->wallet->balance < $price) {
                return response()->json(['status' => 'low_balance'], 402);
            }

            // 1. Deduct from User
            $user->wallet->decrement('balance', (float) $price);
            $user->wallet->transactions()->create([
                'amount' => $price,
                'type' => 'debit',
                'description' => "Voice Call usage charge with {$astrologer->display_name} (1 min)",
                'metadata' => ['twilio_sid' => $request->sid]
            ]);

            // 2. Financial Calculations
            $commissionRate = $astrologer->call_commission_percentage ?? \App\Models\Setting::getValue('global_voice_commission', 20);
            $commissionAmount = round(($price * $commissionRate) / 100, 2);
            $astrologerEarnings = $price - $commissionAmount;

            // 3. Update Call Request
            $callRequest->increment('call_duration', 60); // Track in seconds
            $callRequest->increment('call_cost', (float) $price);
            $callRequest->increment('commission_amount', (float) $commissionAmount);
            $callRequest->increment('astrologer_earnings', (float) $astrologerEarnings);

            // 4. Credit Astrologer
            $astrologerUser = $astrologer->user;
            if ($astrologerUser && $astrologerUser->wallet) {
                $astrologerUser->wallet->increment('balance', (float) $astrologerEarnings);
                $astrologerUser->wallet->transactions()->create([
                    'amount' => $astrologerEarnings,
                    'type' => 'credit',
                    'description' => "Earnings from Voice Call with {$user->name} (1 min)",
                    'metadata' => ['call_request_id' => $callRequest->id]
                ]);
            }
        }

        return response()->json([
            'status' => 'ok',
            'remaining_balance' => $user->wallet->balance
        ]);
    }

    public function callStatusCallback(Request $request)
    {
        // Twilio hits this after call ends
        $sid = $request->input('CallSid');
        $callRequest = CallRequest::where('twilio_sid', $sid)->first();

        if ($callRequest) {
            $totalDuration = $request->input('DialCallDuration') ?? $request->input('CallDuration') ?? 0;
            $status = $request->input('DialCallStatus') ?? $request->input('CallStatus');

            // Find how many minutes we've already billed via pings
            $billedMinutes = floor($callRequest->call_duration / 60);
            $totalMinutesToBill = ceil($totalDuration / 60);

            $remainingMinutes = max(0, $totalMinutesToBill - $billedMinutes);

            $callRequest->call_duration = $totalDuration;
            $callRequest->call_status = strtolower($status);
            $callRequest->end_time = now();

            if ($remainingMinutes > 0) {
                $astrologer = $callRequest->astrologer;
                $pricePerMin = $astrologer->call_price;
                $remainingCost = (float) ($remainingMinutes * $pricePerMin);

                // Final deduction for remaining time
                $user = $callRequest->user;
                if ($user->wallet && $remainingCost > 0) {
                    $user->wallet->decrement('balance', $remainingCost);
                    $user->wallet->transactions()->create([
                        'amount' => $remainingCost,
                        'type' => 'debit',
                        'description' => "Final Voice Call charge with {$astrologer->display_name} ({$remainingMinutes} mins)",
                        'metadata' => ['call_request_id' => $callRequest->id]
                    ]);

                    // Commission & Earnings
                    $commissionRate = $astrologer->call_commission_percentage ?? \App\Models\Setting::getValue('global_voice_commission', 20);
                    $commissionAmount = round(($remainingCost * $commissionRate) / 100, 2);
                    $astrologerEarnings = $remainingCost - $commissionAmount;

                    $callRequest->increment('call_cost', (float) $remainingCost);
                    $callRequest->increment('commission_amount', (float) $commissionAmount);
                    $callRequest->increment('astrologer_earnings', (float) $astrologerEarnings);

                    // Credit Astrologer
                    $astrologerUser = $astrologer->user;
                    if ($astrologerUser && $astrologerUser->wallet) {
                        $astrologerUser->wallet->increment('balance', (float) $astrologerEarnings);
                        $astrologerUser->wallet->transactions()->create([
                            'amount' => $astrologerEarnings,
                            'type' => 'credit',
                            'description' => "Final Earnings from Voice Call with {$user->name} ({$remainingMinutes} mins)",
                            'metadata' => ['call_request_id' => $callRequest->id]
                        ]);
                    }
                }
            }

            $callRequest->save();
        }

        $response = new VoiceResponse();
        $response->hangup();
        return response($response->asXML())->header('Content-Type', 'text/xml');
    }
}
