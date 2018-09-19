<?php

namespace App\Controller\API;

use App\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("api")
 */
class TicketController extends AbstractController implements APIControllerInterface
{
    /**
     * @Route("/tickets/open", requirements={"start" : "[\d]+"} ,name="api_ticketsCount", methods={"GET"})
     */
    public function count(TicketRepository $ticketRepository): Response
    {
        return $this->json($ticketRepository->findCountOfOpenedTickets());
    }
}