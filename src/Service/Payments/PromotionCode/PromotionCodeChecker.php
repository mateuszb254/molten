<?php


namespace App\Service\Payments\PromotionCode;

use App\Entity\Account;
use App\Entity\PromotionCode;
use App\Repository\PromotionCodeRepository;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeExpiredException;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeInvalidException;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeOnePerUserException;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeUsedException;

/**
 * This class checks code if is able to use
 */
class PromotionCodeChecker
{
    /**
     * @var $promotionCode \App\Entity\PromotionCode
     */
    private $promotionCode;

    /**
     * @var $promotionCodeRepository \App\Repository\PromotionCodeRepository
     */
    private $promotionCodeRepository;

    /**
     * @var $user \App\Entity\Account
     */
    private $user;

    /**
     * PromotionCodeChecker constructor.
     * @param \App\Repository\PromotionCodeRepository $promotionCodeRepository
     */
    public function __construct(PromotionCodeRepository $promotionCodeRepository)
    {
        $this->promotionCodeRepository = $promotionCodeRepository;
    }

    /**
     * This method validates code if is able to use. If not throws specific exception
     *
     * @param string $code
     * @param Account $account
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeInvalidException
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeExpiredException
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeOnePerUserException
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeUsedException
     *
     * @return PromotionCode
     */
    public function checkCode(string $code, Account $account): PromotionCode
    {
        if (!$promotionCode = $this->promotionCodeRepository->findOneByCode($code)) {
            throw new PromotionCodeInvalidException();
        }

        if ($this->isExpired($promotionCode)) throw ((new PromotionCodeExpiredException())->setPromotionCode($promotionCode));
        if ($this->isUsed($promotionCode)) throw ((new PromotionCodeUsedException())->setPromotionCode($promotionCode));

        if ($promotionCode->getType() === PromotionCode::ONE_PER_USER_TYPE && $promotionCode->getTag() !== null) {
            if ($this->isOneCodePerTagUsed($promotionCode, $account)) throw ((new PromotionCodeOnePerUserException())->setPromotionCode($promotionCode));
        }

        return $promotionCode;
    }

    /**
     * This method validates if code is expired
     *
     * @param PromotionCode $promotionCode
     * @return bool
     */
    private function isExpired(PromotionCode $promotionCode): bool
    {
        if ($promotionCode->getExpires() === null) {
            return false;
        }

        return $this->promotionCode->getExpires() < new \DateTime();
    }

    /**
     * This method validates if code is used
     *
     * @param PromotionCode $promotionCode
     * @return bool
     */
    private function isUsed(PromotionCode $promotionCode): bool
    {
        return !empty($promotionCode->getUsedBy());
    }

    /**
     * This method validates if code from specific promotion is used right now
     *
     * @param PromotionCode $promotionCode
     * @param Account $account
     * @return bool
     */
    private function isOneCodePerTagUsed(PromotionCode $promotionCode, Account $account): bool
    {
        return $this->promotionCodeRepository->findUsedCodesByTheUserAndTag($account, $promotionCode->getTag()) !== null;
    }
}