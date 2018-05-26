<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class TestSubscriber implements EventSubscriberInterface
{
    public function test()
    {

    }

    public static function getSubscribedEvents()
    {
        return [
          KernelEvents::REQUEST => [
              ['test', 1]
          ]
        ];
    }
}