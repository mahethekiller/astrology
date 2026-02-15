<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    /**
     * Send a transactional email via Brevo API.
     *
     * @param string|array $to Email address or array with 'email' and optionally 'name'
     * @param string $subject
     * @param string $htmlContent
     * @param array|null $sender Optional sender override ['name' => 'Name', 'email' => 'email@example.com']
     * @param array $options Additional options (cc, bcc, replyTo, etc.)
     * @return bool
     * @throws \Exception
     */
    public static function sendEmail($to, string $subject, string $htmlContent, ?array $sender = null, array $options = []): bool
    {
        // If htmlContent is empty but textContent is provided in options, use it
        if (empty($htmlContent) && !empty($options['textContent'])) {
            $htmlContent = nl2br(e($options['textContent']));
        }

        $payload = [
            'sender' => $sender ?: [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => self::formatRecipients($to),
            'subject' => $subject,
            'htmlContent' => $htmlContent ?: ' ', // Ensure it is never empty to satisfy Brevo
        ];

        // Merge additional options
        if (isset($options['textContent'])) {
            $payload['textContent'] = $options['textContent'];
        }
        if (isset($options['cc'])) {
            $payload['cc'] = self::formatRecipients($options['cc']);
        }
        if (isset($options['bcc'])) {
            $payload['bcc'] = self::formatRecipients($options['bcc']);
        }
        if (isset($options['replyTo'])) {
            $payload['replyTo'] = $options['replyTo'];
        }
        if (isset($options['tags'])) {
            $payload['tags'] = $options['tags'];
        }

        $apiKey = config('mail.mailers.brevo.key');

        if (!$apiKey) {
            throw new \Exception('Brevo API key is not configured.');
        }

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            Log::error('Brevo API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload
            ]);
            throw new \Exception('Brevo API request failed: ' . $response->body());
        }

        return true;
    }

    /**
     * Format recipients into the array format required by Brevo.
     *
     * @param string|array $recipients
     * @return array
     */
    private static function formatRecipients($recipients): array
    {
        if (is_string($recipients)) {
            return [['email' => $recipients]];
        }

        if (is_array($recipients)) {
            // Check if it's a single recipient array ['email' => '...', 'name' => '...']
            if (isset($recipients['email'])) {
                return [$recipients];
            }

            // It's already a list of recipients
            return $recipients;
        }

        return [];
    }
}
