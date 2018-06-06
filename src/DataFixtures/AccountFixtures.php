<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin';

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
            $user->setRoles($userData['roles']);
            $user->setCoins($userData['coins']);

            if(array_key_exists('banTime', $userData)) $user->setBanTime($userData['banTime']);
            if(array_key_exists('banReason', $userData)) $user->setBanReason($userData['banReason']);

            $manager->persist($user);

            if($user->getLogin() === self::ADMIN_USER_REFERENCE) {
                $this->addReference(self::ADMIN_USER_REFERENCE, $user);
            }
        }

        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            [
                'login' => 'admin',
                'password' => 'admin',
                'email' => 'admin@nagadev.pl',
                'code' => 11111,
                'question' => 'Is it you Mr. Admin?',
                'answer' => 'It\'s me',
                'roles' => [
                    'ROLE_USER', 'ROLE_ADMIN'
                ],
                'coins' => 5000
            ],
            [
                'login' => 'user',
                'password' => 'user',
                'email' => 'user@nagadev.pl',
                'code' => 22222,
                'question' => 'Am i just an user?',
                'answer' => 'Yes, but unique!',
                'roles' => [
                    'ROLE_USER'
                ],
                'coins' => 5000
            ],
            [
                'login' => 'user_banned',
                'password' => 'user',
                'email' => 'user_banned@nagadev.pl',
                'code' => 33333,
                'question' => 'Am i just an user?',
                'answer' => 'Yes, but banned. :(',
                'roles' => [
                    'ROLE_USER'
                ],
                'coins' => 0,
                'banTime' => (new \DateTime())->add(new \DateInterval('P1D')),
                'banReason' => 'Illegal software'
            ]
        ];
    }
}