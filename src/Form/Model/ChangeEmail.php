<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangeEmail
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "account.password.incorrect"
     * )
     */
    protected $plainPassword;

    protected $newEmail;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $oldEmail)
    {
        $this->plainPassword = $oldEmail;

        return $this;
    }

    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    public function setNewEmail(?string $newEmail): self
    {
        $this->newEmail = $newEmail;

        return $this;
    }
}