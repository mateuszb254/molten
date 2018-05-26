<?php

namespace App\DataFixtures;


use App\Entity\TicketCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TicketCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        foreach($this->getCategoriesData() as $categoryData) {
            $category = new TicketCategory();
            $category->setName($categoryData['name']);

            $manager->persist($category);
        }

        $manager->flush();
    }

    private function getCategoriesData(): array
    {
        return [
            [
                'name' => 'Płatności'
            ],
            [
                'name' => 'Błąd na stronie',
            ],
            [
                'name' => 'Łamanie regulaminu'
            ]
        ];
    }
}