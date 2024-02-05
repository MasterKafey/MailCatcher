<?php

namespace App\Utils;

use App\Entity\Mail;
use PhpMimeMailParser\Parser;

class MailParser
{
    private Parser $parser;

    public function __construct(
        private readonly Mail $mail
    )
    {
        $this->parser = new Parser();
        $this->parser->setText($mail->getRaw());
    }

    public function getDate(): ?\DateTime
    {
        $date = $this->parser->getHeader('date');

        if (null === $date) {
            return null;
        }

        return new \DateTime($date);
    }

    public function getSubject(): ?string
    {
        return $this->parser->getHeader('subject');
    }

    public function getTo(): ?string
    {
        return $this->parser->getHeader('to');
    }

    public function getFrom(): ?string
    {
        return $this->parser->getHeader('from');
    }

    public function getHtml(): string
    {
        return implode($this->parser->getInlineParts('html'));
    }

    public function getText(): string
    {
        return implode($this->parser->getInlineParts());
    }

    public function getAttachments(): array
    {
        return $this->parser->getAttachments();
    }

    public function getMail(): Mail
    {
        return $this->mail;
    }
}