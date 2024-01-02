<?php

namespace App\SMTP\MessageHandler;

use App\Entity\Mail;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;

interface MessageHandlerInterface
{
    public function support(string $message, Session $session): bool;

    public function handle(string $data, Session $session): ?string;

    public function getPriority(): int;
}