<?php

namespace App\Doctrine\Listener;

use App\Business\TokenBusiness;
use App\Entity\Inbox;

class CredentialsInboxListener
{
    public function __construct(
        private readonly TokenBusiness $tokenBusiness
    )
    {

    }

    public function prePersist(Inbox $inbox): void
    {
        $inbox->setIdentifier($this->tokenBusiness->generateToken());
        $inbox->setPassword($this->tokenBusiness->generateToken());
    }
}