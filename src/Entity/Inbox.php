<?php

namespace App\Entity;

use App\Repository\InboxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InboxRepository::class)]
class Inbox
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'inbox', targetEntity: Mail::class)]
    private Collection $mails;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'inboxes')]
    private ?Project $project = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $identifier = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $mailNumberSent = 0;

    public function __construct()
    {
        $this->mails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getMails(): Collection
    {
        return $this->mails;
    }

    public function setMails(iterable $mails): self
    {
        $this->mails = new ArrayCollection();

        foreach ($mails as $mail) {
            $this->addMail($mail);
        }
        return $this;
    }

    public function addMail(Mail $mail): self
    {
        $this->mails[] = $mail;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getMailNumberSent(): int
    {
        return $this->mailNumberSent;
    }

    public function setMailNumberSent(int $mailNumberSent): self
    {
        $this->mailNumberSent = $mailNumberSent;
        return $this;
    }
}