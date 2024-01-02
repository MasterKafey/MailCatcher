<?php

namespace App\Doctrine\Fixture;

use App\Entity\Member;
use Doctrine\Persistence\ObjectManager;

/**
 * @method addReference(string $reference, object $entity)
 */
trait AppFixtureTrait
{
    private static array $references = [];

    public function persist(object $entity, ObjectManager $manager, string $reference, bool $flush = false, bool $addHash = true): void
    {
        $reference .= ($addHash ? spl_object_hash($entity) : '');
        self::$references[] = $reference;
        $manager->persist($entity);
        if ($flush) {
            $manager->flush();
        }
        $this->addReference($reference, $entity);
    }

    public static function getReferences(): array
    {
        return self::$references;
    }
}