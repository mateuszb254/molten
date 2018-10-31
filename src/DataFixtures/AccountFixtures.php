<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    public const GLOBAL_ADMIN_REFERENCE_NAME = 'global_admin';
    public const ADMIN_USER_REFERENCE_NAME = 'admin';
    public const MODERATOR_REFERENCE_NAME = 'moderator';
    public const USER_REFERENCE_NAME = 'user';
    public const USER_BANNED_REFERENCE_NAME = 'user_banned';

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadAccounts($manager);
    }

    private function loadAccounts(ObjectManager $manager): void
    {
        foreach ($this->getAccountsData() as $accountData) {
            $account = new Account();
            $account->setLogin($accountData['login']);
            $account->setPassword($this->encoder->encodePassword($account, $accountData['password']));
            $account->setEmail($accountData['email']);
            $account->setCode($accountData['code']);
            $account->setQuestion($accountData['question']);
            $account->setAnswer($accountData['answer']);
            $account->setRole($accountData['role']);
            $account->setCoins($accountData['coins']);

            if (array_key_exists('banTime', $accountData)) $account->setBanTime($accountData['banTime']);
            if (array_key_exists('banReason', $accountData)) $account->setBanReason($accountData['banReason']);

            $manager->persist($account);

           $this->addReference($account->getLogin(), $account);
        }

        $manager->flush();
    }

    private function getAccountsData(): array
    {
        return [
            [
                'login' => self::GLOBAL_ADMIN_REFERENCE_NAME,
                'password' => 'global_admin',
                'email' => 'global_admin@nagadev.pl',
                'code' => 11111,
                'question' => 'Is it you Mr. Admin?',
                'answer' => 'It\'s me',
                'role' => $this->getReference(RoleFixtures::ROLE_GLOBAL_ADMIN_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => self::ADMIN_USER_REFERENCE_NAME,
                'password' => 'admin',
                'email' => 'admin@nagadev.pl',
                'code' => 11111,
                'question' => 'admin',
                'answer' => 'admin',
                'role' => $this->getReference(RoleFixtures::ROLE_ADMIN_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => self::MODERATOR_REFERENCE_NAME,
                'password' => 'moderator',
                'email' => 'moderator@nagadev.pl',
                'code' => 22222,
                'question' => 'Moderator',
                'answer' => 'Moderator!',
                'role' => $this->getReference(RoleFixtures::ROLE_MODERATOR_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => self::USER_REFERENCE_NAME,
                'password' => 'user',
                'email' => 'user@nagadev.pl',
                'code' => 22222,
                'question' => 'Am i just an user?',
                'answer' => 'Yes, but unique!',
                'role' => $this->getReference(RoleFixtures::ROLE_DEFAULT_REFERENCE),
                'coins' => 5000
            ],
            [
                'login' => self::USER_BANNED_REFERENCE_NAME,
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