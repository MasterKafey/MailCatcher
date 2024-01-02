<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {

    }

    public function prePersist(User $user): void
    {
        $this->hashPassword($user);
    }

    public function preUpdate(User $user): void
    {
        $this->hashPassword($user);
    }

    private function hashPassword(User $user): void
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $password = $this->hasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }
}