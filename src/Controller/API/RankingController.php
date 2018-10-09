<?php

namespace App\Controller\API;

use App\Entity\Guild;
use App\Entity\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("api")
 */
class RankingController extends AbstractController implements APIControllerInterface
{
    /**
     * @Route("/players", methods={"GET"}, name="ranking_player_search")
     * @Route("/players/{name}", methods={"GET"})
     */
    public function player(Request $request, Player $player): Response
    {
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('ranking_players');
        }

        return $this->json($player);
    }

    /**
     * @Route("/guilds", methods={"GET"}, name="ranking_guild_search")
     * @Route("/guilds/{name}", methods={"GET"})
     */
    public function guild(Request $request, Guild $guild)
    {
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('ranking_guilds');
        }

        return $this->json($guild);
    }
}