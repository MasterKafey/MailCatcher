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

    #[ORM\Column(name: '`from`', type: Types::STRING)]
    private ?string $from = null;

    #[ORM\Column(name: '`to`', type: Types::STRING)]
    private ?string $to = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $recipients = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $cc = [];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $replyTo = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $mimeVersion = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $contentType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $sentAt = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $messageId = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $contentTransferEncoding = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $html = null;

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

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): self
    {
        $this->recipients = [];

        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }

        return $this;
    }

    public function addRecipient(string $recipient): self
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(array $cc): self
    {
        $this->cc = $cc;
        return $this;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    public function setReplyTo(?string $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function getMimeVersion(): ?string
    {
        return $this->mimeVersion;
    }

    public function setMimeVersion(?string $mimeVersion): self
    {
        $this->mimeVersion = $mimeVersion;
        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(?string $messageId): self
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getContentTransferEncoding(): ?string
    {
        return $this->contentTransferEncoding;
    }

    public function setContentTransferEncoding(?string $contentTransferEncoding): self
    {
        $this->contentTransferEncoding = $contentTransferEncoding;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;
        return $this;
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

    public function addRaw(string $raw): self
    {
        $this->raw .= $raw;
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
}