<?php

namespace App\Repository;

use App\Entity\PayPal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayPal|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayPal|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayPal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayPalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayPal::class);
    }

    public function findAll()
    {
        return $this->findBy([], [
            'price' => 'ASC'
        ]);
    }
}
