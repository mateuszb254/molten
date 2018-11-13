<?php

namespace App\Service\Payments\PromotionCode\Exception;

class PromotionCodeInvalidException extends PromotionCodeException
{
    /**
     * @inheritdoc
     */
    public function getMessageKey(): string
    {
        return 'payment.promotion_code.invalid';
    }
}