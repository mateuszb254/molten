<?php

namespace App\Service\Payments\PromotionCode\Exception;

class PromotionCodeExpiredException extends PromotionCodeStatusException
{
    /**
     * @inheritdoc
     */
    public function getMessageKey(): string
    {
        return 'payment.promotion_code.expired';
    }
}