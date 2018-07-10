<?php

namespace App\Validator\Constraints;

use App\Repository\AccountRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistentEmailValidator extends ConstraintValidator
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$this->accountRepository->findAccountByEmail($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}