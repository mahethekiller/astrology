<?php

use App\Models\AstrologerProfile;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = 2; // We know this ID exists
$profile = AstrologerProfile::find($id);

if ($profile) {
    echo "Current Chat Price: " . $profile->chat_price . "\n";
    echo "Current Call Price: " . $profile->call_price . "\n";

    $profile->chat_price = 25.50;
    $profile->call_price = 30.00;
    $profile->save();

    $profile->refresh();
    echo "Updated Chat Price: " . $profile->chat_price . "\n";
    echo "Updated Call Price: " . $profile->call_price . "\n";

    if ($profile->chat_price == 25.50 && $profile->call_price == 30.00) {
        echo "SUCCESS: Prices updated correctly.\n";
    } else {
        echo "FAILURE: Prices not updated correctly.\n";
    }
} else {
    echo "Profile not found.\n";
}
