<?php

namespace App\Business;

use App\Entity\Mail;
use App\SMTP\MessageHandler\MessageHandlerInterface;
use App\SMTP\Session;

class SMTPBusiness
{
    /** @var MessageHandlerInterface[] */
    private array $messageHandlers = [];

    public function addMessageHandler(MessageHandlerInterface $messageHandler): void
    {
        $handlers = $this->messageHandlers[$messageHandler->getPriority()] ?? [];
        $handlers[] = $messageHandler;
        $this->messageHandlers[$messageHandler->getPriority()] = $handlers;
        krsort($this->messageHandlers, SORT_NUMERIC);
    }

    public function handleMessage(string $data, Session $session): ?string
    {
        foreach ($this->messageHandlers as $handlers) {
            foreach ($handlers as $handler) {
                if (!$handler->support($data, $session)) {
                    continue;
                }

                $message = $handler->handle($data, $session);
                $session->addCalledHandler($handler);
                return $message;
            }
        }
        return '501 Unknown message';
    }

    public function setData(Mail $mail): void
    {

    }
}