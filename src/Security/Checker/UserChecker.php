<?php

namespace App\Security\Checker;

use App\Exception\AccountBannedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
    }

    public function checkPostAuth(UserInterface $user)
    {
        if ($user->isBanned()) {
            throw new AccountBannedException(null, null, null, $user);
        }
    }
}