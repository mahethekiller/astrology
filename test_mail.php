<?php

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send test email via Brevo SDK...\n";
    // Change the email address below to your recipient address
    $recipient = 'mahethekiller@gmail.com';

    Mail::raw('This is a test email to verify the Brevo SDK integration in Laravel 12.', function ($message) use ($recipient) {
        $message->to($recipient)
            ->subject('Brevo SDK Configuration Test');
    });
    echo "Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "Failed to send test email: " . $e->getMessage() . "\n";
}
