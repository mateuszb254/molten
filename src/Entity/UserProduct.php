<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProductRepository")
 */
class UserProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ShopProduct")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function getProduct(): ?ShopProduct
    {
        return $this->product;
    }

    public function setProduct(ShopProduct $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(Account $user): self
    {
        $this->user = $user;

        return $this;
    }
}
