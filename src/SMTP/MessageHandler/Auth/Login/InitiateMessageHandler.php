<?php

namespace App\SMTP\MessageHandler\Auth\Login;

use App\SMTP\MessageHandler\AbstractMessageHandler;
use App\SMTP\Session;

class InitiateMessageHandler extends AbstractMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'AUTH LOGIN');
    }

    public function handle(string $data, Session $session): string
    {
        return '334 ' . base64_encode('Username');
    }
}