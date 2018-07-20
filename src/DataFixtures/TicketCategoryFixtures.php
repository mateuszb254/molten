<?php

namespace App\DataFixtures;


use App\Entity\TicketCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TicketCategoryFixtures extends Fixture
{
    public const PAYMENTS_REFERENCE = 'payments';
    public const SITE_BUG_REFERENCE = 'site_bug';
    public const RULES_REFERENCE = 'rules';

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        $references = [self::PAYMENTS_REFERENCE, self::SITE_BUG_REFERENCE, self::RULES_REFERENCE];

        foreach ($this->getCategoriesData() as $categoryData) {
            $category = new TicketCategory();
            $category->setName($categoryData['name']);

            $manager->persist($category);

            if (!isset($references[0])) continue;

            $this->addReference($references[0], $category);
            array_shift($references);
        }

        $manager->flush();
    }

    /**
     * If  you want to add new category, append it at the end of array
     */
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