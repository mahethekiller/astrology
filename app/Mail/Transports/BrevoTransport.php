<?php

namespace App\Mail\Transports;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class BrevoTransport extends AbstractTransport
{
    /**
     * Create a new Brevo transport instance.
     */
    public function __construct(protected string $key)
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'brevo';
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $payload = [
            'sender' => [
                'name' => $email->getFrom()[0]->getName() ?: config('mail.from.name'),
                'email' => $email->getFrom()[0]->getAddress(),
            ],
            'to' => array_map(function (Address $address) {
                $recipient = ['email' => $address->getAddress()];
                if ($address->getName()) {
                    $recipient['name'] = $address->getName();
                }
                return $recipient;
            }, $email->getTo()),
            'subject' => $email->getSubject(),
        ];

        if ($email->getHtmlBody()) {
            $payload['htmlContent'] = $email->getHtmlBody();
        }

        if ($email->getTextBody()) {
            $payload['textContent'] = $email->getTextBody();
        }

        if ($email->getCc()) {
            $payload['cc'] = array_map(function (Address $address) {
                return ['email' => $address->getAddress(), 'name' => $address->getName()];
            }, $email->getCc());
        }

        if ($email->getBcc()) {
            $payload['bcc'] = array_map(function (Address $address) {
                return ['email' => $address->getAddress(), 'name' => $address->getName()];
            }, $email->getBcc());
        }

        if ($email->getReplyTo()) {
            $payload['replyTo'] = [
                'email' => $email->getReplyTo()[0]->getAddress(),
                'name' => $email->getReplyTo()[0]->getName(),
            ];
        }

        $response = Http::withHeaders([
            'api-key' => $this->key,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            throw new \Exception('Brevo API request failed: ' . $response->body());
        }
    }
}
