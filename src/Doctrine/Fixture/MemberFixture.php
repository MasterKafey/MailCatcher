<?php

namespace App\Doctrine\Fixture;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MemberFixture extends Fixture implements DependentFixtureInterface
{
    use AppFixtureTrait;

    public function load(ObjectManager $manager): void
    {
        $users = UserFixture::getReferences();
        foreach (ProjectFixture::getReferences() as $reference) {
            $project = $this->getReference($reference);
            shuffle($users);

            $member = (new Member())
                    ->setProject($project)
                    ->setRole(MemberRole::OWNER)
                    ->setUser($this->getReference($users[array_key_first($users)]))
                    ->setStatus(MemberStatus::ACCEPTED);

            $this->persist($member, $manager, 'MEMBER');

            $usersMember = array_slice($users, 1, random_int(1, count($users) - 1));
            foreach ($usersMember as $userReference) {
                $member = new Member();
                $member
                    ->setProject($project)
                    ->setRole([MemberRole::EDITOR, MemberRole::VIEWER][random_int(0, 1)])
                    ->setUser($this->getReference($userReference))
                    ->setStatus([MemberStatus::PENDING, MemberStatus::ACCEPTED, MemberStatus::REFUSED][random_int(0, 2)]);
                $this->persist($member, $manager, 'MEMBER');
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