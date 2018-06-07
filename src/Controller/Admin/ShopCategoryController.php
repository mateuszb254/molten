<?php

namespace App\Controller\Admin;

use App\Entity\ShopCategory;
use App\Form\ShopCategoryType;
use App\Repository\ShopCategoryRepository;
use App\Service\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acp/shop/categories")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ShopCategoryController extends Controller implements AdminControllerInterface
{
    /**
     * @Route("/", name="shop_category_index", methods="GET")
     */
    public function index(ShopCategoryRepository $shopCategoryRepository): Response
    {
        return $this->render('admin/shop_category/index.html.twig', ['shop_categories' => $shopCategoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="shop_category_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $shopCategory = new ShopCategory();
        $form = $this->createForm(ShopCategoryType::class, $shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCategory->setSlug(Slugger::slugify($shopCategory->getName()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($shopCategory);
            $em->flush();

            return $this->redirectToRoute('shop_category_index');
        }

        return $this->render('admin/shop_category/new.html.twig', [
            'shop_category' => $shopCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_category_show", methods="GET")
     */
    public function show(ShopCategory $shopCategory): Response
    {
        return $this->render('admin/shop_category/show.html.twig', ['shop_category' => $shopCategory]);
    }

    /**
     * @Route("/{id}/edit", name="shop_category_edit", methods="GET|POST")
     */
    public function edit(Request $request, ShopCategory $shopCategory): Response
    {
        $form = $this->createForm(ShopCategoryType::class, $shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop_category_edit', ['id' => $shopCategory->getId()]);
        }

        return $this->render('shop_category/edit.html.twig', [
            'shop_category' => $shopCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_category_delete", methods="DELETE")
     */
    public function delete(Request $request, ShopCategory $shopCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopCategory->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopCategory);
            $em->flush();
        }

        return $this->redirectToRoute('shop_category_index');
    }
}
