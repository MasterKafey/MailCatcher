<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Member::class, cascade: ['persist','remove'])]
    private Collection $members;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Inbox::class)]
    private Collection $inboxes;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->inboxes = new ArrayCollection();
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

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function setMembers(Collection $members): self
    {
        $this->members = $members;
        return $this;
    }

    public function addMember(Member $member): self
    {
        $this->members[] = $member;
        $member->setProject($this);
        return $this;
    }

    public function getInboxes(): Collection
    {
        return $this->inboxes;
    }

    public function setInboxes(Collection $inboxes): self
    {
        $this->inboxes = $inboxes;
        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->getCreatorMember()?->getUser();
    }

    public function getCreatorMember(): ?Member
    {
        foreach ($this->members as $member) {
            if ($member->getRole() === MemberRole::OWNER) {
                return $member;
            }
        }

        return null;
    }

    public function getAcceptedMembers(): array
    {
        return $this->getMembersByStatus(MemberStatus::ACCEPTED);
    }

    public function getPendingMembers(): array
    {
        return $this->getMembersByStatus(MemberStatus::PENDING);
    }

    public function getMembersByStatus(MemberStatus $status): array
    {
        $members = array_filter($this->members->toArray(), function (Member $member) use ($status) {
            return $member->getStatus() === $status;
        });

        $order = [
            MemberRole::OWNER,
            MemberRole::EDITOR,
            MemberRole::VIEWER,
        ];

        usort($members, function (Member $a, Member $b) use ($order) {
            if ($a->getRole() === $b->getRole()) {
                return 0;
            }

            $aKey = array_search($a->getRole(), $order);
            $bKey = array_search($b->getRole(), $order);

            return $aKey - $bKey;
        });

        return $members;
    }

    public function getMemberByUser(User $user): ?Member
    {
        foreach ($this->members as $member) {
            if ($member->getUser() === $user) {
                return $member;
            }
        }

        return null;
    }

    public function setCreator(User $user, MemberRole $nextMemberRole = MemberRole::EDITOR): self
    {
        $creator = $this->getCreatorMember();
        $member = $this->getMemberByUser($user);

        $member->setRole(MemberRole::OWNER);
        $creator->setRole($nextMemberRole);
        return $this;
    }
}