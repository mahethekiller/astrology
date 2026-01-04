<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OtpAuthController extends Controller
{
    /**
     * Send OTP to the provided phone number.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string', // Basic validation, can be stricter
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $phone = $request->phone_number;

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store in Cache for 10 minutes
        Cache::put('otp_' . $phone, $otp, 600);

        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');

            if ($sid && $token && $from) {
                $client = new \Twilio\Rest\Client($sid, $token);
                $client->messages->create(
                    $phone,
                    [
                        'from' => $from,
                        'body' => "Your OTP for Astrology App is: $otp"
                    ]
                );
            } else {
                // Fallback for development if credentials missing (or log error)
                // Keeping the mock response for dev convenience if keys are missing
                return response()->json([
                    'message' => 'Twilio credentials missing. OTP (Dev): ' . $otp,
                    'otp' => $otp
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'OTP sent successfully',
            // 'otp' => $otp // SECURE: Do not return OTP in production
        ]);
    }

    /**
     * Verify OTP and Login or Prompt for Registration.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $phone = $request->phone_number;
        $otp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp != $otp) {
            return response()->json(['error' => 'Invalid or expired OTP'], 400);
        }

        // OTP is valid
        // Check if user exists
        $user = User::where('phone_number', $phone)->first();

        if ($user) {
            // User exists, login
            Auth::login($user);
            Cache::forget('otp_' . $phone); // Clear OTP

            // Check if user has role, if not assume 'user'? 
            // The request said "normal users", so existing checks in middleware should handle roles.

            $redirectUrl = route('user.dashboard');
            if ($user->hasRole('admin')) {
                $redirectUrl = route('admin.dashboard');
            } elseif ($user->hasRole('astrologer')) {
                $redirectUrl = route('astrologer.dashboard');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully',
                'redirect_url' => $redirectUrl
            ]);
        } else {
            // User does not exist, prompt for name
            return response()->json([
                'status' => 'new_user',
                'message' => 'OTP verified. Please enter your name to complete registration.',
            ]);
        }
    }

    /**
     * Complete registration for new user with Name.
     */
    public function registerWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $phone = $request->phone_number;
        $otp = $request->otp;
        $name = $request->name;

        // Verify OTP again to be sure (or use a signed temp token)
        $cachedOtp = Cache::get('otp_' . $phone);
        if (!$cachedOtp || $cachedOtp != $otp) {
            return response()->json(['error' => 'Invalid or expired OTP session'], 400);
        }

        // Check if user already exists (race condition check)
        if (User::where('phone_number', $phone)->exists()) {
            return response()->json(['error' => 'User already exists. Please login.'], 400);
        }

        // Create User
        // Need email/password as they are likely required by DB schema.
        // If email is required, we might need to ask for it, or generate a dummy one.
        // Steps 1 said: "after login create user if not present ask full name after otp verification then create user and login"
        // It didn't mention email. But DB migration usually requires email.
        // Let's check the users table migration I read earlier.
        // "email" -> unique, required. "password" -> required.

        // I will generate a dummy email and random password for now, as the user didn't specify email input.
        // Or I can ask the user if they want to capture email.
        // For now, I'll generate a placeholder email like {phone}@example.com

        $user = User::create([
            'name' => $name,
            'email' => $phone . '@example.com', // Placeholder
            'phone_number' => $phone,
            'password' => Hash::make(Str::random(16)),
        ]);

        // Assign default role 'user'
        $user->assignRole('user');

        // Create Wallet
        $user->wallet()->create(['balance' => 0]);

        Auth::login($user);
        Cache::forget('otp_' . $phone);

        return response()->json([
            'status' => 'success',
            'message' => 'Registered and logged in successfully',
            'redirect_url' => route('user.dashboard')
        ]);
    }
}
