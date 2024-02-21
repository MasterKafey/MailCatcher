<?php

namespace App\Repository;

use App\Entity\TemplatedEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemplatedEmail>
 *
 * @method TemplatedEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplatedEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplatedEmail[]    findAll()
 * @method TemplatedEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class TemplatedEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplatedEmail::class);
    }
}