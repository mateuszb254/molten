<?php

namespace App\Repository;

use App\Entity\PayPalTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayPalTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayPalTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayPalTransaction[]    findAll()
 * @method PayPalTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayPalTransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayPalTransaction::class);
    }

    public function findOneByPaymentId(string $paymentId)
    {
        return $this->findOneBy([
            'payment_id' => $paymentId
        ]);
    }
}
