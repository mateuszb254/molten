<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ChangeEmail extends Constraint
{
    public $message = 'This e-mail is not valid.';
}