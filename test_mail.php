<?php

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send test email via BrevoService...\n";
    // Change the email address below to your recipient address
    $recipient = 'mahethekiller@gmail.com';

    \App\Services\BrevoService::sendEmail(
        $recipient,
        'BrevoService Configuration Test',
        '<h1>Success!</h1><p>This is a test email to verify the reusable <b>BrevoService</b> integration in Laravel 12.</p>'
    );
    echo "Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "Failed to send test email: " . $e->getMessage() . "\n";
}
