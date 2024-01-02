<?php

namespace App\SMTP\MessageHandler;

use App\Entity\Mail;
use App\SMTP\Session;
use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;

class QuitMessageHandler extends AbstractMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'QUIT');
    }

    public function handle(string $data, Session $session): string
    {
        Loop::get()->addTimer(1, function () use ($session) {
            $session->getConnection()->close();
            $session->reset();
        });
        return '221 Service closing transmission channel';
    }
}