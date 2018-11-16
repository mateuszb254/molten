<?php
namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketAnswerType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/support")
 * @Security("has_role('ROLE_USER')")
 */
class SupportController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("", name="support_index")
     * @Method("GET")
     */
    public function index(): Response
    {
        return $this->render('user/support/index.html.twig');
    }

    /**
     * @Route("/list", name="support_show")
     * @Method("GET")
     */
    public function show(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findTicketsOfUser($this->getUser());

        return $this->render('user/support/list.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/ticket/{id}", name="support_ticket_details", requirements={"id"="\d+"})
     * @Security("ticket.getAuthor() == user")
     */
    public function showTicket(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketAnswerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($ticket->getStatus() !== Ticket::STATUS_CLOSED) {
                $ticket->addAnswer($form->getData());

                $ticket->setStatus(Ticket::STATUS_OPEN);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
            return $this->redirectToRoute('support_ticket_details', [
                'id' => $ticket->getId()
            ]);
        }

        return $this->render('user/support/ticket_details.html.twig', [
            'ticket' => $ticket,
            'ticket_answer_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="support_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(TicketType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var $ticket Ticket */
            $ticket = $form->getData();

            $ticket->setAuthor($this->getUser());
            $ticket->setCreatedAt(new \DateTime());

            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('support_show');
        }

        return $this->render('user/support/new.html.twig', [
            'create_ticket_form' => $form->createView()
        ]);
    }
}