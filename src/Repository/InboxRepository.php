<?php

namespace App\Repository;

use App\Entity\MemberStatus;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class InboxRepository extends EntityRepository
{
    public function findByUser(User $user): array
    {
        $queryBuilder = $this->createQueryBuilder('inbox');

        $queryBuilder
            ->join('inbox.project', 'project')
            ->join('project.members', 'members')
        ;

        $queryBuilder
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('members.user', ':user'),
                    $queryBuilder->expr()->in('members.status', ':status')
                )
            )
        ;

        $queryBuilder
            ->setParameter('user', $user)
            ->setParameter('status', [
                MemberStatus::ACCEPTED,
            ])
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}