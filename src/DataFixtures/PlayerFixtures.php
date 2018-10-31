<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    private const AMOUNT_OF_PLAYERS = 250;

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
            $player->setAccount($playerData['account']);

            $manager->persist($player);
        }

        $manager->flush();
    }

    private function getPlayers()
    {
        $account = [
            AccountFixtures::GLOBAL_ADMIN_REFERENCE_NAME,
            AccountFixtures::ADMIN_USER_REFERENCE_NAME,
            AccountFixtures::MODERATOR_REFERENCE_NAME,
            AccountFixtures::USER_REFERENCE_NAME,
            AccountFixtures::USER_BANNED_REFERENCE_NAME
        ];

        $players = [];
        $amountPerAccount = 0;

        for ($i = 1; $i <= self::AMOUNT_OF_PLAYERS; $i++) {
            $players[] = [
                'name' => 'Player' . $i,
                'level' => mt_rand(1, 250),
                'kingdom' => mt_rand(1, 3),
                'account' => isset($account[0]) ? $this->getReference($account[0]) : null,
            ];

            $amountPerAccount++;

            if ($amountPerAccount >= 3) {
                unset($account[0]);
                $account = array_values($account);
                $amountPerAccount = 0;
            }
        }

        return $players;
    }

    public function getDependencies()
    {
        return [
            AccountFixtures::class
        ];
    }
}