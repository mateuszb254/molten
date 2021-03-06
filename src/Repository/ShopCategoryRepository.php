<?php

namespace App\Repository;

use App\Entity\ShopCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShopCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShopCategory::class);
    }

    public function findAll()
    {
        return $this->findBy([], [
            'position' => 'ASC'
        ]);
    }

    /**
     * @return ShopCategory[] Returns an array of ShopCategory objects with ShopCategory::STATUS_ACTIVE value sorted ASC
     */
    public function findAllActiveCategories()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter(':status', ShopCategory::STATUS_ACTIVE)
            ->orderBy('c.position', 'ASC')
            ->getQuery();

        return $query->execute();
    }
}
