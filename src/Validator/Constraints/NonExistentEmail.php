<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NonExistentEmail extends Constraint
{
    public $message = 'E-mail doesn\'t exist';
}