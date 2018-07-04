<?php


namespace App\Form\Model;

use App\Entity\PromotionCode;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionCodeGenerate
{
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1)
     */
    private $amount;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1)
     */
    private $value;

    /**
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @Assert\NotBlank(groups={"require_tag"}, message="payment.promotion_code.tag.require")
     */
    private $tag;

    /**
     * @Assert\GreaterThan("today", message="payment.promotion_code.expires.greaterThanToday")
     */
    private $expires;

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag($tag): self
    {
        if ($this->getType() === PromotionCode::ONE_PER_USER_TYPE) {
            $this->tag = $tag;
        }

        return $this;
    }

    public function getExpires(): ?\DateTime
    {
        return $this->expires;
    }

    public function setExpires(?\DateTime $expires): self
    {
        $this->expires = $expires;

        return $this;
    }
}