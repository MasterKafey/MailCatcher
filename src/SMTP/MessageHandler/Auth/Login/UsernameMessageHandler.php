<?php

namespace App\SMTP\MessageHandler\Auth\Login;

use App\Entity\Inbox;
use App\SMTP\MessageHandler\AbstractMessageHandler;
use App\SMTP\Session;
use Doctrine\ORM\EntityManagerInterface;

class UsernameMessageHandler extends AbstractMessageHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    public function support(string $message, Session $session): bool
    {
        return $session->isLastHandler(InitiateMessageHandler::class);
    }

    public function handle(string $data, Session $session): ?string
    {
        $inbox = $this->entityManager->getRepository(Inbox::class)->findOneBy(['identifier' => base64_decode($data)]);
        $session->setInbox($inbox);

        return '334 ' . base64_encode('Password');
    }
}