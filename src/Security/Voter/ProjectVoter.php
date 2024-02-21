<?php

namespace App\Security\Voter;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Project) {
            return false;
        }

        return in_array($attribute, [self::VIEW, self::UPDATE, self::DELETE]);
    }

    public function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        $member = $subject->getMemberByUser($user);

        if ($member === null || $member->getStatus() !== MemberStatus::ACCEPTED) {
            return false;
        }

        if (!$member instanceof Member) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($member),
            self::UPDATE => $this->canUpdate($member),
            self::DELETE => $this->canDelete($member),
            default => throw new \LogicException('Should never be called'),
        };
    }

    private function canView(Member $member): bool
    {
        return in_array($member->getRole(), [MemberRole::OWNER, MemberRole::EDITOR, MemberRole::VIEWER]);
    }

    private function canUpdate(Member $member): bool
    {
        return in_array($member->getRole(), [MemberRole::EDITOR, MemberRole::OWNER]);
    }

    private function canDelete(Member $member): bool
    {
        return $member->getRole() == MemberRole::OWNER;
    }
}