<?php

namespace App\DataFixtures;

use App\Entity\DownloadLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DownloadLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadDownloadLinks($manager);
    }

    public function loadDownloadLinks(ObjectManager $manager): void
    {
        foreach ($this->getDownloadLinksData() as $link) {
            $downloadLink = new DownloadLink();
            $downloadLink->setName($link['name']);
            $downloadLink->setUrl($link['url']);

            $manager->persist($downloadLink);
        }

        $manager->flush();
    }

    private function getDownloadLinksData(): array
    {
        return [
            [
                'name' => 'Example #1',
                'url' => '#'
            ],
            [
                'name' => 'Example #2',
                'url' => '#'
            ],
            [
                'name' => 'Example #3',
                'url' => '#'
            ],
            [
                'name' => 'Example #4',
                'url' => '#'
            ],
        ];
    }
}