<?php

namespace App\Controller\Admin;

use App\Form\TermsType;
use App\Service\TermsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acp")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TermsController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("/terms", name="admin_terms", methods={"GET", "POST"})
     */
    public function index(Request $request, TermsManager $termsManager): Response
    {
        $form = $this->createForm(TermsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $termsManager->setTerms($form->get('contents')->getData());
        }

        return $this->render('admin/terms/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}