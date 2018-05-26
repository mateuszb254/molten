<?php

namespace App\Repository;

use App\Entity\Guild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guild::class);
    }

    public function findGuildsForSidebar()
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->orderBy('g.points', 'DESC')
            ->setMaxResults(Guild::GUILDS_PER_PAGE_SIDEBAR)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findGuildsForMainRanking(int $page)
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
}
