<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\ShopProduct;
use App\Form\Transformer\ItemToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopProductType extends AbstractType
{
    protected $itemTransformer;

    public function __construct(ItemToStringTransformer $itemTransformer)
    {
        $this->itemTransformer = $itemTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('item', TextType::class, [
                'label' => 'shop.product.name',
                'invalid_message' => 'item.notFound'
            ])
            ->add('description', TextType::class, [
                'label' => 'shop.product.description'
            ])
            ->add('price', NumberType::class, [
                'label' => 'shop.product.price'
            ])
            ->add('position', HiddenType::class);

        $builder->get('item')->addModelTransformer($this->itemTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShopProduct::class,
        ]);
    }
}
