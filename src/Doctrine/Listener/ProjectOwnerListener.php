<?php

namespace App\Doctrine\Listener;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use App\Entity\Project;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectOwnerListener
{
    public function __construct(
        private readonly Security $security,
    )
    {

    }

    public function prePersist(Project $project): void
    {
        if (null === $this->security->getUser()) {
            return;
        }

        /** @var Member $member */
        foreach ($project->getMembers() as $member) {
            if ($member->getRole() === MemberRole::OWNER) {
                return;
            }
        }

        $member = new Member();
        $member
            ->setRole(MemberRole::OWNER)
            ->setStatus(MemberStatus::ACCEPTED)
            ->setUser($this->security->getUser());

        $project->addMember($member);
    }
}