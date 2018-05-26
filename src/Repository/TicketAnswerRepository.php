<?php

namespace App\Repository;

use App\Entity\TicketAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TicketAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketAnswer[]    findAll()
 * @method TicketAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TicketAnswer::class);
    }

//    /**
//     * @return TicketAnswer[] Returns an array of TicketAnswer objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketAnswer
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
