<?php

namespace App\SMTP\MessageHandler;

use App\Entity\Mail;
use App\SMTP\MessageHandler\Auth\AbstractAuthenticatedMessageHandler;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validation;

class RcptToMessageHandler extends AbstractAuthenticatedMessageHandler
{
    public function support(string $message, Session $session): bool
    {
        return str_starts_with($message, 'RCPT TO:');
    }

    public function authenticatedHandle(string $data, Session $session): string
    {
        try {
            $validator = Validation::createValidator();
            $emailConstraint = new EmailConstraint();
            preg_match('/RCPT TO:<(.+?)>/', $data, $matches);
            $emailAddress = $matches[1] ?? null;
            $violations = $validator->validate($emailAddress, $emailConstraint);
            if (0 !== count($violations)) {
                throw new \Exception($violations->get(0)->getMessage());
            }
            $session->getMail()->addRecipient($emailAddress);

            return '250 OK';

        } catch (\Exception $exception) {
            return '501 ' . $exception->getMessage();
        }
    }
}