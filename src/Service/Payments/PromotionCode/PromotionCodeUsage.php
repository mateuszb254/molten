<?php

namespace App\Service\Payments\PromotionCode;

use App\Entity\Account;
use App\Entity\PromotionCode;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @var $user \App\Entity\Account
     */
    private $user;

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    /**
     * PromotionCodeUsage constructor.
     * @param PromotionCodeChecker $promotionCodeChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function __construct(PromotionCodeChecker $promotionCodeChecker, TokenStorageInterface $tokenStorage, ObjectManager $objectManager)
    {
        $this->promotionCodeChecker = $promotionCodeChecker;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->objectManager = $objectManager;
    }

    /**
     * This method provides using code by user
     *
     * @param PromotionCode $promotionCode
     */
    public function useCode(PromotionCode $promotionCode): void
    {
        $this->promotionCodeChecker->checkCode($promotionCode);

        $promotionCode->setUsedBy($this->user);
        $promotionCode->setUsedDate(new \DateTime());

        $this->user->grantCoins($promotionCode->getValue());

        $this->objectManager->flush();
    }
}