<?php

namespace App\Service\Payments\PromotionCode\Exception;

use App\Entity\PromotionCode;

abstract class PromotionCodeStatusException extends PromotionCodeException
{
    /**
     * @var PromotionCode $promotionCode
     */
    protected $promotionCode;

    /**
     * Get the promotion code.
     *
     * @return PromotionCode
     */
    public function getPromotionCode(): PromotionCode
    {
        return $this->promotionCode;
    }

    /**
     * Set the promotion code
     *
     * @param \App\Entity\PromotionCode $promotionCode
     * @return self
     */
    public function setPromotionCode(PromotionCode $promotionCode): self
    {
        $this->promotionCode = $promotionCode;

        return $this;
    }

    /**
     * Message key to be used by the translation component.
     *
     * @return string
     */
    public function getMessageKey(): string
    {
        return 'a promotion code status exception occurred.';
    }
}