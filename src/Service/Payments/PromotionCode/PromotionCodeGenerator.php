<?php

namespace App\Service\Payments\PromotionCode;

use App\Entity\Account;
use App\Entity\PromotionCode;
use App\Form\Model\PromotionCodeGenerate;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PromotionCodeGenerator
{
    /**
     * Number of characters in generated code
     */
    const NUMBER_OF_CHARACTERS = 8;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \App\Entity\Account
     */
    private $user;

    /**
     * CodeGenerator constructor.
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage)
    {
        $this->managerRegistry = $managerRegistry;
        $this->manager = $managerRegistry->getManager();
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * Generates random code
     *
     * @return string
     */
    private function generateRandomCode(): string
    {
        return strtoupper(bin2hex(random_bytes(self::NUMBER_OF_CHARACTERS / 2)));
    }

    /**
     * Creates single App\Entity\PromotionCode
     *
     * @param int $value
     * @param int $type
     * @param string $tag
     * @param \DateTime|null $expires
     * @return PromotionCode
     */
    private function createSingleCode(int $value, int $type, ?string $tag = null, ?\DateTime $expires = null): PromotionCode
    {
        try {
            $promotionCode = new PromotionCode();
            $promotionCode->setCode($this->generateRandomCode());
            $promotionCode->setValue($value);
            $promotionCode->setGeneratedBy($this->user);
            $promotionCode->setExpires($expires);
            $promotionCode->setType($type);

            if ($promotionCode->getType() === PromotionCode::ONE_PER_USER_TYPE) {
                $promotionCode->setTag($tag);
            }

            $this->manager->persist($promotionCode);
            $this->manager->flush();
        } catch (UniqueConstraintViolationException $e) {
            $this->manager = $this->managerRegistry->resetManager();
            $this->user = $this->manager->find(Account::class, $this->user->getId());

            $this->createSingleCode($value, $type, $expires);
        }

        return $promotionCode;
    }

    /**
     * Generates specific amount of codes as App\Entity\PromotionCode
     *
     * @param PromotionCodeGenerate $promotionCodeGenerate
     */
    public function generate(PromotionCodeGenerate $promotionCodeGenerate): void
    {
        for ($i = 0; $i < $promotionCodeGenerate->getAmount(); $i++) {
            $this->createSingleCode($promotionCodeGenerate->getValue(), $promotionCodeGenerate->getType(), $promotionCodeGenerate->getTag(), $promotionCodeGenerate->getExpires());
        }
    }
}