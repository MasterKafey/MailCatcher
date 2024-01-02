<?php

namespace App\SMTP\MessageHandler\Auth;

use App\SMTP\MessageHandler\AbstractMessageHandler;
use App\SMTP\Session;

abstract class AbstractAuthenticatedMessageHandler extends AbstractMessageHandler
{
    public final function handle(string $data, Session $session): ?string
    {
        if (!$this->isAuthenticated($session)) {
            return '530 5.7.1 Authentication required';
        }

        return $this->authenticatedHandle($data, $session);
    }

    public function isAuthenticated(Session $session): bool
    {
        return null !== $session->getInbox();
    }

    public abstract function authenticatedHandle(string $data, Session $session): ?string;
}