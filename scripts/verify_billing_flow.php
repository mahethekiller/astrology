<?php

use App\Models\User;
use App\Models\AstrologerProfile;
use App\Models\CallRequest;
use App\Models\Wallet;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Verification...\n";

// 1. Setup Data
echo "1. Setting up User and Astrologer...\n";
$user = User::firstOrCreate(
    ['email' => 'testuser@example.com'],
    ['name' => 'Test User', 'password' => bcrypt('password')]
);

// Ensure wallet
if (!$user->wallet) {
    try {
        $user->wallet()->create(['balance' => 100]);
    } catch (\Exception $e) {
    } // Ignore if race condition
    $user->refresh();
}
if ($user->wallet) {
    $user->wallet->update(['balance' => 100]); // Reset balance
}

$astroUser = User::firstOrCreate(
    ['email' => 'astro@example.com'],
    ['name' => 'Test Astrologer', 'password' => bcrypt('password'), 'role' => 'astrologer']
);

if (!$astroUser->wallet) {
    try {
        $astroUser->wallet()->create(['balance' => 0]);
    } catch (\Exception $e) {
    }
    $astroUser->refresh();
}
if ($astroUser->wallet) {
    $astroUser->wallet->update(['balance' => 0]); // Reset balance
}

// Check for existing profile to avoid dupe errors if slug exists
$astrologer = AstrologerProfile::where('user_id', $astroUser->id)->first();

if (!$astrologer) {
    $astrologer = AstrologerProfile::create([
        'user_id' => $astroUser->id,
        'display_name' => 'Guru Test',
        'slug' => 'guru-test-' . Str::random(5),
        'gender' => 'male',
        'date_of_birth' => '1990-01-01',
        'experience_years' => 5,
        'about' => 'Expert in Vedic Astrology',
        'profile_image' => 'default.jpg',
        'languages' => ['English', 'Hindi'], // Casted to array by model
        'specializations' => ['Vedic'],      // Casted to array by model
        'call_price' => 10,
        'chat_price' => 5,
        'status' => 'active',
        'verification_status' => 'approved'
    ]);
} else {
    // Ensure prices
    $astrologer->update(['call_price' => 10, 'chat_price' => 5]);
}

// 2. Set Commission
echo "2. Setting Global Commission to 10%...\n";
Setting::setValue('global_voice_commission', 10);

// 3. Create Initial Call Request (Simulating 'initiated' state)
echo "3. Creating Call Request...\n";
$callSid = 'CA' . uniqid();
$callRequest = CallRequest::create([
    'user_id' => $user->id,
    'astrologer_id' => $astrologer->id,
    'twilio_sid' => $callSid,
    'call_status' => 'initiated',
    'start_time' => now(),
    'call_cost' => 0
]);

// 4. Simulate Webhook (Call Controller Logic)
echo "4. Simulating Call Completion (1 minute duration)...\n";
$duration = 60; // 1 min
$pricePerMin = $astrologer->call_price; // 10
$cost = ceil($duration / 60) * $pricePerMin; // 1 * 10 = 10

// Update CallRequest
$callRequest->update([
    'call_duration' => $duration,
    'call_status' => 'completed',
    'end_time' => now(),
    'call_cost' => $cost
]);

// Deduct User
if ($user->wallet) {
    $user->wallet->decrement('balance', $cost);
    echo "   User Balance Deducted: $cost. New Balance: " . $user->wallet->balance . "\n";
} else {
    echo "   [ERROR] User has no wallet!\n";
}

// Commission Logic
$commissionRate = Setting::getValue('global_voice_commission', 20);
$commissionAmount = round(($cost * $commissionRate) / 100, 2);
$astrologerEarnings = $cost - $commissionAmount;

echo "   Commission Rate: $commissionRate%\n";
echo "   Commission: $commissionAmount\n";
echo "   Earnings: $astrologerEarnings\n";

// Update Financials
$callRequest->update([
    'commission_amount' => $commissionAmount,
    'astrologer_earnings' => $astrologerEarnings
]);

// Credit Astrologer
if ($astroUser->wallet) {
    $astroUser->wallet->increment('balance', $astrologerEarnings);
    echo "   Astrologer Credited. New Balance: " . $astroUser->wallet->balance . "\n";
} else {
    echo "   [ERROR] Astrologer has no wallet!\n";
}

// 5. Assertions
echo "5. Verifying Results...\n";

if ($user->wallet && $user->wallet->balance == 90) {
    echo "   [PASS] User Balance Correct (90)\n";
} else {
    echo "   [FAIL] User Balance Incorrect (" . ($user->wallet->balance ?? 'null') . ")\n";
}

if ($astroUser->wallet && $astroUser->wallet->balance == 9) {
    echo "   [PASS] Astrologer Balance Correct (9)\n";
} else {
    echo "   [FAIL] Astrologer Balance Incorrect (" . ($astroUser->wallet->balance ?? 'null') . ")\n";
}

if ($callRequest->commission_amount == 1.00) {
    echo "   [PASS] Commission Amount Correct (1.00)\n";
} else {
    echo "   [FAIL] Commission Amount Incorrect ({$callRequest->commission_amount})\n";
}

if ($callRequest->astrologer_earnings == 9.00) {
    echo "   [PASS] Astrologer Earnings Correct (9.00)\n";
} else {
    echo "   [FAIL] Astrologer Earnings Incorrect ({$callRequest->astrologer_earnings})\n";
}

echo "Verification Complete.\n";
