<?php

namespace App\Form;

use App\Entity\ShopCategory;
use App\Repository\ShopCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ShopCategoryCollectionType extends AbstractType
{
    protected $categoryRepository;

    public function __construct(ShopCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categories', CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type' => ShopCategoryType::class
        ])->addEventListener(FormEvents::PRE_SET_DATA, [
            $this, 'onPreSetData'
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = [
            'categories' => $this->categoryRepository->findAll()
        ];

        $event->setData($data);
    }
}