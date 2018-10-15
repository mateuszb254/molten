<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    const ROLE_GLOBAL_ADMIN_REFERENCE = 'ROLE_GLOBAL_ADMIN';
    const ROLE_ADMIN_REFERENCE = 'ROLE_ADMIN';
    const ROLE_MODERATOR_REFERENCE = 'ROLE_MODERATOR';
    const ROLE_DEFAULT_REFERENCE = 'ROLE_USER';

    public function load(ObjectManager $manager)
    {
        $this->loadRoles($manager);
    }

    private function loadRoles(ObjectManager $manager)
    {
        foreach ($this->getRoleNames() as $name) {
            $role = new Role($name);

            $manager->persist($role);

            $this->addReference($name, $role);
        }

        $manager->flush();
    }

    private function getRoleNames()
    {
        return [
            Account::GLOBAL_ADMIN_ROLE,
            Account::ADMIN_ROLE,
            Account::MODERATOR_ROLE,
            Account::DEFAULT_ROLE
        ];
    }

}