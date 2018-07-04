<?php

namespace App\Controller\Admin;

use App\Form\PromotionCodeGenerateType;
use App\Repository\PromotionCodeRepository;
use App\Service\Payments\PromotionCode\PromotionCodeGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/codes")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PromotionCodeController extends AbstractController
{
    /**
     * @Route("/", name="admin_payments_codes_index")
     */
    public function index(PromotionCodeRepository $promotionCodeRepository): Response
    {
        $promotionCodes = $promotionCodeRepository->findAll();

        return $this->render('admin/payments/codes/index.html.twig', [
            'promotionCodes' => $promotionCodes
        ]);
    }

    /**
     * @Route("/generate", name="admin_payments_codes_generate")
     */
    public function generate(Request $request, PromotionCodeGenerator $codeGenerator, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(PromotionCodeGenerateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codeGenerate = $form->getData();

            $codeGenerator->generate($codeGenerate);

            $this->addFlash('success', $translator->trans('payment.promotion_code.generate.success'));
            return $this->redirectToRoute('admin_payments_codes_generate');
        }

        return $this->render('admin/payments/codes/generate.html.twig', [
            'form' => $form->createView()
        ]);
    }
}