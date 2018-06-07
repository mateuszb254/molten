<?php
namespace App\Controller\API;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("json")
 */
class ServerController extends AbstractController implements APIControllerInterface
{
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