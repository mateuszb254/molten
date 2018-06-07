<?php
namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Entity\TicketAnswer;
use App\Form\TicketAnswerType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/support")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SupportController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("", name="admin_support_index")
     */
    public function index(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findAll();

        return $this->render('admin/support/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/ticket/{id}", name="admin_ticket_show", requirements={"id": "\d+"})
     */
    public function show(Request $request, Ticket $ticket, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(TicketAnswerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $answer TicketAnswer
             */
            $answer = $form->getData();
            $answer->setIsAdminAnswer(true);

            $ticket->addAnswer($answer);

            if($form->get('status')->getData() === true) {
                $ticket->setStatus(Ticket::STATUS_CLOSED);
            } else {
                $ticket->setStatus(Ticket::STATUS_ANSWERED);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();

            $this->addFlash('success', $translator->trans('support.answer.success', [
                '%admin_support_index%' => $this->generateUrl('admin_support_index')
            ]));
            return $this->redirectToRoute('admin_ticket_show', [
                'id' => $ticket->getId()
            ]);
        } else {
            $form->get('status')->setData($ticket->getStatus() === Ticket::STATUS_CLOSED);
        }

        return $this->render('admin/support/show.html.twig', [
            'ticket' => $ticket,
            'ticket_answer_form' => $form->createView()
        ]);
    }
}