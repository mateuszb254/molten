<?php

namespace App\Form;

use App\Repository\DownloadLinkRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DownloadLinkCollectionType extends AbstractType
{
    private $downloadLinkRepository;

    public function __construct(DownloadLinkRepository $downloadLinkRepository)
    {
        $this->downloadLinkRepository = $downloadLinkRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('links', CollectionType::class, [
            'entry_type' => DownloadLinkType::class,
            'allow_add' => true,
            'allow_delete' => true
        ])->addEventListener(FormEvents::PRE_SET_DATA, [
            $this, 'onPreSetData'
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = [
            'links' => $this->downloadLinkRepository->findAll()
        ];

        $event->setData($data);
    }
}