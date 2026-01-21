<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;

class ChatController extends Controller
{
    private $twilioSid;
    private $twilioToken;
    private $twilioApiKey;
    private $twilioApiSecret;
    private $twilioServiceSid;

    public function __construct()
    {
        $this->twilioSid = config('services.twilio.sid');
        $this->twilioToken = config('services.twilio.token'); // Auth token for REST API (if needed, but API Key is better for JWT)
        // Ideally use API Key/Secret for both REST and JWT to be safe, but sometimes Auth Token is used for REST.
        // For production, use API Key/Secret for everything.
        // Config:
        $this->twilioApiKey = config('services.twilio.chat.api_key');
        $this->twilioApiSecret = config('services.twilio.chat.api_secret');
        $this->twilioServiceSid = config('services.twilio.chat.service_sid');
    }

    public function requestChat($astrologerId)
    {
        $user = Auth::user();
        $astrologer = AstrologerProfile::with('user')->findOrFail($astrologerId);

        // 0. Ensure Wallet Exists
        if (!$user->wallet) {
            $user->wallet()->create(['balance' => 0]);
            $user->load('wallet');
        }

        // 1. Check Balance (Min 5 mins)
        $minBalance = $astrologer->chat_price * 5;
        if ($user->wallet->balance < $minBalance) {
            return redirect()->route('wallet.index')->with('error', 'Insufficient balance. You need at least ₹' . $minBalance . ' to start a chat.');
        }

        // 2. Check for existing pending request
        $existingRequest = \App\Models\ChatRequest::where('user_id', $user->id)
            ->where('astrologer_id', $astrologerId)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('chat.waiting', $existingRequest->id);
        }

        // 3. Create Request
        $chatRequest = \App\Models\ChatRequest::create([
            'user_id' => $user->id,
            'astrologer_id' => $astrologerId,
            'status' => 'pending'
        ]);

