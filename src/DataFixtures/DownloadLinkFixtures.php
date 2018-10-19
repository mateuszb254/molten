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
        $position = 0;
        foreach ($this->getDownloadLinksData() as $link) {
            $downloadLink = new DownloadLink();
            $downloadLink->setName($link['name']);
            $downloadLink->setUrl($link['url']);
            $downloadLink->setPosition($position++);

            $manager->persist($downloadLink);
        }

        $manager->flush();
    }

    private function getDownloadLinksData(): array
    {
        return [
            [
                'name' => 'Example #1',
                'url' => 'http://e1.example.example',
            ],
            [
                'name' => 'Example #2',
                'url' => 'http://e2.example.example',
            ],
            [
                'name' => 'Example #3',
                'url' => 'http://e3.example.example',
            ],
            [
                'name' => 'Example #4',
                'url' => 'http://e4.example.example',
            ],
        ];
    }
}