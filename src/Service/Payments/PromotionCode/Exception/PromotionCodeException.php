<?php


namespace App\Service\Payments\PromotionCode\Exception;


abstract class PromotionCodeException extends \Exception
{
    /**
     * Message key to be used by the translation component.
     *
     * @return string
     */
    public function getMessageKey(): string
    {
        return 'a promotion code exception occurred.';
    }

    /**
     * Message data to be used by the translation component.
     *
     * @return array
     */
    public function getMessageData(): array
    {
        return array();
    }
}