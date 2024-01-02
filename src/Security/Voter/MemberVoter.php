<?php

namespace App\Security\Voter;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberVoter extends Voter
{
    const VIEW = 'VIEW';
    const UPDATE = 'UPDATE';
    const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::UPDATE, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Member) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        if (!$subject instanceof Member) {
            throw new \LogicException('Should never be called');
        }

        $currentMember = $subject->getProject()->getMemberByUser($currentUser);

        if (null === $currentMember) {
            // Change this if admin need to access other user project
            return false;
        }

        if ($currentMember->getRole() === MemberRole::OWNER) {
            return true;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $currentMember),
            self::UPDATE => $this->canUpdate($subject, $currentMember),
            self::DELETE => $this->canDelete($subject, $currentMember),
            default => throw new \LogicException('Should never be called'),
        };
    }

    private function canView(Member $member, Member $currentMember): bool
    {
        if ($this->canUpdate($member, $currentMember)) {
            return true;
        }

        return in_array($currentMember->getRole(), [MemberRole::OWNER, MemberRole::EDITOR, MemberRole::VIEWER]);
    }

    private function canUpdate(Member $member, Member $currentMember): bool
    {
        if ($member->getRole() === MemberRole::OWNER) {
            // The owner is the only one able to update himself
            // The check of the owner is already made in voteOnAttribute
            return false;
        }

        return in_array($currentMember->getRole(), [MemberRole::OWNER, MemberRole::EDITOR]);
    }

    private function canDelete(Member $member, Member $user): bool
    {
        return $this->canUpdate($member, $user);
    }
}