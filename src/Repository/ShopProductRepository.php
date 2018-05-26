<?php

namespace App\Repository;

use App\Entity\ShopProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShopProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopProduct[]    findAll()
 * @method ShopProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShopProduct::class);
    }
}
