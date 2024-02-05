<?php

namespace App\SMTP\MessageHandler;

use App\SMTP\MessageHandler\Auth\AbstractAuthenticatedMessageHandler;
use App\SMTP\Session;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validation;

class MailFromMessageHandler extends AbstractAuthenticatedMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'MAIL FROM:');
    }

    public function authenticatedHandle(string $data, Session $session): string
    {
        return '250 OK';
    }
}