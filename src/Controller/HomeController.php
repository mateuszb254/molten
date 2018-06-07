<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function index(ArticleRepository $articles): Response
    {
        $threeLastArticles = $articles->findFirstArticles();

        $countOfArticles = $articles->getCountOfArticles();

        return $this->render('user/index.html.twig', [
            'articles' => $threeLastArticles,
            'countOfArticles' => $countOfArticles
        ]);
    }

    /**
     * @Route("/terms", name="terms")
     * @Method("GET")
     */
    public function rules(): Response
    {
        return $this->render('user/terms.html.twig');
    }

    /**
     * @Route("/download", name="download")
     * @Method("GET")
     */
    public function download(): Response
    {
        return $this->render('user/download.html.twig');
    }


    /**
     * @Route("/test")
     */
    public function test(): Response {



    }
}
