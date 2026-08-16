<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Support\Facades\Auth;

class TwilioTokenController extends Controller
{
    /**
     * Generate Twilio Access Token for Chat
     */
    public function chatToken(Request $request)
    {
        $user = Auth::user();
        $identity = 'user_' . $user->id;

        $twilioAccountSid = config('services.twilio.sid');
        $twilioApiKey = config('services.twilio.chat.api_key');
        $twilioApiSecret = config('services.twilio.chat.api_secret');
        $serviceSid = config('services.twilio.chat.service_sid');

        if (!$twilioAccountSid || !$twilioApiKey || !$twilioApiSecret || !$serviceSid) {
            return response()->json(['error' => 'Twilio Chat configuration is incomplete.'], 500);
        }

        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $identity
        );

        $chatGrant = new ChatGrant();
        $chatGrant->setServiceSid($serviceSid);
        $token->addGrant($chatGrant);

        return response()->json([
            'token' => $token->toJWT(),
            'identity' => $identity
        ]);
    }

    /**
     * Generate Twilio Access Token for Voice
     */
    public function voiceToken(Request $request)
    {
        $user = Auth::user();
        $identity = 'user_' . $user->id;

        if ($user->isAstrologer()) {
            $identity = 'astrologer_' . $user->id;
        }

        $twilioAccountSid = config('services.twilio.sid');
        $twilioApiKey = config('services.twilio.chat.api_key');
        $twilioApiSecret = config('services.twilio.chat.api_secret');
        $appSid = config('services.twilio.voice.app_sid');

        if (!$twilioAccountSid || !$twilioApiKey || !$twilioApiSecret || !$appSid) {
            return response()->json(['error' => 'Twilio Voice configuration is incomplete.'], 500);
        }

        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($appSid);
        
        // Optional: Support for push notifications on APK
        $pushCredentialSid = config('services.twilio.voice.push_credential_sid');
        if ($pushCredentialSid) {
            $voiceGrant->setPushCredentialSid($pushCredentialSid);
        }
        
        $voiceGrant->setIncomingAllow(true);
        $token->addGrant($voiceGrant);

        return response()->json([
            'token' => $token->toJWT(),
            'identity' => $identity
        ]);
    }
}
