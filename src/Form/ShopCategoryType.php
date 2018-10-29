<?php

namespace App\Form;

use App\Entity\ShopCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'label.category'
            ])->add('status', ChoiceType::class, [
                'choice_translation_domain' => true,
                'choices' => [
                    'turned.off' => ShopCategory::STATUS_INACTIVE,
                    'turned.on' => ShopCategory::STATUS_ACTIVE,
                ]
            ])->add('position', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShopCategory::class,
        ]);
    }
}
