<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadPlayers($manager);
    }

    private function loadPlayers(ObjectManager $manager)
    {
        foreach ($this->getPlayers() as $playerData) {
            $player = new Player();
            $player->setName($playerData['name']);
            $player->setLevel($playerData['level']);
            $player->setKingdom($playerData['kingdom']);

            $manager->persist($player);
        }

        $manager->flush();
    }

    private function getPlayers()
    {
        $players = [];

        for ($i = 1; $i <= 250; $i++) {
            $players[] = [
                'name' => 'Player' . $i,
                'level' => mt_rand(1, 250),
                'kingdom' => mt_rand(1, 3)
            ];
        }

        return $players;
    }
}