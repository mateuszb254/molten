<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->loadArticles($manager);
    }

    private function loadArticles(ObjectManager $manager)
    {
        foreach ($this->getArticles() as $articleData) {
            $article = new Article();
            $article->setTitle($articleData['title']);
            $article->setContent($articleData['content']);
            $article->setCreatedAt($articleData['createdAt']);
            $article->setAuthor($articleData['author']);
            $article->setMore($articleData['more']);

            $manager->persist($article);
        }

        $manager->flush();
    }

    private function getArticles(): array
    {
        return [
            [
                'title' => 'Lorem ipsum dolor sit amet',
                'content' => 'Suspendisse ullamcorper ullamcorper arcu quis bibendum. Vivamus mattis magna ac est imperdiet fringilla. Vivamus non felis quis lorem fringilla mollis vel et ipsum. Nam convallis accumsan felis in rhoncus. Curabitur lacinia neque vel tristique semper. Mauris vitae arcu tincidunt dui iaculis faucibus.',
                'createdAt' => (new \DateTime())->modify('-1 DAY'),
                'author' => $this->getReference(AccountFixtures::ADMIN_USER_REFERENCE),
                'more' => 'http://nagadev.pl',
            ],
            [
                'title' => 'Donec vel cursus felis',
                'content' => 'Mauris suscipit mauris in lectus pellentesque, eget tempus dui blandit. Aenean turpis magna, ornare sit amet mattis quis, bibendum ac elit. Nullam orci sapien, finibus vestibulum tempor non, mollis in nisl. Integer cursus, elit nec sollicitudin iaculis, turpis mi semper lectus, sit amet rutrum nunc justo sed enim. Integer varius non felis sit amet semper. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi odio turpis, ornare in nunc in, tempor finibus risus.',
                'createdAt' => (new \DateTime())->modify('-7 DAY'),
                'author' => $this->getReference(AccountFixtures::ADMIN_USER_REFERENCE),
                'more' => null
            ],
            [
                'title' => 'Morbi vitae arcu nibh. Aliquam sagittis fringilla nibh at dictum. Suspendisse ullamcorper est vel viverra tempor.',
                'content' => 'nteger elit dui, hendrerit et condimentum sit amet, varius a nibh. Duis ac ultrices est, at egestas tortor. Mauris ullamcorper tristique tincidunt. Proin vel tellus lorem. ',
                'createdAt' => (new \DateTime())->modify('-7 DAY'),
                'author' => $this->getReference(AccountFixtures::ADMIN_USER_REFERENCE),
                'more' => null
            ],
            [
                'title' => 'Praesent aliquam purus',
                'content' => 'Suspendisse eget hendrerit nisi, in imperdiet purus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc feugiat varius ornare. Integer tristique nunc mauris, id molestie odio facilisis sit amet.',
                'createdAt' => (new \DateTime())->modify('-7 DAY'),
                'author' => $this->getReference(AccountFixtures::ADMIN_USER_REFERENCE),
                'more' => null
            ],
            [
                'title' => 'Etiam et nibh turpis.',
                'content' => 'Nulla sit amet vestibulum augue. Praesent id cursus felis. Phasellus eget vulputate sem. Cras suscipit enim a turpis egestas, ac vehicula risus feugiat. Maecenas eget sagittis justo. Sed sed hendrerit augue.',
                'createdAt' => (new \DateTime())->modify('-7 DAY'),
                'author' => $this->getReference(AccountFixtures::ADMIN_USER_REFERENCE),
                'more' => null
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            AccountFixtures::class
        ];
    }

}