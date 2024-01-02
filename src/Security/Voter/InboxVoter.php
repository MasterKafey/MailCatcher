<?php

namespace App\Security\Voter;

use App\Entity\Inbox;
use App\Entity\Member;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class InboxVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Inbox) {
            return false;
        }

        return true;
    }

    public function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $rights = $this->getRights($subject, $user);

        return in_array($attribute, $rights);
    }

    public function getRights(Inbox $inbox, User $user): array
    {
        if ($inbox->getProject()->getCreator() === $user) {
            return [self::VIEW, self::EDIT];
        }

        /** @var Member $member */
        foreach ($inbox->getProject()->getMembers() as $member)
        {
            if ($member->getUser() === $user) {
                // TODO: Need to be rework with right system
                return [self::VIEW, self::EDIT];
            }
        }
        return [];
    }

}