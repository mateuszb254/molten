<?php

namespace App\EventSubscriber;

use App\Controller\UserControllerInterface;
use App\Exception\MaintenanceException;
use App\Service\MaintenanceManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \App\Service\MaintenanceManager $maintenanceManager
     */
    private $maintenanceManager;

    /**
     * MaintenanceSubscriber constructor.
     * @param \Twig_Environment $twig
     * @param \App\Service\MaintenanceManager $maintenanceManager
     */
    public function __construct(\Twig_Environment $twig, MaintenanceManager $maintenanceManager)
    {
        $this->twig = $twig;
        $this->maintenanceManager = $maintenanceManager;
    }


    /**
     * This method checks if site is under maintenance mode. If so prevents to do controller
     * logic and throws App\Exception\MaintenanceException exception
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if ($event->isMasterRequest()) {
            if ($this->maintenanceManager->getMaintenanceStatus() && $controller[0] instanceof UserControllerInterface) {
                throw new MaintenanceException();
            }
        }
    }

    /**
     * If MaintenanceException is thrown it renders user/maintenance.html.twig template with 503 status
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof MaintenanceException) {
            return $event->setResponse(new Response($this->twig->render('user/maintenance.html.twig'), Response::HTTP_SERVICE_UNAVAILABLE));
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                'onKernelController'
            ],
            KernelEvents::EXCEPTION => [
                'onKernelException'
            ]
        ];
    }
}