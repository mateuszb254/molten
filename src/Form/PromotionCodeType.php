<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class PromotionCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('promotion_code', TextType::class, [
            'label' => 'label.promotion_code',
            'constraints' => [
                new Length(8, 8)
            ]
        ]);
    }
}