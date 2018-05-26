<?php
namespace App\Validator\Constraints;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ChangeEmailValidator extends ConstraintValidator
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if($user->getEmail() !== $value) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}