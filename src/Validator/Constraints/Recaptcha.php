<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Recaptcha extends Constraint
{
    public $message = 'You must confirm the recaptcha';
}