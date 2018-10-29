<?php

namespace App\Entity;

use App\Service\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopCategoryRepository")
 * @UniqueEntity(fields="name", message="shop.category.name.unique")
 * @ORM\HasLifecycleCallbacks()
 */
class ShopCategory
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="App\Entity\ShopProduct", mappedBy="category")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false, length=35)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false, length=35)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $status = self::STATUS_INACTIVE;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ShopProduct", mappedBy="category", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"price": "DESC"})
     */
    private $products;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setSlug()
    {
        $this->slug = Slugger::slugify($this->getName());
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ShopProduct $product): self
    {
        $product->setCategory($this);
        $this->products[] = $product;

        return $this;
    }

    public function removeProduct(ShopProduct $product): self
    {
        $product->setCategory(null);
        $this->products->removeElement($product);

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
