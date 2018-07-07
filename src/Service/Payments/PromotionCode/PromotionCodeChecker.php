<?php


namespace App\Service\Payments\PromotionCode;

use App\Entity\PromotionCode;
use App\Repository\PromotionCodeRepository;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeExpiredException;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeOnePerUserException;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeUsedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(PromotionCodeRepository $promotionCodeRepository, TokenStorageInterface $tokenStorage)
    {
        $this->promotionCodeRepository = $promotionCodeRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * This method validates code if is able to use. If not throws specific exception
     *
     * @param \App\Entity\PromotionCode $promotionCode
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeExpiredException
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeOnePerUserException
     * @throws \App\Service\Payments\PromotionCode\Exception\PromotionCodeUsedException
     */
    public function checkCode(PromotionCode $promotionCode)
    {
        $this->promotionCode = $promotionCode;

        if ($this->isExpired()) throw new PromotionCodeExpiredException();
        if ($this->isUsed()) throw new PromotionCodeUsedException();

        if ($this->promotionCode->getType() === PromotionCode::ONE_PER_USER_TYPE && $this->promotionCode->getTag() !== null) {
            if ($this->isOneCodePerTagUsed()) throw new PromotionCodeOnePerUserException();
        }
    }

    /**
     * This method validates if code is expired
     *
     * @return bool
     */
    private function isExpired(): bool
    {
        if ($this->promotionCode->getExpires() === null) {
            return false;
        }

        return $this->promotionCode->getExpires() < new \DateTime();
    }

    /**
     * This method validates if code is used
     *
     * @return bool
     */
    private function isUsed(): bool
    {
        return !empty($this->promotionCode->getUsedBy());
    }

    /**
     * This method validates if code from specific promotion is used right now
     *
     * @return bool
     */
    private function isOneCodePerTagUsed(): bool
    {
        return $this->promotionCodeRepository->findUsedCodesByTheUserAndTag($this->user, $this->promotionCode->getTag()) !== null;
    }
}