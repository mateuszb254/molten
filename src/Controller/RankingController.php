<?php
namespace App\Controller;

use App\Entity\Guild;
use App\Entity\Player;
use App\Repository\GuildRepository;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ranking")
 */
class RankingController extends AbstractController implements UserControllerInterface
{
    /**
     * @Route("/", name="ranking_index")
     * @Route("/players/{page}", requirements={"page": "\d+"}, defaults={"page" : 1}, name="ranking_players")
     */
    public function players(Request $request, PlayerRepository $playerRepository, int $page = 1): Response
    {
       $players = $playerRepository->findPlayersForMainRanking($page);

        return $this->render('user/ranking/players.html.twig', [
            'players' => $players
        ]);
    }

    /**
     * @Route("/guilds/{page}", requirements={"page": "\d+"}, defaults={"page": 1}, name="ranking_guilds")
     */
    public function guilds(Request $request, GuildRepository $guildRepository, int $page = 1): Response
    {
        $guilds = $guildRepository->findGuildsForMainRanking($page);

        return $this->render('user/ranking/guilds.html.twig', [
            'guilds' => $guilds
        ]);
    }

    public function rankingSidebar(PlayerRepository $playerRepository, GuildRepository $guildRepository)
    {
        $players = $playerRepository->findPlayersForSidebar();
        $guilds = $guildRepository->findGuildsForSidebar();

        return $this->render('user/ranking/sidebar.html.twig', [
            'players' => $players,
            'guilds' => $guilds
        ]);
    }
}