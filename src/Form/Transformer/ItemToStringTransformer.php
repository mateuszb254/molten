<?php

namespace App\Form\Transformer;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ItemToStringTransformer implements DataTransformerInterface
{
    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param Item|null $value
     * @return string
     */
    public function transform($value)
    {
        if (!$value) {
            return '';
        }

        return $value->getName();
    }

    /**
     * @param string $value
     * @throws TransformationFailedException
     * @return Item
     */
    public function reverseTransform($value)
    {
        $item = $this->itemRepository->findItemByName($value);

        if (!$item) throw new TransformationFailedException();

        return $item;
    }
}