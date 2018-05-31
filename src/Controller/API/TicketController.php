<?php

namespace App\Controller\API;

use App\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("json")
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/tickets/open", requirements={"start" : "[\d]+"} ,name="json_tickets_count", methods={"GET"})
     */
    public function count(TicketRepository $ticketRepository): Response
    {
        return $this->json($ticketRepository->findCountOfOpenedTickets());
    }
}