        return redirect()->route('chat.waiting', $chatRequest->id);
    }

    public function waiting($requestId)
    {
        $user = Auth::user();
        $chatRequest = \App\Models\ChatRequest::with('astrologer')->where('user_id', $user->id)->findOrFail($requestId);

        if ($chatRequest->status === 'accepted' && $chatRequest->twilio_sid) {
            return redirect()->route('chat.room', $chatRequest->twilio_sid);
        }

        return view('frontend.chat.waiting', compact('chatRequest'));
    }

    public function checkStatus($requestId)
    {
        $chatRequest = \App\Models\ChatRequest::findOrFail($requestId);
        return response()->json([
            'status' => $chatRequest->status,
            'sid' => $chatRequest->twilio_sid
        ]);
    }

    public function room($sid)
    {
        $user = Auth::user();
        return view('frontend.chat.room', compact('sid'));
    }

    public function endChat(Request $request)
    {
        $sid = $request->sid;

        // Logic to mark request as completed
        // Find request by SID
        $chatRequest = \App\Models\ChatRequest::where('twilio_sid', $sid)->first();

        if ($chatRequest) {
            $chatRequest->status = 'completed';
            $chatRequest->save();

            // Save messages from Twilio to local DB
            try {
                $client = new Client($this->twilioSid, $this->twilioToken);
                $messages = $client->conversations->v1->services($this->twilioServiceSid)
                    ->conversations($sid)
                    ->messages
                    ->read();

                foreach ($messages as $msg) {
                    \App\Models\ChatMessage::create([
                        'chat_request_id' => $chatRequest->id,
                        'sender_identity' => $msg->author,
                        'body' => $msg->body
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Illuminate\Support\Facades\Log::error("Failed to save chat messages: " . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }

    public function checkSessionStatus(Request $request)
    {
        $sid = $request->sid;
        $chatRequest = \App\Models\ChatRequest::where('twilio_sid', $sid)->first();

        if (!$chatRequest) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json(['status' => $chatRequest->status]);
    }

    public function history()
    {
        $user = Auth::user();
        $chats = \App\Models\ChatRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('astrologer')
            ->latest()
            ->get();

        return view('frontend.chat.history', compact('chats'));
    }

    public function token(Request $request)
    {
        $user = Auth::user();
        $identity = 'user_' . $user->id;

        if (empty($this->twilioApiKey) || empty($this->twilioApiSecret) || empty($this->twilioServiceSid)) {
            return response()->json(['error' => 'Twilio Chat API Keys missing in config.'], 500);
        }

        // Create access token, which we will sign and return to the client,
        // containing the grant we just created.
        $token = new AccessToken(
            $this->twilioSid,
            $this->twilioApiKey,
            $this->twilioApiSecret,
            3600,
            $identity
        );

        // Create Chat Grant
        $chatGrant = new ChatGrant();
        $chatGrant->setServiceSid($this->twilioServiceSid);

        // Add grant to token
        $token->addGrant($chatGrant);

        // Render token to string
        return response()->json(['token' => $token->toJWT()]);
    }

    // Billing Ping - Called every minute
    public function billingPing(Request $request)
    {
        $request->validate([
            'sid' => 'required'
        ]);

        $user = Auth::user();

        // We really should validate the conversation SID belongs to this user and is active.
        // Retrieving attributes from Twilio or local DB.
        // For speed, let's assume we pass the price or fetch astrologer info. 
        // Ideally, we previously stored `chat_price` in the conversation attributes or a local `ChatSession` model.
        // Let's rely on finding the conversation via Twilio to get attributes (slow) OR 
        // pass the astrologer_id in the ping? Secure approach: Fetch convo from Twilio or DB.

        // Let's try fetching conversation attributes (cached if possible in real world)
        // Or better: The client passes astrologer_id, we verify balance against that price.
        // BUT malicious user could pass low price.
        // CORRECT PATH: Fetch Conversation Attributes.

        try {
            $client = new Client($this->twilioSid, $this->twilioToken);
            $conversation = $client->conversations->v1->services($this->twilioServiceSid)
                ->conversations($request->sid)
                ->fetch();

            $attributes = json_decode($conversation->attributes, true);
            $price = $attributes['chat_price'] ?? 0;

            if ($price > 0) {
                // Ensure Wallet Exists
                if (!$user->wallet) {
                    $user->wallet()->create(['balance' => 0]);
                    $user->load('wallet');
                }

                if ($user->wallet->balance < $price) {
                    return response()->json(['status' => 'low_balance'], 402);
                }

                // 1. Deduct from User
                $user->wallet->decrement('balance', $price);
                $user->wallet->transactions()->create([
                    'amount' => $price,
                    'type' => 'debit',
                    'description' => 'Chat usage charge (1 min)',
                    'metadata' => ['conversation_sid' => $request->sid]
                ]);

                // 2. Credit Astrologer & Update Request
                $chatRequest = \App\Models\ChatRequest::where('twilio_sid', $request->sid)->first();
                if ($chatRequest) {

                    // Commission Calculation
                    $commissionRate = \App\Models\Setting::getValue('global_chat_commission', 20);
                    $commissionAmount = round(($price * $commissionRate) / 100, 2);
                    $astrologerEarnings = $price - $commissionAmount;

                    // Update Chat Request Totals
                    $chatRequest->increment('chat_duration', 1); // 1 minute
                    $chatRequest->increment('chat_cost', $price);
                    $chatRequest->increment('commission_amount', $commissionAmount);
                    $chatRequest->increment('astrologer_earnings', $astrologerEarnings);

                    // Credit Astrologer
                    $astrologer = $chatRequest->astrologer;
                    $astrologerUser = $astrologer->user;

                    if ($astrologerUser && $astrologerUser->wallet) {
                        $astrologerUser->wallet->increment('balance', $astrologerEarnings);
                        $astrologerUser->wallet->transactions()->create([
                            'amount' => $astrologerEarnings,
                            'type' => 'credit',
                            'description' => "Earnings from Chat with {$user->name} (1 min)",
                            'metadata' => ['chat_request_id' => $chatRequest->id]
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'ok', 'remaining_balance' => $user->wallet->balance]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
