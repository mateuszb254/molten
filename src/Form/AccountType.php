<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountType extends AbstractType
{
    public const DEFAULT_VALUE_PASS = 'password';

    private $passwordEncoder;
    private $authorizationChecker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'label.login'
            ])->add('plainPassword', PasswordType::class, [
                'label' => 'label.password',
            ])->add('coins', TextType::class, [
                'label' => 'label.coins'
            ])->add('email', EmailType::class, [
                'label' => 'label.email'
            ])->add('code', TextType::class, [
                'label' => 'label.code',
            ])->addEventListener(FormEvents::PRE_SET_DATA, [
                $this, 'onPreSetData'
            ])->addEventListener(FormEvents::POST_SUBMIT, [
                $this, 'onPostSubmit'
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['default_value_pass'] = self::DEFAULT_VALUE_PASS;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $account = $event->getData();
        $form = $event->getForm();

        if ($this->authorizationChecker->isGranted('CHANGE_ROLE', $account)) {
            $form->add('role', EntityType::class, [
                'choice_translation_domain' => true,
                'class' => Role::class,
                'label' => 'roles'
            ]);
        }
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var $user Account */
        $user = $event->getForm()->getData();

        if ($user->getPlainPassword() === self::DEFAULT_VALUE_PASS) {
            return;
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
    }
}