<?php

namespace App\SMTP\MessageHandler\Auth\Login;

use App\SMTP\MessageHandler\AbstractMessageHandler;
use App\SMTP\Session;

class PasswordMessageHandler extends AbstractMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return $session->isLastHandler(UsernameMessageHandler::class);
    }

    public function handle(string $data, Session $session): ?string
    {
        $inbox = $session->getInbox();
        if (null === $inbox || $inbox->getPassword() !== base64_decode($data)) {
            return '535 5.7.8 Authentication credentials invalid';
        }

        return '235 Authentication successful';
    }
}