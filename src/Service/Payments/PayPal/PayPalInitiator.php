<?php

namespace App\Service\Payments\PayPal;

use App\Entity\PayPal;
use App\Entity\PayPalTransaction;
use App\Service\Payments\PayPal\Exception\ConfigNotSetException;
use Doctrine\Common\Persistence\ObjectManager;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * This class initializes PayPal Payment using https://github.com/paypal/PayPal-PHP-SDK
 */
class PayPalInitiator
{
    /**
     * config\service.yaml
     *
     * @var $config
     */
    private $config;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Stores array of \Paypal\Api\Item
     *
     * @var array;
     */
    private $items;

    /**
     * Stores array of App\Entity\Paypal for registering payment
     *
     * @var array
     */
    private $payPal;

    /**
     * PayPalInitiator constructor.
     * @param array $config
     * @param UrlGeneratorInterface $urlGenerator
     * @param ObjectManager $manager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(array $config, UrlGeneratorInterface $urlGenerator, ObjectManager $manager, TokenStorageInterface $tokenStorage)
    {
        $this->config = $config;
        $this->urlGenerator = $urlGenerator;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns configured \PayPal\Rest\ApiContext
     *
     * @return \PayPal\Rest\ApiContext
     */
    private function getApiContext(): ApiContext
    {
        $apiContext = new ApiContext(
            $this->getOAuthTokenCredential()
        );

        $apiContext->setConfig([
            'mode' => $this->config['mode']
        ]);

        return $apiContext;
    }

    /**
     * Returns configured \PayPal\Auth\OAuthTokenCredential
     *
     * @return \PayPal\Auth\OAuthTokenCredential
     *
     * @throws \App\Service\Payments\PayPal\Exception\ConfigNotSetException
     */
    private function getOAuthTokenCredential(): OAuthTokenCredential
    {
        if (empty($this->config['public_key']) || empty($this->config['secret_key'])) {
            throw new ConfigNotSetException([
                empty($this->config['public_key']) ? 'public_key' : null,
                empty($this->config['secret_key']) ? 'secret_key' : null
            ]);
        }

        return new OAuthTokenCredential(
            $this->config['public_key'], $this->config['secret_key']
        );
    }

    /**
     * Returns configured \PayPal\Api\Amount
     *
     * @return \PayPal\Api\Amount
     */
    private function getAmount(): Amount
    {
        $amount = new Amount();
        $amount->setCurrency($this->config['currency']);

        $priceAmount = 0;
        foreach ($this->getItemList()->getItems() as $item) {
            $priceAmount += (int)$item->getPrice();
        }

        $amount->setTotal($priceAmount);

        return $amount;
    }

    /**
     * It needs \App\Entity\PayPal to return \Paypal\Api\Item
     *
     * @param PayPal $payPal
     * @return PayPalInitiator
     */
    public function setItem(PayPal $payPal): self
    {
        $this->payPal[] = $payPal;

        $item = new Item();
        $item->setName('nanme')
            ->setCurrency($this->config['currency'])
            ->setQuantity(1)
            ->setPrice($payPal->getPrice());

        $this->items[] = $item;
        return $this;
    }

    /**
     * @return \Paypal\Api\ItemList
     */
    private function getItemList(): ItemList
    {
        $itemList = new ItemList();
        $itemList->setItems($this->items);

        return $itemList;
    }

    /**
     * Returns configured \Paypal\Api\Transaction
     *
     * @return \Paypal\Api\Transaction
     */
    private function getTransaction(): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->getAmount())
            ->setItemList($this->getItemList())
            ->setDescription("Test")
            ->setInvoiceNumber(uniqid());

        return $transaction;
    }

    /**
     * Returns configured \Paypal\Api\Payer
     *
     * @return \Paypal\Api\Payer
     */
    private function getPayer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod($this->config['payment_method']);

        return $payer;
    }

    /**
     * Returns configured \Paypal\Api\RedirectUrls from config
     *
     * @return \Paypal\Api\RedirectUrls
     */
    private function getRedirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->urlGenerator->generate($this->config['payment_success_route_name'], [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCancelUrl($this->urlGenerator->generate($this->config['payment_cancel_route_name'], [], UrlGeneratorInterface::ABSOLUTE_URL));

        return $redirectUrls;
    }

    /**
     * Returns configured \Paypal\Api\Payment
     *
     * @return \Paypal\Api\Payment
     */
    private function getPayment(): Payment
    {
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($this->getPayer())
            ->setRedirectUrls($this->getRedirectUrls())
            ->setTransactions(array($this->getTransaction()));

        return $payment;
    }

    /**
     * Returns array of \App\Entity\PayPal
     *
     * @return array
     */
    public function getPaypal(): array
    {
        return $this->payPal;
    }

    /**
     * Registers payment in database
     *
     * @param string $paymentId
     */
    private function registerPayment(string $paymentId): void
    {
        $paypalTransaction = new PayPalTransaction();
        $paypalTransaction->setUser($this->tokenStorage->getToken()->getUser());
        $paypalTransaction->setPaymentId($paymentId);
        $paypalTransaction->setComplete(0);

        /*
         * TODO:: Should store collection of products in future
         */
        $paypalTransaction->setPayPal($this->getPaypal()[0]);

        $this->manager->persist($paypalTransaction);
        $this->manager->flush();
    }

    /**
     * This method initializes everything of payment and returns payment url
     *
     * @return string
     *
     * @throws \Exception
     */
    public function startPayment(): string
    {
        if (!$this->getItemList()->getItems()) {
            throw new \Exception('ItemList is empty. Use setItem() before starting payment');
        }

        $propperPayment = $this->getPayment()->create($this->getApiContext());

        $this->registerPayment($propperPayment->getId());

        return $propperPayment->getApprovalLink();
    }
}