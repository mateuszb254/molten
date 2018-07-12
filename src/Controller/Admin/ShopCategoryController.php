<?php

namespace App\Controller\Admin;

use App\Entity\ShopCategory;
use App\Form\ShopCategoryType;
use App\Repository\ShopCategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/acp/shop/category")
 */
class ShopCategoryController extends AbstractController implements AdminControllerInterface
{
    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("", name="admin_shop_category_show", methods={"GET"})
     */
    public function index(ShopCategoryRepository $shopCategoryRepository): Response
    {
        return $this->render('admin/shop/category/index.html.twig', [
            'categories' => $shopCategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="admin_shop_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $shopCategory = new ShopCategory();

        $form = $this->createForm(ShopCategoryType::class, $shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopCategory);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('shop.category.new.success'));
            return $this->redirectToRoute('admin_shop_category_new');
        }

        return $this->render('admin/shop/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_shop_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ShopCategory $shopCategory): Response
    {
        $form = $this->createForm(ShopCategoryType::class, $shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $this->translator->trans('shop.category.edit.success'));
            return $this->redirectToRoute('admin_shop_category_show');
        }

        return $this->render('admin/shop/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_shop_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ShopCategory $shopCategory): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shopCategory->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopCategory);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('shop.category.delete.success'));
        }

        return $this->redirectToRoute('admin_shop_category_show');
    }
}