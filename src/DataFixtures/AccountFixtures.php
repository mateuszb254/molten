<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_USER_REFERENCE = 'admin';
    public const USER_REFERENCE = 'user';

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUsersData() as $userData) {
            $user = new Account();
            $user->setLogin($userData['login']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setEmail($userData['email']);
            $user->setCode($userData['code']);
            $user->setQuestion($userData['question']);
            $user->setAnswer($userData['answer']);
            $user->setRole($userData['role']);
            $user->setCoins($userData['coins']);

            if (array_key_exists('banTime', $userData)) $user->setBanTime($userData['banTime']);
            if (array_key_exists('banReason', $userData)) $user->setBanReason($userData['banReason']);

            $manager->persist($user);

            if ($user->getLogin() === self::ADMIN_USER_REFERENCE) {
                $this->addReference(self::ADMIN_USER_REFERENCE, $user);
            }

            if ($user->getLogin() === self::USER_REFERENCE) {
                $this->addReference(self::USER_REFERENCE, $user);
            }
        }

        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            [
                'login' => 'global_admin',
                'password' => 'global_admin',
                'email' => 'global_admin@nagadev.pl',
                'code' => 11111,
                'question' => 'Is it you Mr. Admin?',
                'answer' => 'It\'s me',
                'role' => $this->getReference(RoleFixtures::ROLE_GLOBAL_ADMIN_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => 'admin',
                'password' => 'admin',
                'email' => 'admin@nagadev.pl',
                'code' => 11111,
                'question' => 'admin',
                'answer' => 'admin',
                'role' => $this->getReference(RoleFixtures::ROLE_ADMIN_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => 'moderator',
                'password' => 'moderator',
                'email' => 'moderator@nagadev.pl',
                'code' => 22222,
                'question' => 'Moderator',
                'answer' => 'Moderator!',
                'role' => $this->getReference(RoleFixtures::ROLE_MODERATOR_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => 'user',
                'password' => 'user',
                'email' => 'user@nagadev.pl',
                'code' => 22222,
                'question' => 'Am i just an user?',
                'answer' => 'Yes, but unique!',
                'role' => $this->getReference(RoleFixtures::ROLE_DEFAULT_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => 'user_banned',
                'password' => 'user',
                'email' => 'user_banned@nagadev.pl',
                'code' => 33333,
                'question' => 'Am i just an user?',
                'answer' => 'Yes, but banned. :(',
                'role' => $this->getReference(RoleFixtures::ROLE_DEFAULT_REFERENCE),
                'coins' => 0,
                'banTime' => (new \DateTime())->add(new \DateInterval('P3653D')),
                'banReason' => 'Illegal software'
            ]
        ];
    }

    public function getDependencies()
    {
        return [
            RoleFixtures::class
        ];
    }
}