<?php

namespace App\SMTP;

use App\Entity\Mail;
use App\Entity\Inbox;
use App\SMTP\MessageHandler\MessageHandlerInterface;
use React\Socket\ConnectionInterface;

class Session
{
    private array $calledHandler = [];

    private Mail $mail;

    private ?Inbox $inbox = null;

    public function __construct(
        private readonly ConnectionInterface $connection
    )
    {
        $this->mail = new Mail();
    }

    public function addCalledHandler(MessageHandlerInterface $handler): self
    {
        $this->calledHandler[] = $handler;

        return $this;
    }

    public function isLastHandler(MessageHandlerInterface|string $handler): bool
    {
        return ($this->calledHandler[array_key_last($this->calledHandler)] ?? null) instanceof $handler;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function getMail(): Mail
    {
        return $this->mail;
    }

    public function setMail(Mail $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getInbox(): ?Inbox
    {
        return $this->inbox;
    }

    public function setInbox(?Inbox $inbox): self
    {
        $this->inbox = $inbox;
        return $this;
    }

    public function reset(): void
    {
        $this->setMail(new Mail());
        $this->inbox = null;
        $this->calledHandler = [];
    }
}