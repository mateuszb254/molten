<?php

namespace App\Controller;

use App\Entity\PayPal;
use App\Entity\PayPalTransaction;
use App\Repository\PayPalRepository;
use App\Repository\PayPalTransactionRepository;
use App\Service\Payments\PayPal\PayPalInitiator;
use App\Service\Payments\PayPal\PayPalReceiver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Security("has_role('ROLE_USER')")
 *
 * @Route("/payments/paypal"))
 */
class PayPalController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("", name="payments_paypal_index", methods={"GET"})
     */
    public function index(Request $request, PayPalRepository $payPalRepository): Response
    {
        return $this->render('user/payments/paypal/index.html.twig', [
            'paypal_packets' => $payPalRepository->findAll()
        ]);
    }

    /**
     * @Route("/packet/{id}", name="payments_paypal_packet", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function packet(PayPal $paypal, PayPalInitiator $payPalInitiator): Response
    {
        $payPalInitiator->setItem($paypal);

        return $this->redirect($payPalInitiator->startPayment());
    }

    /**
     * @Route("/receive", name="payments_paypal_receive", methods={"GET"})
     */
    public function receive(Request $request, PayPalTransactionRepository $payPalTransactionRepository, PayPalReceiver $payPalReceiver, TranslatorInterface $translator): Response
    {
        $token = $request->get('token');
        $paymentId = $request->get('paymentId');

        if (!($transaction = $payPalTransactionRepository->findOneByPaymentId($paymentId)) || !$token || !$paymentId) {
            return $this->redirectToRoute('payments_paypal_index');
        }

        if (!$transaction->getComplete() !== PayPalTransaction::PAYMENT_INCOMPLETE) {
            $this->addFlash('alert', $translator->trans('payment.paypal.completed'));
            return $this->redirectToRoute('payments_paypal_index');
        }

        $user = $this->getUser();
        $user->setCoins($user->getCoins() + $transaction->getPayPal()->getCoins());

        /**
         * $payPalReceiver executes flush()
         */
        $payPalReceiver->receive($transaction);

        $this->addFlash('success', $translator->trans('payment.paypal.success', [
            '%coins%' => $transaction->getPayPal()->getCoins()
        ]));

        return $this->redirectToRoute('payments_paypal_index');
    }
}