<?php

namespace App\Repository;

use App\Entity\PayPal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayPal|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayPal|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayPal[]    findAll()
 * @method PayPal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayPalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayPal::class);
    }

//    /**
//     * @return PayPal[] Returns an array of PayPal objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PayPal
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
