<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TestSubscriber implements EventSubscriberInterface
{
    public function before(GetResponseEvent  $event)
    {
        $event->getRequest()->attributes->set('hehe', 123);
    }

    public function after(FinishRequestEvent $event)
    {
        $event->getRequest()->attributes->get('hehe');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'before',
            KernelEvents::FINISH_REQUEST => 'after'
        ];
    }
}