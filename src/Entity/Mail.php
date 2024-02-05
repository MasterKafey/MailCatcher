<?php

namespace App\Entity;

use App\Repository\MailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailRepository::class)]
class Mail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $raw = null;

    #[ORM\ManyToOne(targetEntity: Inbox::class, inversedBy: 'mails')]
    private ?Inbox $inbox = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isSeen = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function setRaw(?string $raw): self
    {
        $this->raw = $raw;
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

    public function isSeen(): bool
    {
        return $this->isSeen;
    }

    public function setIsSeen(bool $isSeen): self
    {
        $this->isSeen = $isSeen;
        return $this;
    }

    public function addRaw(string $raw): self
    {
        $this->raw .= $raw;
        return $this;
    }
}