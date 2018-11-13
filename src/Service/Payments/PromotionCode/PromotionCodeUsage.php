<?php

namespace App\Service\Payments\PromotionCode;

use App\Entity\Account;
use App\Entity\PromotionCode;
use App\Service\UserLogger;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * This class provides usage promotion codes by user
 */
class PromotionCodeUsage
{
    /**
     * @var $promotionCodeChecker PromotionCodeChecker
     */
    private $promotionCodeChecker;

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    /**
     * @var $userLogger \App\Service\UserLogger
     */
    private $userLogger;

    /**
     * PromotionCodeUsage constructor.
     * @param PromotionCodeChecker $promotionCodeChecker
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \App\Service\UserLogger $userLogger
     */
    public function __construct(PromotionCodeChecker $promotionCodeChecker, ObjectManager $objectManager, UserLogger $userLogger)
    {
        $this->promotionCodeChecker = $promotionCodeChecker;
        $this->objectManager = $objectManager;
        $this->userLogger = $userLogger;
    }

    /**
     * This method provides using code by user
     *
     * @param string $code
     * @param Account $account
     * @return PromotionCode
     */
    public function useCode(string $code, Account $account): PromotionCode
    {
        $promotionCode = $this->promotionCodeChecker->checkCode($code, $account);

        $promotionCode->setUsedBy($account);

        $account->grantCoins($promotionCode->getValue());

        $this->userLogger->addLog($account, 'PROMOTION_CODE', sprintf('%s %s', $promotionCode->getCode(), $promotionCode->getTag() !== null ? '(' . $promotionCode->getTag() . ')' : ''));

        $this->objectManager->flush();

        return $promotionCode;
    }
}