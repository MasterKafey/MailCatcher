<?php

namespace App\SMTP\MessageHandler;

use App\Business\SMTPBusiness;
use App\SMTP\MessageHandler\Auth\AbstractAuthenticatedMessageHandler;
use App\SMTP\Session;
use Doctrine\ORM\EntityManagerInterface;

class EndDataMessageHandler extends AbstractAuthenticatedMessageHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SMTPBusiness           $smtpBusiness,
    )
    {

    }

    public function support(string $message, Session $session): bool
    {
        return str_ends_with($message, ".\r\n") &&
            (
                $session->isLastHandler(BeginDataMessageHandler::class) ||
                $session->isLastHandler(DataMessageHandler::class)
            );
    }

    public function authenticatedHandle(string $data, Session $session): string
    {
        $data = substr($data, 0, -5);   // Remove \r\n.\r\n
        $session->getMail()->addRaw($data);
        $this->smtpBusiness->setData($session->getMail());
        $session->getMail()->setInbox($session->getInbox());
        $this->entityManager->persist($session->getMail());
        $this->entityManager->flush();

        return '354 Message accepted for delivery';
    }

    public function getPriority(): int
    {
        return 1;
    }
}