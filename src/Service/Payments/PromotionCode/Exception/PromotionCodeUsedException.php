<?php

namespace App\Service\Payments\PromotionCode\Exception;

class PromotionCodeUsedException extends PromotionCodeStatusException
{
    /**
     * @inheritdoc
     */
    public function getMessageKey(): string
    {
        return 'payment.promotion_code.used';
    }
}