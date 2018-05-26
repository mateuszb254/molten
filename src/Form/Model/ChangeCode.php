<?php

namespace App\Form\Model;

use App\Validator\Constraints as AcmeAssert;

class ChangeCode
{
    /**
     * @AcmeAssert\ChangeCode(message="account.code.change")
     */
    protected $oldCode;

    protected $newCode;

    public function getOldCode()
    {
        return $this->oldCode;
    }

    public function setOldCode(string $oldCode)
    {
        $this->oldCode = $oldCode;

        return $this;
    }

    public function getNewCode()
    {
        return $this->newCode;
    }

    public function setNewCode($newCode)
    {
        $this->newCode = $newCode;

        return $this;
    }
}