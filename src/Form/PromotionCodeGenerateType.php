<?php

namespace App\Form;

use App\Entity\PromotionCode;
use App\Form\Model\PromotionCodeGenerate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class PromotionCodeGenerateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', NumberType::class, [
            'label' => 'label.promotion_code.amount'
        ])->add('value', NumberType::class, [
            'label' => 'label.promotion_code.value'
        ])->add('type', ChoiceType::class, [
            'choices' => [
                'yes' => PromotionCode::ONE_PER_USER_TYPE,
                'no' => PromotionCode::SIMPLE_TYPE,
            ],
            'data' => PromotionCode::SIMPLE_TYPE,
            'label' => 'label.promotion_code.onePerUser'
        ])->add('tag', TextType::class, [
            'label' => 'label.promotion_code.tag',
            'required' => false
        ])->add('expires', DateTimeType::class, [
            'label' => 'label.promotion_code.expires',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromotionCodeGenerate::class,
            'validation_groups' => function (Form $form) {
                $promotionCodeGenerate = $form->getData();

                if ($promotionCodeGenerate->getType() === PromotionCode::ONE_PER_USER_TYPE) {
                    return [
                        'default', 'require_tag'
                    ];
                }
            }
        ]);
    }
}