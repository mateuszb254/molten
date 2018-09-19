<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("api")
 */
class ArticleController extends AbstractController implements APIControllerInterface
{
    /**
     * @Route("/articles/{start}", requirements={"start" : "[\d]+"} ,name="api_articles", methods={"GET"})
     *
     * @param $start integer refers start limit to fetching articles
     *
     * @return Response
     */
    public function articles(int $start, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findArticlesByOffset($start);

        return $this->json($articles);
    }
}