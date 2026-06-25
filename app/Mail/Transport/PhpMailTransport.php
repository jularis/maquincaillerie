<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class PhpMailTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $toAddresses = array_map(fn($a) => $a->toString(), $email->getTo());
        $to      = implode(', ', $toAddresses);
        $subject = $email->getSubject() ?? '';
        $body    = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        $fromAddress = config('mail.from.address');
        $fromName    = config('mail.from.name');

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromAddress}>\r\n";
        $headers .= "Reply-To: {$fromAddress}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        mail($to, $subject, $body, $headers);
    }

    public function __toString(): string
    {
        return 'phpmail://default';
    }
}
