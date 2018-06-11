<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayPalTransactionRepository")
 */
class PayPalTransaction
{
    const PAYMENT_INCOMPLETE = 0;
    const PAYMENT_COMPLETE = 1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $payment_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PayPal")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paypal;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $complete = self::PAYMENT_COMPLETE;

    public function getId()
    {
        return $this->id;
    }

    public function getPaymentId(): ?string
    {
        return $this->payment_id;
    }

    public function setPaymentId(string $payment_id): self
    {
        $this->payment_id = $payment_id;

        return $this;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(?Account $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPayPal(): ?PayPal
    {
        return $this->paypal;
    }

    public function setPayPal(?PayPal $paypal): self
    {
        $this->paypal = $paypal;

        return $this;
    }

    public function getComplete(): ?int
    {
        return $this->complete;
    }

    public function setComplete(int $complete): self
    {
        $this->complete = $complete;

        return $this;
    }
}
