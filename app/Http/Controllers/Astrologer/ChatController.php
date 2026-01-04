<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Models\ChatRequest;
use App\Models\AstrologerProfile;

class ChatController extends Controller
{
    private $twilioSid;
    private $twilioToken;
    private $twilioServiceSid;

    public function __construct()
    {
        $this->twilioSid = config('services.twilio.sid');
        $this->twilioToken = config('services.twilio.token');
        $this->twilioServiceSid = config('services.twilio.chat.service_sid');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->astrologerProfile) {
            return redirect()->route('home')->with('error', 'Not an astrologer');
        }

        $requests = ChatRequest::where('astrologer_id', $user->astrologerProfile->id)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('astrologer.chat.index', compact('requests'));
    }

    public function accept($requestId)
    {
        $user = Auth::user(); // The astrologer
        $chatRequest = ChatRequest::findOrFail($requestId);

        if ($chatRequest->astrologer_id !== $user->astrologerProfile->id) {
            abort(403);
        }

        // Create Twilio Conversation
        try {
            $client = new Client($this->twilioSid, $this->twilioToken);

            $userIdentity = 'user_' . $chatRequest->user_id;
            $astrologerIdentity = 'user_' . $user->id;

            $conversation = $client->conversations->v1->services($this->twilioServiceSid)
                ->conversations
                ->create([
                    'friendlyName' => 'Chat: ' . $chatRequest->user->name . ' & ' . $user->astrologerProfile->display_name,
                    'attributes' => json_encode([
                        'user_id' => $chatRequest->user_id,
                        'astrologer_id' => $user->astrologerProfile->id,
                        'chat_price' => $user->astrologerProfile->chat_price,
                        'initiated_at' => now()->timestamp,
                        'request_id' => $chatRequest->id
                    ])
                ]);

            // Add Participants
            $client->conversations->v1->services($this->twilioServiceSid)
                ->conversations($conversation->sid)
                ->participants
                ->create(['identity' => $userIdentity]);

            $client->conversations->v1->services($this->twilioServiceSid)
                ->conversations($conversation->sid)
                ->participants
                ->create(['identity' => $astrologerIdentity]);

            // Update Request
            $chatRequest->update([
                'status' => 'accepted',
                'twilio_sid' => $conversation->sid
            ]);

            return redirect()->route('astrologer.chat.room', $conversation->sid);

        } catch (\Exception $e) {
            Log::error('Twilio Accept Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error accepting chat: ' . $e->getMessage());
        }
    }

    public function reject($requestId)
    {
        $user = Auth::user();
        $chatRequest = ChatRequest::findOrFail($requestId);

        if ($chatRequest->astrologer_id !== $user->astrologerProfile->id) {
            abort(403);
        }

        $chatRequest->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Request rejected.');
    }

    public function room($sid)
    {
        $user = Auth::user();
        return view('astrologer.chat.room', compact('sid', 'user'));
    }

    public function toggleStatus(Request $request)
    {
        $user = Auth::user();
        if (!$user->astrologerProfile) {
            return response()->json(['error' => 'Not Authorized'], 403);
        }

        $type = $request->type; // 'chat' or 'call'
        $status = $request->status; // true or false

        $profile = $user->astrologerProfile;

        if ($type === 'chat') {
            $profile->is_chat_online = $status;
        } elseif ($type === 'call') {
            $profile->is_call_online = $status;
        }

        $profile->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user->astrologerProfile) {
            abort(403);
        }

        $chats = \App\Models\ChatRequest::where('astrologer_id', $user->astrologerProfile->id)
            ->where('status', 'completed')
            ->with('user')
            ->latest()
            ->get();

        return view('astrologer.chat.history', compact('chats'));
    }
}
