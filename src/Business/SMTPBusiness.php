<?php

namespace App\Business;

use App\Entity\Mail;
use App\SMTP\MessageHandler\MessageHandlerInterface;
use App\SMTP\Session;
use PhpMimeMailParser\Parser;
use React\Socket\ConnectionInterface;

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
        $parser = new Parser();
        $parser->setText($mail->getRaw());
        $reflectionClass = new \ReflectionClass($mail);
        foreach ($parser->getHeaders() as $name => $value) {
            if ('date' === $name) {
                $name = 'sentAt';
                $value = new \DateTime($value);
            } else if  ('cc' === $name) {
                $value = explode(',', $value);
                $value = array_map(function($emailAddress) {
                    return trim($emailAddress);
                }, $value);
            }
            $propertyName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $name))));
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($mail, $value);
        }

        $mail->setText($parser->getMessageBody('text'));
        $mail->setHtml($parser->getMessageBody('html'));
    }
}