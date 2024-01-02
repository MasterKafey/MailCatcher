<?php

namespace App\SMTP\MessageHandler;

use App\Business\SMTPBusiness;
use App\Entity\Mail;
use App\SMTP\MessageHandler\Auth\AbstractAuthenticatedMessageHandler;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;

class DataMessageHandler extends AbstractAuthenticatedMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return $session->isLastHandler(BeginDataMessageHandler::class) || $session->isLastHandler(self::class);
    }

    public function authenticatedHandle(string $data, Session $session): string
    {
        $session->getMail()->addRaw($data);

        return '250 OK';
    }
}