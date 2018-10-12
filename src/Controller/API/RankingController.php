<?php

namespace App\Controller\API;

use App\Repository\GuildRepository;
use App\Repository\PlayerRepository;
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
     * @Route("/players/{name}", requirements={"name" : "[a-zA-Z0-9]+"}, methods={"GET"})
     */
    public function player(Request $request, PlayerRepository $playerRepository, string $name): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('ranking_players');
        }

        return $this->json($playerRepository->findPlayerWithPositionByName($name));
    }

    /**
     * @Route("/guilds", methods={"GET"}, name="ranking_guild_search")
     * @Route("/guilds/{name}", requirements={"name" : "[a-zA-Z0-9_]+"}, methods={"GET"})
     */
    public function guild(Request $request, GuildRepository $guildRepository, string $name)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('ranking_guilds');
        }

        return $this->json($guildRepository->findGuildWithPositionByName($name));
    }
}