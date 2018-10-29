<?php

namespace App\Controller\Admin;

use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Form\ShopCategoryCollectionType;
use App\Form\ShopType;
use App\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/acp/shop/category")
 */
class ShopController extends AbstractController implements AdminControllerInterface
{
    /**
     * @Route("", name="admin_shop_index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ShopCategoryCollectionType::class);
        /** @var ShopCategory[] $originalCategories */
        $originalCategories = $form->get('categories')->getData();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var ShopCategory[] $categories */
            $categories = $form->get('categories')->getData();

            foreach ($categories as $category) {
                if (!in_array($category, $originalCategories)) $em->persist($category);
            }

            foreach ($originalCategories as $originalCategory) {
                if (!in_array($originalCategory, $categories)) $em->remove($originalCategory);
            }

            $em->flush();

            return $this->redirectToRoute('admin_shop_index');
        }

        return $this->render('admin/shop/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_shop_edit", methods={"GET", "POST"}))
     */
    public function edit(Request $request, ShopCategory $shopCategory, ItemRepository $itemRepository): Response
    {
        $form = $this->createForm(ShopType::class, $shopCategory);
        /** @var ShopCategory $originalShopCategory */
        $originalProducts = clone $shopCategory->getProducts();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var ShopCategory $shopCategory */
            $shopCategory = $form->getData();

            foreach ($shopCategory->getProducts() as $product) {
                if (!$originalProducts->contains($product)) {
                    $shopCategory->addProduct($product);
                }
            }

            foreach ($originalProducts as $product) {
                if (!$shopCategory->getProducts()->contains($product)) {
                    $shopCategory->removeProduct($product);
                }
            }

            $em->flush();

            return $this->redirectToRoute('admin_shop_edit', [
                'id' => $shopCategory->getId()
            ]);
        }

        return $this->render('admin/shop/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $shopCategory,
            'items' => $itemRepository->findAll()
        ]);
    }
}