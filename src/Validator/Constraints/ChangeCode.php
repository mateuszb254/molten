<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ChangeCode extends Constraint
{
    public $message = 'This code is not valid.';
}