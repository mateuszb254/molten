<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/articles")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/", name="admin_article_index", methods="GET")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/index.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin_article_new", methods="GET|POST")
     */
    public function new(Request $request, TranslatorInterface $translator): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime());
            $article->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', $translator->trans('articles.new.success', [
                '%admin_article_index%' => $this->generateUrl('admin_article_index')
            ]));
            return $this->redirectToRoute('admin_article_new');
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_article_show", methods="GET")
     */
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/{id}/edit", name="admin_article_edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', $translator->trans('article.delete.success'));
        }

        return $this->redirectToRoute('admin_article_index');
    }
}
