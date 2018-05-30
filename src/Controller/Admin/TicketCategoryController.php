<?php

namespace App\Controller\Admin;

use App\Entity\TicketCategory;
use App\Form\TicketCategoryType;
use App\Repository\TicketCategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("acp/ticket/category")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TicketCategoryController extends Controller
{
    /**
     * @Route("/", name="ticket_category_index", methods="GET")
     */
    public function index(TicketCategoryRepository $ticketCategoryRepository): Response
    {
        return $this->render('admin/ticket_category/index.html.twig', ['ticket_categories' => $ticketCategoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="ticket_category_new", methods="GET|POST")
     */
    public function new(Request $request, TranslatorInterface $translator): Response
    {
        $ticketCategory = new TicketCategory();
        $form = $this->createForm(TicketCategoryType::class, $ticketCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticketCategory);
            $em->flush();

            $this->addFlash('success', $translator->trans('support.category.new.success', [
                '%ticket_category_index%' => $this->generateUrl('ticket_category_index')
            ]));
            return $this->redirectToRoute('ticket_category_new');
        }

        return $this->render('admin/ticket_category/new.html.twig', [
            'ticket_category' => $ticketCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_category_show", methods="GET")
     */
    public function show(TicketCategory $ticketCategory): Response
    {
        return $this->render('admin/ticket_category/show.html.twig', ['ticket_category' => $ticketCategory]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_category_edit", methods="GET|POST")
     */
    public function edit(Request $request, TicketCategory $ticketCategory): Response
    {
        $form = $this->createForm(TicketCategoryType::class, $ticketCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_category_edit', ['id' => $ticketCategory->getId()]);
        }

        return $this->render('admin/ticket_category/edit.html.twig', [
            'ticket_category' => $ticketCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_category_delete", methods="DELETE")
     */
    public function delete(Request $request, TicketCategory $ticketCategory): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticketCategory->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ticketCategory);
            $em->flush();
        }

        return $this->redirectToRoute('ticket_category_index');
    }
}
