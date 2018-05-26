<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuildRepository")
 */
class Guild
{
    public const GUILDS_PER_PAGE_SIDEBAR = 10;
    public const GUILDS_PER_PAGE_MAIN = 25;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $wins = 0;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $loses = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $kingdom;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setWins(int $wins): void
    {
        $this->wins = $wins;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setLoses(int $loses): void
    {
        $this->loses = $loses;
    }

    public function getLoses(): ?int
    {
        return $this->loses;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function getKingdom(): ?int
    {
        return $this->kingdom;
    }

    public function setKingdom(int $kingdom): self
    {
        $this->kingdom = $kingdom;

        return $this;
    }
}
