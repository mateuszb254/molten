<?php

namespace App\Repository;

use App\Entity\Guild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Guild|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guild|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guild[]    findAll()
 * @method Guild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuildRepository extends ServiceEntityRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * GuildRepository constructor.
     * @param RegistryInterface $registry
     * @param Connection $connection
     */
    public function __construct(RegistryInterface $registry, Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct($registry, Guild::class);
    }

    public function findGuildsForSidebar(): array
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->orderBy('g.points', 'DESC')
            ->setMaxResults(Guild::GUILDS_PER_PAGE_SIDEBAR)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findGuildsForMainRanking(int $page): Pagerfanta
    {
        $query = $this->createQueryBuilder('g')
            ->orderBy('g.points', 'DESC')
            ->setMaxResults(Guild::GUILDS_PER_PAGE_MAIN)
            ->getQuery();

        return $this->createPagination($query, $page);
    }

    private function createPagination(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Guild::GUILDS_PER_PAGE_MAIN);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    public function findGuildWithPositionByName(string $guildName)
    {
        $stmt = $this->connection->prepare('
            SELECT `name`, `wins`, `loses`, `kingdom`, `points`, `position` FROM (
                SELECT
                  `name`, `wins`, `loses`, `kingdom`, `points`, @position := @position +1 as position
                FROM 
                  `guild`
                JOIN (SELECT @position := 0) as pos
                ORDER BY `points` DESC
            ) as guilds
              WHERE `name` = :guildName
              LIMIT 1;
            ;
        ');

        $stmt->bindValue(':guildName', $guildName);
        $stmt->execute();

        return $stmt->fetch();
    }
}
