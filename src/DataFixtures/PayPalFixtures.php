<?php

namespace App\DataFixtures;

use App\Entity\PayPal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PayPalFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadPayPalData($manager);
    }

    private function loadPayPalData(ObjectManager $manager)
    {
        foreach ($this->getPayPalData() as $paypalData) {
            $paypal = new PayPal();
            $paypal->setCoins($paypalData['coins']);
            $paypal->setPrice($paypalData['price']);

            $manager->persist($paypal);
        }

        $manager->flush();
    }

    private function getPayPalData()
    {
        return [
            [
                'coins' => 200,
                'price' => 10
            ],
            [
                'coins' => 350,
                'price' => 20
            ],
            [
                'coins' => 700,
                'price' => 35
            ],
            [
                'coins' => 1400,
                'price' => 60
            ],
        ];
    }
}