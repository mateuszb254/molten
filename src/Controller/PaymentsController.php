<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payments")
 */
class PaymentsController extends AbstractController
{
    /**
     * @Route("", name="payments_index")
     */
    public function index(): Response
    {
        return $this->render('user/payments/index.html.twig');
    }
}