<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findPlayersForSidebar(): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.level', 'DESC')
            ->setMaxResults(Player::PLAYERS_PER_PAGE_SIDEBAR)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findPlayersForMainRanking(int $page): Pagerfanta
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.level', 'DESC')
            ->setMaxResults(Player::PLAYERS_PER_PAGE_MAIN)
            ->getQuery();

        return $this->createPagination($query, $page);
    }

    private function createPagination(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Player::PLAYERS_PER_PAGE_MAIN);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
