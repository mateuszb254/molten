<?php

namespace App\Repository;

use App\Entity\UserProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProduct[]    findAll()
 * @method UserProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserProduct::class);
    }

//    /**
//     * @return UserProduct[] Returns an array of UserProduct objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserProduct
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
