<?php

namespace App\Doctrine\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    use AppFixtureTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $defaultUser = new User();
        $defaultUser
            ->setEmail('mail-catcher@dyosis.com')
            ->setPlainPassword('azerty');

        $this->persist($defaultUser, $manager, 'USER');

        for ($i = 0; $i < 10; ++$i) {
            $user = (new User())
                ->setEmail($faker->email)
                ->setPlainPassword($faker->password);
            $this->persist($user, $manager, 'USER');
        }

        $manager->flush();
    }
}