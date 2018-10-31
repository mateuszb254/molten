<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ItemFixtures extends Fixture
{
    const DATA_FILE = __DIR__ . '/Data/items.txt';

    public static $endIdReference = 0;

    public function load(ObjectManager $manager): void
    {
        $this->loadItems($manager);
    }

    private function loadItems(ObjectManager $manager): void
    {
        $itemsData = $this->splitStringToArrayOfData();

        foreach ($itemsData as $key => $itemData) {
            $item = $this->createObjectFromArray($itemData);
            $manager->persist($item);

            $this->addReference('item-' . $key, $item);
        }

        self::$endIdReference = $key ?? 0;

        $manager->flush();
    }

    private function createObjectFromArray(array $itemData): Item
    {
        $item = new Item();

        $reflectionItem = new \ReflectionClass($item);

        $offset = 0;
        foreach ($reflectionItem->getProperties() as $itemProperty) {
            if ($itemProperty->getName() === 'vnum') continue;

            $itemProperty->setAccessible(true);
            $itemProperty->setValue($item, $itemData[$offset]);

            $offset++;
        };

        return $item;
    }

    private function splitStringToArrayOfData(): array
    {
        $stringOfData = trim($this->getStringOfItemData());
        $itemRows = explode(PHP_EOL, $stringOfData);

        $itemsData = [];
        foreach ($itemRows as $itemRow) {
            $itemsData[] = explode(', ', $itemRow);
        }

        return $itemsData;
    }

    private function getStringOfItemData(): string
    {
        return file_get_contents(self::DATA_FILE);
    }
}