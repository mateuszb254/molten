<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\PromotionCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PromotionCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionCode[]    findAll()
 * @method PromotionCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PromotionCode::class);
    }

    public function findOneByCode(string $code): ?PromotionCode
    {
        return $this->findOneBy([
            'code' => $code
        ]);
    }

    public function findUsedCodesByTheUserAndTag(Account $user, string $tag): ?PromotionCode
    {
        return $this->findOneBy([
            'usedBy' => $user,
            'tag' => $tag
        ]);
    }
}
