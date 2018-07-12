<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/acp/"shop/category")
 */
class ShopCategoryController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("", name="admin_shop_category_show", methods={"GET"})
     */
    public function index(): Response
    {
        return new Response('admin_shop_category_show');
    }

    /**
     * @Route("/new", name="admin_shop_category_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return new Response('admin_shop_category_new');
    }

    /**
     * @Route("/edit/{id}", name="admin_shop_category_edit", methods={"GET", "POST"})
     */
    public function edit(): Response
    {
        return new Response('admin_shop_category_edit');
    }

    /**
     * @Route("/delete/{id}", name="admin_shop_category_edit", methods={"GET", "POST"})
     */
    public function delete(): Response
    {
        return new Response('admin_shop_category_edit');
    }
}