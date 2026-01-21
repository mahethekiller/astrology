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

    public function __construct()
    {
        $this->twilioSid = config('services.twilio.sid');
        $this->twilioApiKey = config('services.twilio.chat.api_key'); // Reusing Chat API Key if valid, else need separate
        $this->twilioApiSecret = config('services.twilio.chat.api_secret');
        $this->twilioVoiceAppSid = config('services.twilio.voice.app_sid'); // Must be set in config
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

            $from = $request->input('From'); // client:user_1
            $toAstrologerId = $request->input('astrologer_id');

            Log::info("Call from: $from, To Astrologer ID: $toAstrologerId");

            if (!$toAstrologerId) {
                // Maybe it's a direct dial to client:astrologer_X? 
                // If the client used .connect({ params: { astrologer_id: ... } }) it comes as post param.
                Log::error('No Astrologer ID provided');
                $response->say('Invalid request. No Astrologer ID provided.');
                return response($response->asXML())->header('Content-Type', 'text/xml');
            }

            // Parse User ID from 'client:user_{id}'
            $userId = str_replace('client:user_', '', $from);

            $user = User::find($userId);
            $astrologer = AstrologerProfile::find($toAstrologerId);

            if (!$user || !$astrologer) {
                Log::error("User ($userId) or Astrologer ($toAstrologerId) not found");
                $response->say('User or Astrologer not found.');
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

            if ($pricePerMin <= 0) {
                // Free call? Or not allowed? Assuming allowed.
                $timeLimit = 3600; // 1 hour cap
            } else {
                $balance = $user->wallet->balance;
                // Safeguard against division by zero (already checked <=0 but good to cover)
                $maxDuration = floor(($balance / ($pricePerMin > 0 ? $pricePerMin : 1)) * 60);

                if ($maxDuration < 60) {
                    $response->say('You have insufficient balance for this call.');
                    return response($response->asXML())->header('Content-Type', 'text/xml');
                }
                // Twilio max timeLimit is 4 hours (14400s). Ensure reasonable int.
                $timeLimit = (int) min($maxDuration, 14400);
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

    public function callStatusCallback(Request $request)
    {
        // Twilio hits this after call ends (because of 'action' in Dial)
        // Params: DialCallStatus, DialCallDuration, DialCallSid... OR CallStatus etc.
        // If it's the 'action' callback, it gives the result of the Dial.

        $sid = $request->input('CallSid');
        $callRequest = CallRequest::where('twilio_sid', $sid)->first();

        if ($callRequest) {
            $duration = $request->input('DialCallDuration') ?? 0;
            $status = $request->input('DialCallStatus');

            $callRequest->call_duration = $duration;
            $callRequest->call_status = strtolower($status); // completed, busy, no-answer, failed
            $callRequest->end_time = now();

            // Calculate Cost
            $astrologer = $callRequest->astrologer;
            $pricePerMin = $astrologer->call_price;

            // User pays for full minutes? Or seconds? Usually per minute billing.
            // Let's do per minute ceiling.
            $minutes = ceil($duration / 60);
            $cost = $minutes * $pricePerMin;

            $callRequest->call_cost = $cost;
            $callRequest->save();

            // Deduct from Wallet
            if ($cost > 0) {
                // Ensure latest balance
                $user = $callRequest->user;

                // Transaction
                if ($user->wallet) {
                    $user->wallet->decrement('balance', $cost);
                    $user->wallet->transactions()->create([
                        'amount' => $cost,
                        'type' => 'debit',
                        'description' => "Voice Call with {$astrologer->display_name} ({$minutes} mins)",
                        'metadata' => ['call_request_id' => $callRequest->id]
                    ]);
                }

                // ---------------------------------------------------------
                // 2. Calculate Commission & Earnings
                // ---------------------------------------------------------
                $commissionRate = \App\Models\Setting::getValue('global_voice_commission', 20); // Default 20%
                $commissionAmount = round(($cost * $commissionRate) / 100, 2);
                $astrologerEarnings = $cost - $commissionAmount;

                // Update Call Request with financials
                $callRequest->update([
                    'commission_amount' => $commissionAmount,
                    'astrologer_earnings' => $astrologerEarnings
                ]);

                // ---------------------------------------------------------
                // 3. Credit Astrologer Wallet
                // ---------------------------------------------------------
                $astrologerUser = $astrologer->user; // Get User model from AstrologerProfile

                if ($astrologerUser && $astrologerUser->wallet) {
                    $astrologerUser->wallet->increment('balance', $astrologerEarnings);

                    $astrologerUser->wallet->transactions()->create([
                        'amount' => $astrologerEarnings,
                        'type' => 'credit',
                        'description' => "Earnings from Voice Call with {$user->name} ({$minutes} mins)",
                        'metadata' => [
                            'call_request_id' => $callRequest->id,
                            'total_call_cost' => $cost,
                            'commission_deducted' => $commissionAmount
                        ]
                    ]);
                } else {
                    Log::error("Astrologer User or Wallet not found for Astrologer ID: {$astrologer->id}");
                }
            }
        }

        // Return TwiML to hangup (or just empty response to end call)
        $response = new VoiceResponse();
        $response->hangup();
        return response($response->asXML())->header('Content-Type', 'text/xml');
    }
}
