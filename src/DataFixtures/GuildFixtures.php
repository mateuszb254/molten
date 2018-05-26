<?php

namespace App\DataFixtures;

use App\Entity\Guild;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GuildFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadPlayers($manager);
    }

    private function loadPlayers(ObjectManager $manager)
    {
        foreach ($this->getGuilds() as $guildData) {
            $guild = new Guild();
            $guild->setName($guildData['name']);
            $guild->setWins($guildData['wins']);
            $guild->setLoses($guildData['loses']);
            $guild->setPoints($guildData['points']);
            $guild->setKingdom($guildData['kingdom']);

            $manager->persist($guild);
        }

        $manager->flush();
    }

    private function getGuilds()
    {
        $guilds = [];

        for ($i = 1; $i <= 250; $i++) {
            $wins = mt_rand(0, 50);
            $loses = mt_rand(0, 50);
            $points = ($wins * 1000) - ($loses * 750) + 5000;

            $guild = [
                'name' => 'Guild_' . $i,
                'wins' => $wins,
                'loses' => $loses,
                'points' => $points,
                'kingdom' => mt_rand(1, 3)
            ];

            $guilds[] = $guild;
        }

        return $guilds;
    }
}