<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role extends \Symfony\Component\Security\Core\Role\Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function __toString()
    {
        return $this->role;
    }
}
