<?php

namespace App\DataFixtures;

use App\Entity\ShopCategory;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ShopCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        foreach ($this->getCategoriesData() as $categoryData) {
            $category = new ShopCategory();
            $category->setName($categoryData['name']);
            $category->setSlug(Slugger::slugify($category->getName()));
            $category->setStatus($categoryData['status']);

            $manager->persist($category);

            $this->addReference('category-'.$category->getName(), $category);
        }

        $manager->flush();
    }

    private function getCategoriesData(): array
    {
        return [
            [
                'name' => 'Przedmioty unikatowe',
                'status' => ShopCategory::STATUS_ACTIVE
            ],
            [
                'name' => 'Przedmioty testowe',
                'status' => ShopCategory::STATUS_ACTIVE
            ],
            [
                'name' => 'Różności',
                'status' => ShopCategory::STATUS_ACTIVE
            ],
            [
                'name' => 'Kategoria która jest wyłączona',
                'status' => ShopCategory::STATUS_INACTIVE
            ],
        ];
    }
}