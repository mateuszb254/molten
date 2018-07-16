<?php

namespace App\Form;

use App\Validator\Constraints\ExistentEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgottenPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => 'label.email',
            'constraints' => new ExistentEmail([
                'message' => 'email.nonexistent'
            ])
        ]);
    }
}