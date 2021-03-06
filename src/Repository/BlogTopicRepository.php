<?php

namespace App\Repository;

use App\Entity\BlogTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogTopic[]    findAll()
 * @method BlogTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTopic::class);
    }



    // /**
    //  * @return BlogTopic[] Returns an array of BlogTopic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogTopic
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
