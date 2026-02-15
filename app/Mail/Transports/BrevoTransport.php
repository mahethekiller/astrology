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
        // Include the Brevo SDK autoloader
        require_once base_path('APIv3-php-library/autoload.php');

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $config = \Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->key);
        $apiInstance = new \Brevo\Client\Api\TransactionalEmailsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail();

        // Sender
        $sender = new \Brevo\Client\Model\SendSmtpEmailSender([
            'name' => $email->getFrom()[0]->getName() ?: config('mail.from.name'),
            'email' => $email->getFrom()[0]->getAddress(),
        ]);
        $sendSmtpEmail->setSender($sender);

        // To
        $to = array_map(function (Address $address) {
            $params = ['email' => $address->getAddress()];
            if ($address->getName()) {
                $params['name'] = $address->getName();
            }
            return new \Brevo\Client\Model\SendSmtpEmailTo($params);
        }, $email->getTo());
        $sendSmtpEmail->setTo($to);

        // Subject
        $sendSmtpEmail->setSubject($email->getSubject());

        // Content
        if ($email->getHtmlBody()) {
            $sendSmtpEmail->setHtmlContent($email->getHtmlBody());
        }
        if ($email->getTextBody()) {
            $sendSmtpEmail->setTextContent($email->getTextBody());
        }

        // Cc
        if ($email->getCc()) {
            $cc = array_map(function (Address $address) {
                return new \Brevo\Client\Model\SendSmtpEmailCc([
                    'email' => $address->getAddress(),
                    'name' => $address->getName(),
                ]);
            }, $email->getCc());
            $sendSmtpEmail->setCc($cc);
        }

        // Bcc
        if ($email->getBcc()) {
            $bcc = array_map(function (Address $address) {
                return new \Brevo\Client\Model\SendSmtpEmailBcc([
                    'email' => $address->getAddress(),
                    'name' => $address->getName(),
                ]);
            }, $email->getBcc());
            $sendSmtpEmail->setBcc($bcc);
        }

        // Reply To
        if ($email->getReplyTo()) {
            $replyTo = new \Brevo\Client\Model\SendSmtpEmailReplyTo([
                'email' => $email->getReplyTo()[0]->getAddress(),
                'name' => $email->getReplyTo()[0]->getName(),
            ]);
            $sendSmtpEmail->setReplyTo($replyTo);
        }

        try {
            $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            throw new \Exception('Brevo SDK request failed: ' . $e->getMessage());
        }
    }
}
