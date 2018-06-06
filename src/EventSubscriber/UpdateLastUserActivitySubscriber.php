<?php

namespace App\EventSubscriber;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UpdateLastUserActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
     */
    private $authorizationChecker;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    private $objectManager;

    /**
     * UserSubscriber constructor.
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, ObjectManager $objectManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->objectManager = $objectManager;
    }

    /**
     * This method updates User's last activity on every request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            if ($this->tokenStorage->getToken() && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->tokenStorage->getToken()->getUser();
                $user->setLastActivity(new \DateTime());

                $this->objectManager->flush();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'onKernelRequest'
            ]
        ];
    }
}