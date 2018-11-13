<?php

namespace App\Controller;

use App\Form\PromotionCodeType;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeException;
use App\Service\Payments\PromotionCode\PromotionCodeUsage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/payments")
 */
class PromotionCodeController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("", name="payments_index")
     */
    public function index(): Response
    {
        return $this->render('user/payments/index.html.twig');
    }

    /**
     * @Route("/codes", name="payments_promotion_code")
     */
    public function promotionCode(Request $request, PromotionCodeUsage $promotionCodeUsage, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(PromotionCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $promotionCode = $promotionCodeUsage->useCode($form->get('promotion_code')->getData(), $this->getUser());

                $this->addFlash('success', $translator->trans('payment.promotion_code.success', [
                    '%coins%' => $promotionCode->getValue()
                ]));

                return $this->redirectToRoute('payments_promotion_code');
            } catch (PromotionCodeException $exception) {
                $error = $exception;
            }
        }

        return $this->render('user/payments/promotion_code/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? ''
        ]);
    }
}