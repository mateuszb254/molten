<?php
namespace App\Form;

use App\Form\Model\ChangeEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', PasswordType::class, [
            'label' => 'label.password'
        ])
            ->add('newEmail', RepeatedType::class, [
                'type' => EmailType::class,
                'first_options' => [
                    'label' => 'label.email.new'
                ],
                'second_options' => [
                    'label' => 'label.email.repeat'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class' => ChangeEmail::class
       ]);
    }
}