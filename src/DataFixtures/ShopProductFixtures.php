<?php

namespace App\DataFixtures;

use App\Entity\ShopProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ShopProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
    }

    public function loadProducts(ObjectManager $manager): void
    {
        foreach ($this->getProductsData() as $productData) {
            $product = new ShopProduct();
            $product->setItem($productData['item']);
            $product->setPosition($productData['position']);
            $product->setCategory($productData['category']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getProductsData(): array
    {
        $productsData = [];

        $positions = [
            ShopCategoryFixtures::CATEGORY_1_REFERENCE_NAME => 0,
            ShopCategoryFixtures::CATEGORY_2_REFERENCE_NAME => 0
        ];

        $prices = [100, 200, 300, 400, 500, 600, 700, 1000];
        for ($i = 0; $i <= 10; $i++) {
            $categoryReferenceName = mt_rand(0, 1) ? ShopCategoryFixtures::CATEGORY_1_REFERENCE_NAME : ShopCategoryFixtures::CATEGORY_2_REFERENCE_NAME;

            $productsData[] = [
                'item' => $this->getReference('item-' . mt_rand(0, ItemFixtures::$endIdReference)),
                'category' => $this->getReference($categoryReferenceName),
                'description' => 'Description...',
                'price' => $prices[array_rand($prices)],
                'position' => $positions[$categoryReferenceName]
            ];

            $positions[$categoryReferenceName]++;
        }

        return $productsData;
    }

    public function getDependencies()
    {
        return [
            ItemFixtures::class, ShopCategoryFixtures::class
        ];
    }
}