<?php

namespace App\DataFixtures;

use App\Entity\ShopCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ShopCategoryFixtures extends Fixture
{
    public const CATEGORY_1_REFERENCE_NAME = 'Category-1';
    public const CATEGORY_2_REFERENCE_NAME = 'Category-2';
    public const CATEGORY_3_REFERENCE_NAME = 'Category-3-Inactive';

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        $position = 0;
        foreach ($this->getCategoriesData() as $categoryData) {
            $category = new ShopCategory();
            $category->setName($categoryData['name']);
            $category->setStatus($categoryData['status']);
            $category->setPosition($position++);

            $manager->persist($category);

            $this->addReference($categoryData['name'], $category);
        }

        $manager->flush();
    }

    private function getCategoriesData(): array
    {
        return [
            [
                'name' => self::CATEGORY_1_REFERENCE_NAME,
                'status' => ShopCategory::STATUS_ACTIVE
            ],
            [
                'name' => self::CATEGORY_2_REFERENCE_NAME,
                'status' => ShopCategory::STATUS_ACTIVE
            ],
            [
                'name' => self::CATEGORY_3_REFERENCE_NAME,
                'status' => ShopCategory::STATUS_INACTIVE
            ],
        ];
    }
}