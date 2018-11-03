<?php

namespace App\Form;

use App\Entity\Account;
use App\Form\Type\RecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'label.login'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'label.password'],
                'second_options' => ['label' => 'label.password.repeat'],
            ])
            ->add('email', TextType::class, [
                'label' => 'label.email'
            ])
            ->add('code', TextType::class, [
                'label' => 'label.code'
            ])
            ->add('question', TextType::class, [
                'label' => 'label.question'
            ])
            ->add('answer', TextType::class, [
                'label' => 'label.answer'
            ])
            ->add('rules', CheckboxType::class, array(
                'label' => 'label.terms.statement',
                'mapped' => false,
                'constraints' => new IsTrue([
                    'message' => 'terms'
                ]),
                'required' => true
            ))
            ->add('recaptcha', RecaptchaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'required' => false
        ]);
    }
}