<?php

namespace App\Form\Model;

use App\Validator\Constraints as AcmeAssert;

class ChangeEmail
{
    /**
     * @AcmeAssert\ChangeEmail(message="account.email.change")
     */
    protected $oldEmail;

    protected $newEmail;

    public function getOldEmail()
    {
        return $this->oldEmail;
    }

    public function setOldEmail($oldEmail)
    {
        $this->oldEmail = $oldEmail;
    }

    public function getNewEmail()
    {
        return $this->newEmail;
    }

    public function setNewEmail($newEmail)
    {
        $this->newEmail = $newEmail;
    }
}