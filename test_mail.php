<?php

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send test email to a202bb001@smtp-brevo.com...\n";
    Mail::raw('This is a test email to verify the SMTP configuration.', function ($message) {
        $message->to('mahethekiller@gmail.com')
            ->subject('SMTP Configuration Test');
    });
    echo "Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "Failed to send test email: " . $e->getMessage() . "\n";
}
