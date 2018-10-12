<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Connection;
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
    /**
     * @var Connection
     */
    private $connection;

    /**
     * PlayerRepository constructor.
     * @param RegistryInterface $registry
     * @param Connection $connection
     */
    public function __construct(RegistryInterface $registry, Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct($registry, Player::class);
    }

    public function findPlayersForSidebar(): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->addOrderBy('p.level', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->setMaxResults(Player::PLAYERS_PER_PAGE_SIDEBAR)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findPlayersForMainRanking(int $page): Pagerfanta
    {
        $query = $this->createQueryBuilder('p')
            ->addOrderBy('p.level', 'DESC')
            ->addOrderBy('p.name', 'ASC')
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

    public function findPlayerWithPositionByName(string $playerName)
    {
        $stmt = $this->connection->prepare('
            SELECT `name`, `level`, `kingdom`, `position` FROM ( 
                SELECT 
                  `name`, `level`, `kingdom`, @position := @position +1 as position
                FROM 
                  `player` 
                JOIN 
                  (SELECT @position := 0) as row 
                ORDER BY 
                  `level` DESC, 
                  `name` ASC 
                ) as player 
                WHERE `name` = :playerName
                LIMIT 1
            ');

        $stmt->bindValue(':playerName', $playerName);
        $stmt->execute();

        return $stmt->fetch();
    }
}
