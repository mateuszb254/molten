<?php

namespace App\Controller;

use App\Entity\ShopCategory;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop")
 * @Security("has_role('ROLE_USER')")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("", name="shop_index", methods={"GET"})
     */
    public function index(ShopCategoryRepository $shopCategoryRepository): Response
    {
        $categories = $shopCategoryRepository->findAllActiveCategories();

        return $this->render('user/shop/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{slug}", name="shop_products", methods={"GET", "POST"})
     */
    public function products(ShopCategory $shopCategory, ShopCategoryRepository $shopCategoryRepository): Response
    {
        $categories = $shopCategoryRepository->findAllActiveCategories();

        return $this->render('user/shop/products.html.twig', [
            'categories' => $categories,
            'shopCategory' => $shopCategory
        ]);
    }

    /**
     * @Route("/buy", name="shop_buy", methods={"POST"})
     */
    public function buy(ShopProductRepository $productRepository)
    {

    }
}