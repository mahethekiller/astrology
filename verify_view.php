<?php

use App\Models\AstrologerProfile;
use Illuminate\Support\Facades\View;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$astrologers = AstrologerProfile::where('id', 2)->paginate(12);

$html = View::make('frontend.astrologer.partials.astrologer-list', compact('astrologers'))->render();

// Check for prices
if (strpos($html, '₹26/min') !== false) {
    echo "Found Chat Price (rounded): ₹26/min\n";
} else {
    echo "Chat Price NOT Found\n";
}

if (strpos($html, '₹30/min') !== false) {
    echo "Found Call Price: ₹30/min\n";
} else {
    echo "Call Price NOT Found\n";
}
