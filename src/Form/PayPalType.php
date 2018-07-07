<?php


namespace App\Form;


use App\Entity\PayPal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayPalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coins', NumberType::class, [
                'label' => 'label.paypal.coins'
            ])->add('price', NumberType::class, [
                'label' => 'label.paypal.price'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayPal::class
        ]);
    }
}