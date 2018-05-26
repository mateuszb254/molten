<?php

namespace App\Controller\Admin;

use App\Entity\ShopProduct;
use App\Form\ShopProductType;
use App\Repository\ShopProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acp/shop/products")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ShopProductController extends Controller
{
    /**
     * @Route("/", name="shop_product_index", methods="GET")
     */
    public function index(ShopProductRepository $shopProductRepository): Response
    {
        return $this->render('admin/shop_product/index.html.twig', ['shop_products' => $shopProductRepository->findAll()]);
    }

    /**
     * @Route("/new", name="shop_product_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $shopProduct = new ShopProduct();
        $form = $this->createForm(ShopProductType::class, $shopProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopProduct);
            $em->flush();

            return $this->redirectToRoute('shop_product_index');
        }

        return $this->render('admin/shop_product/new.html.twig', [
            'shop_product' => $shopProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_product_show", methods="GET")
     */
    public function show(ShopProduct $shopProduct): Response
    {
        return $this->render('admin/shop_product/show.html.twig', ['shop_product' => $shopProduct]);
    }

    /**
     * @Route("/{id}/edit", name="shop_product_edit", methods="GET|POST")
     */
    public function edit(Request $request, ShopProduct $shopProduct): Response
    {
        $form = $this->createForm(ShopProductType::class, $shopProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop_product_edit', ['id' => $shopProduct->getId()]);
        }

        return $this->render('admin/shop_product/edit.html.twig', [
            'shop_product' => $shopProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_product_delete", methods="DELETE")
     */
    public function delete(Request $request, ShopProduct $shopProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopProduct->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopProduct);
            $em->flush();
        }

        return $this->redirectToRoute('shop_product_index');
    }
}
