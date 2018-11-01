<?php

namespace App\Security\Checker;

use App\Entity\Account;
use App\Security\Exception\AccountBannedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Account) {
            return;
        }

        if ($user->isBanned()) {
            $exception = new AccountBannedException();
            $exception->setUser($user);

            throw $exception;
        }
    }
}