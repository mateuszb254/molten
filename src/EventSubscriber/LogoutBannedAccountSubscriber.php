<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LogoutBannedAccountSubscriber implements EventSubscriberInterface
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
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface;
     */
    private $urlGenerator;

    /**
     * UserSubscriber constructor.
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, UrlGeneratorInterface $urlGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * This method checks if user's banned. If so logs him out.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event;
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            if ($this->tokenStorage->getToken() && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->tokenStorage->getToken()->getUser();

                if ($user->isBanned()) {
                    return $event->setResponse(new RedirectResponse($this->urlGenerator->generate('logout')));
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'onKernelRequest'
            ]
        ];
    }
}