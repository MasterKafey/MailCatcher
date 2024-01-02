<?php

namespace App\Doctrine\Fixture;

use App\Entity\Inbox;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InboxFixture extends Fixture implements DependentFixtureInterface
{
    use AppFixtureTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $projectsReferences = ProjectFixture::getReferences();
        foreach ($projectsReferences as $projectReference) {
            $project = $this->getReference($projectReference);
            for ($i = random_int(0, 2); $i < 3; ++$i) {
                $inbox = new Inbox();
                $inbox
                    ->setProject($project)
                    ->setName($faker->domainName)
                ;
                $this->persist($inbox, $manager, 'INBOX');
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixture::class,
        ];
    }
}