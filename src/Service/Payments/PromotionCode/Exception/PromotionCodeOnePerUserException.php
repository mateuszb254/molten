<?php

namespace App\Service\Payments\PromotionCode\Exception;

class PromotionCodeOnePerUserException extends PromotionCodeStatusException
{
    /**
     * @inheritdoc
     */
    public function getMessageKey(): string
    {
        return 'payment.promotion_code.onePerUser';
    }

    /**
     * @inheritdoc
     */
    public function getMessageData(): array
    {
        return [
            '%tag%' => $this->promotionCode->getTag()
        ];
    }
}