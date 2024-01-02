<?php

namespace App\SMTP\MessageHandler;

use App\Entity\Mail;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;

class RsetCommandHandler extends AbstractMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'RSET');
    }

    public function handle(string $data, Session $session): ?string
    {
        $session->reset();

        return '250 OK';
    }
}