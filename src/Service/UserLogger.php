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

    public function addLog(Account $account, string $type, string $additional = null): void
    {
        $entityManager = $this->entityManager;

        $userLog = new UserLog();

        $userLog->setUser($account);
        $userLog->setType($type);

        if ($additional !== null) {
            $userLog->setAdditional($additional);
        }

        $entityManager->persist($userLog);
        $entityManager->flush();
    }
}