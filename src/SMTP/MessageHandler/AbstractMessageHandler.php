<?php

namespace App\SMTP\MessageHandler;

abstract class AbstractMessageHandler implements MessageHandlerInterface
{
    public function getPriority(): int
    {
        return 0;
    }
}