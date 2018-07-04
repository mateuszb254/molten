<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromotionCodeRepository")
 * @UniqueEntity(fields="code")
 */
class PromotionCode
{
    /**
     * Specify type of promotion code.
     * If it's set user can use many codes per pool.
     */
    const SIMPLE_TYPE = 0;

    /**
     * Specify type of promotion code.
     * If it's set user can use one code per pool.
     */
    const ONE_PER_USER_TYPE = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expires = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generatedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private $usedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usedDate = null;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tag;

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getExpires(): ?\DateTimeInterface
    {
        return $this->expires;
    }

    public function setExpires(?\DateTimeInterface $expires): self
    {
        $this->expires = $expires;

        return $this;
    }

    public function getGeneratedBy(): ?Account
    {
        return $this->generatedBy;
    }

    public function setGeneratedBy(Account $generatedBy): self
    {
        $this->generatedBy = $generatedBy;

        return $this;
    }

    public function getUsedBy(): ?Account
    {
        return $this->usedBy;
    }

    public function setUsedBy(?Account $usedBy): self
    {
        $this->usedBy = $usedBy;

        return $this;
    }

    public function getUsedDate(): ?\DateTime
    {
        return $this->usedDate;
    }

    public function setUsedDate(?\DateTime $usedDate): self
    {
        $this->usedDate = $usedDate;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
