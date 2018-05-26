<?php
namespace App\Form;

use App\Form\Model\ChangeCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldCode', TextType::class, [
            'label' => 'label.code.old'
        ])
            ->add('newCode', RepeatedType::class, [
                'type' => TextType::class,
                'first_options' => [
                    'label' => 'label.code.new'
                ],
                'second_options' => [
                    'label' => 'label.code.repeat'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeCode::class
        ]);
    }
}