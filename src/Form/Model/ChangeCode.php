<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangeCode
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "account.password.incorrect"
     * )
     */
    protected $plainPassword;

    protected $newCode;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $oldEmail)
    {
        $this->plainPassword = $oldEmail;

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