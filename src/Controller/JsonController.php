<?php
namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("json")
 */
class JsonController extends AbstractController
{
    /**
     * @Route("/articles/{start}", requirements={"start" : "[\d]+"} ,name="json_articles")
     * @Method("GET")
     *
     * @param $start integer referes start limit to fetching articles
     *
     * @return Response
     */
    public function jsonArticles(int $start): Response
    {
        $articlesRepository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articlesRepository->findArticlesByOffset($start);

        return $this->json($articles);
    }

    /**
     * @Route("/status", name="json_serverStatus")
     * @Method("GET")
     *
     * @return Response
     */
    public function serverStatus(): Response {
        /**
         * TODO
         * Get data of server status by ssh
         */
        $serverStatus = [
            'online' => 0,
            'count' => 0
        ];

        return $this->json($serverStatus);
    }
}