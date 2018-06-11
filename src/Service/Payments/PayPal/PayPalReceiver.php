<?php

namespace App\Service\Payments\PayPal;

use App\Entity\PayPalTransaction;
use Doctrine\Common\Persistence\ObjectManager;

class PayPalReceiver
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function receive(PayPalTransaction $payPalTransaction)
    {
        $payPalTransaction->setComplete(PayPalTransaction::PAYMENT_COMPLETE);
        $this->manager->flush();
    }
}