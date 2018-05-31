<?php

namespace App\Controller\API;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("json")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/articles/{start}", requirements={"start" : "[\d]+"} ,name="json_articles", methods={"GET"})
     *
     * @param $start integer referes start limit to fetching articles
     *
     * @return Response
     */
    public function articles(int $start): Response
    {
        $articlesRepository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articlesRepository->findArticlesByOffset($start);

        return $this->json($articles);
    }
}