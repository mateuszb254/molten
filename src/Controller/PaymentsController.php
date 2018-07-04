<?php

namespace App\Controller;

use App\Entity\PromotionCode;
use App\Form\PromotionCodeType;
use App\Repository\PromotionCodeRepository;
use App\Service\UserLogger;
use Doctrine\Common\Persistence\ObjectManager;
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
    public function promotionCode(Request $request, PromotionCodeRepository $promotionCodeRepository, TranslatorInterface $translator, UserLogger $userLogger, ObjectManager $objectManager): Response
    {
        $form = $this->createForm(PromotionCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionCode = $promotionCodeRepository->findOneByCode($form->get('promotion_code')->getData());

            if (!$promotionCode) {
                $this->addFlash('alert', $translator->trans('payment.promotion_code.notValid'));
                return $this->redirectToRoute('payments_promotion_code');
            }

            if($promotionCode->getExpires() < new \DateTime() && $promotionCode->getExpires() !== null) {
                $this->addFlash('alert', $translator->trans('payment.promotion_code.expired'));
                return $this->redirectToRoute('payments_promotion_code');
            }

            if ($promotionCode->getUsedBy()) {
                $this->addFlash('alert', $translator->trans('payment.promotion_code.used'));
                return $this->redirectToRoute('payments_promotion_code');
            }

            if($promotionCode->getType() === PromotionCode::ONE_PER_USER_TYPE && $promotionCode->getTag() !== null) {
                if($promotionCodeRepository->findUsedCodesByTheUserAndTag($this->getUser(), $promotionCode->getTag())) {
                    $this->addFlash('alert', $translator->trans('payment.promotion_code.onePerUser', [
                        '%tag%' => $promotionCode->getTag()
                    ]));
                    return $this->redirectToRoute('payments_promotion_code');
                };
            }

            $user = $this->getUser();
            $user->setCoins($user->getCoins() + $promotionCode->getValue());
            $promotionCode->setUsedBy($user);
            $promotionCode->setUsedDate(new \DateTime());

            $objectManager->flush();

            $userLogger->addLog($user, 'PROMOTION_CODE', $promotionCode->getCode());

            $this->addFlash('success', $translator->trans('payment.promotion_code.success', [
                '%coins%' => $promotionCode->getValue()
            ]));
            return $this->redirectToRoute('payments_promotion_code');
        }
        return $this->render('user/payments/promotion_code/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}