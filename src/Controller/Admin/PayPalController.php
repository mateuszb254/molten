<?php

namespace App\Controller\Admin;

use App\Entity\PayPal;
use App\Form\PayPalType;
use App\Repository\PayPalRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/paypal")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PayPalController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("/packets", name="admin_payments_paypal_index", methods={"GET"})
     */
    public function index(Request $request, PayPalRepository $payPalRepository): Response
    {
        $packets = $payPalRepository->findAll();

        return $this->render('admin/payments/paypal/index.html.twig', [
            'paypal_packets' => $packets
        ]);
    }

    /**
     * @Route("/new", name="admin_payments_paypal_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TranslatorInterface $translator): Response
    {
        $paypal = new PayPal();

        $form = $this->createForm(PayPalType::class, $paypal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paypal);
            $em->flush();

            $this->addFlash('success', $translator->trans('payment.paypal.new.success'));
            return $this->redirectToRoute('admin_payments_paypal_new');
        }

        return $this->render('admin/payments/paypal/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_payments_paypal_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PayPal $payPal, TranslatorInterface $translator): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $payPal->getId(), $request->get('_token'))) {
            return $this->redirectToRoute('admin_payments_paypal_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($payPal);
        $entityManager->flush();

        $this->addFlash('success', $translator->trans('payment.paypal.delete.success'));
        return $this->redirectToRoute('admin_payments_paypal_index');
    }

    /**
     * @Route("/edit/{id}", name="admin_payments_paypal_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PayPal $payPal, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(PayPalType::class, $payPal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $translator->trans('payment.paypal.edit.success'));
            return $this->redirectToRoute('admin_payments_paypal_index');
        }

        return $this->render('admin/payments/paypal/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}