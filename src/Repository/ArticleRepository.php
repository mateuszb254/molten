<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getCountOfArticles()
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->getQuery();

        $result = $queryBuilder->getSingleScalarResult();

        return $result;
    }

    public function findFirstArticles()
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(Article::ARTICLES_PER_PAGE)
            ->getQuery();

        return $queryBuilder->execute();
    }

    public function findArticlesByOffset($start)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.content, a.createdAt, a.more, a.image, u.login as author')
            ->innerJoin('a.author', 'u')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults(Article::ARTICLES_PER_PAGE)
            ->getQuery();

        return $queryBuilder->execute();
    }
}
