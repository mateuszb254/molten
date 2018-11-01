<?php

namespace App\Security\Exception;

use App\Entity\Account;
use Exception;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountBannedException extends AccountStatusException
{
    private $account;

    public function __construct($message = "", $code = 0, Exception $previous = null, Account $account)
    {
        parent::__construct($message, $code, $previous);
        $this->setUser($account);
    }

    public function getMessageKey()
    {
        return 'account.banned';
    }

    public function getMessageData()
    {
        return [
            '%reason%' => $this->getUser()->getBanReason(),
            '%expires%' => $this->getUser()->getBanTime()->format('Y-m-d H:i:s')
        ];
    }
}