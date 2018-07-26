<?php

namespace App\Repository;

use App\Entity\DownloadLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DownloadLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadLink[]    findAll()
 * @method DownloadLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DownloadLink::class);
    }

//    /**
//     * @return DownloadLink[] Returns an array of DownloadLink objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DownloadLink
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
