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

    private function loadProducts(ObjectManager $manager)
    {
        foreach($this->getProducts() as $productData) {
            $product = new ShopProduct();
            $product->setName($productData['name']);
            $product->setCategory($productData['category']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getProducts()
    {
        return [
            [
                'name' => 'Item 1',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 1',
                'price' => 100
            ],
            [
                'name' => 'Item 2',
                'category' => $this->getReference('category-Przedmioty testowe'),
                'description' => 'Description of Item 2',
                'price' => 60
            ],
            [
                'name' => 'Item 3',
                'category' => $this->getReference('category-Przedmioty testowe'),
                'description' => 'Description of Item 3',
                'price' => 35
            ],
            [
                'name' => 'Item 4',
                'category' => $this->getReference('category-Przedmioty testowe'),
                'description' => 'Description of Item 4',
                'price' => 22
            ],
            [
                'name' => 'Item 5',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 5',
                'price' => 55
            ],
            [
                'name' => 'Item 6',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 6',
                'price' => 55
            ],
            [
                'name' => 'Item 7',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 7',
                'price' => 55
            ],
            [
                'name' => 'Item 8',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 8',
                'price' => 55
            ],
            [
                'name' => 'Item 9',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 9',
                'price' => 55
            ],
            [
                'name' => 'Item 10',
                'category' => $this->getReference('category-Przedmioty unikatowe'),
                'description' => 'Description of Item 10',
                'price' => 55
            ],
        ];
    }

    public function getDependencies()
    {
        return [
          ShopCategoryFixtures::class
        ];
    }
}