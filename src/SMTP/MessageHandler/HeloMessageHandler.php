<?php

namespace App\SMTP\MessageHandler;

use App\SMTP\Session;

class HeloMessageHandler extends AbstractMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'HELO') || str_starts_with($message, 'EHLO');
    }

    public function handle(string $data, Session $session): string
    {
        return implode("\r\n", [
            '250-MailCatcher',
            '250-AUTH LOGIN PLAIN',
            '250-SIZE 52428800',
            '250-8BITMIME',
            '250 SMTPUTF8',
        ]);
    }
}