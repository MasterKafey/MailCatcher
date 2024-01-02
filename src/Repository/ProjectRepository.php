<?php

namespace App\Repository;

use App\Entity\MemberStatus;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function findByUser(User $user): iterable
    {
        $queryBuilder = $this->createQueryBuilder('project');
        $queryBuilder->leftJoin('project.members', 'members');

        $queryBuilder->where(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('members.user', ':user'),
                $queryBuilder->expr()->in('members.status', ':status')
            )
        );

        $queryBuilder
            ->setParameter('user', $user)
            ->setParameter('status', [MemberStatus::ACCEPTED, MemberStatus::PENDING])
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}