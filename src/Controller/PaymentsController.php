<?php

namespace App\Controller;

use App\Form\PromotionCodeType;
use App\Repository\PromotionCodeRepository;
use App\Service\Payments\PromotionCode\Exception\PromotionCodeStatusException;
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
class PaymentsController extends AbstractController implements UserControllerInterface
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
    public function promotionCode(Request $request, PromotionCodeRepository $promotionCodeRepository, PromotionCodeUsage $promotionCodeUsage, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(PromotionCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionCode = $promotionCodeRepository->findOneByCode($form->get('promotion_code')->getData());

            if (!$promotionCode) {
                $this->addFlash('alert', $translator->trans('payment.promotion_code.notValid'));

                return $this->redirectToRoute('payments_promotion_code');;
            }

            try {
                $promotionCodeUsage->useCode($promotionCode);

                $this->addFlash('success', $translator->trans('payment.promotion_code.success', [
                    '%coins%' => $promotionCode->getValue()
                ]));

                return $this->redirectToRoute('payments_promotion_code');
            } catch (PromotionCodeStatusException $exception) {
                $exception->setPromotionCode($promotionCode);
                $error = $exception;
            }
        }

        return $this->render('user/payments/promotion_code/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? ''
        ]);
    }
}