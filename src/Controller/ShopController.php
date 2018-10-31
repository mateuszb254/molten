<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\ShopCategory;
use App\Entity\UserLog;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/shop")
 * @Security("has_role('ROLE_USER')")
 */
class ShopController extends AbstractController implements UserControllerInterface
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
    public function products(ShopCategory $shopCategory, ShopCategoryRepository $shopCategoryRepository, TranslatorInterface $translator): Response
    {
        if ($shopCategory->getStatus() === ShopCategory::STATUS_INACTIVE) {
            throw $this->createNotFoundException($translator->trans('shop.category.inactive'));
        }

        $categories = $shopCategoryRepository->findAllActiveCategories();

        return $this->render('user/shop/products.html.twig', [
            'categories' => $categories,
            'shopCategory' => $shopCategory
        ]);
    }

    /**
     * @Route("/{slug}/buy", name="shop_buy", methods={"GET", "POST"})
     */
    public function buy(Request $request, ShopProductRepository $productRepository, TranslatorInterface $translator, TokenStorageInterface $tokenStorage, string $slug): Response
    {
        $productId = $request->get('product', 0);

        $product = $productRepository->find($productId);

        if (!$product) {
            throw $this->createNotFoundException($translator->trans('shop.product.not.found'));
        }

        if ($product->getCategory()->getStatus() === ShopCategory::STATUS_INACTIVE) {
            throw $this->createNotFoundException($translator->trans('shop.product.inactive'));
        }

        /** @var Account $user */
        $user = $tokenStorage->getToken()->getUser();

        if ($user->getCoins() < $product->getPrice()) {
            $this->addFlash('alert', $translator->trans('shop.not.enough.coins'));

            return $this->redirectToRoute('shop_products', [
                'slug' => $slug
            ]);
        }

        if ($user->hasItem($product->getItem())) {
            $this->addFlash('alert', $translator->trans('shop.item.already.having'));

            return $this->redirectToRoute('shop_products', [
                'slug' => $slug
            ]);
        }

        $user->setCoins($user->getCoins() - $product->getPrice());
        $user->addItem($product->getItem());

        $em = $this->getDoctrine()->getManager();

        $userLog = new UserLog();
        $userLog->setUser($user);
        $userLog->setProduct($product);
        $userLog->setType('BUY_ITEM');

        $em->persist($user);
        $em->persist($userLog);
        $em->flush();

        $this->addFlash('success', $translator->trans('shop.product.bought'));
        return $this->redirectToRoute('shop_products', [
            'slug' => $slug
        ]);
    }
}