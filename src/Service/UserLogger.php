<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\UserLog;
use Doctrine\Common\Persistence\ObjectManager;

class UserLogger
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addLog(Account $account, string $type): void
    {
        $entityManager = $this->entityManager;

        $userLog = new UserLog();

        $userLog->setUser($account);
        $userLog->setType($type);

        $entityManager->persist($userLog);
        $entityManager->flush();
    }
}