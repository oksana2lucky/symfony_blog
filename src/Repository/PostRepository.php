<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
    * @return Post[] Returns an array of Post objects
    */
    public function findByMoreCommentsCount($limit)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.title, p.description, p.created_at, count(comments) as comments_count')
            ->addSelect('u.first_name as user_first_name, u.last_name as user_last_name')
            ->innerJoin('p.comments', 'comments')
            ->innerJoin('p.user', 'u')
            ->groupBy('p.id')
            ->orderBy('comments_count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
