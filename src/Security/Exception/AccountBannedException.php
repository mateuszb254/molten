<?php

namespace App\Security\Exception;

use App\Entity\Account;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountBannedException extends AccountStatusException
{
    public function getMessageKey()
    {
        return 'account.banned';
    }

    public function getMessageData()
    {
        $user = $this->getUser();

        if (!$user instanceof Account) {
            return [];
        }

        return [
            '%reason%' => $user->getBanReason(),
            '%expires%' => $user->getBanTime()->format('Y-m-d H:i:s')
        ];
    }
}