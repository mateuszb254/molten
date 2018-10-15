<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acp")
 * @Security("has_role('ROLE_ACP_ACCESS')")
 */
class HomeController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("", name="admin_index")
     * @Method("GET")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}