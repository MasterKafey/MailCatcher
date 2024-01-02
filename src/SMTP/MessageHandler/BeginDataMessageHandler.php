<?php

namespace App\SMTP\MessageHandler;

use App\SMTP\MessageHandler\Auth\AbstractAuthenticatedMessageHandler;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;

class BeginDataMessageHandler extends AbstractAuthenticatedMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'DATA');
    }

    public function authenticatedHandle(string $data, Session $session): string
    {
        return '354 Start mail input: end with <CRLF>.<CRLF>';
    }
}