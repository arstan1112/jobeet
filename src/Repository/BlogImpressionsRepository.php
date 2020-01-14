<?php

namespace App\Repository;

use App\Entity\BlogImpressions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogImpressions|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogImpressions|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogImpressions[]    findAll()
 * @method BlogImpressions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogImpressionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogImpressions::class);
    }

    public function findWithUser($blogTopicId, $userId)
    {
        return $this->createQueryBuilder('i')
            ->where('i.blogTopic = :topicId')
            ->andWhere('i.user = :userId')
            ->setParameter('topicId', $blogTopicId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return BlogImpressions[] Returns an array of BlogImpressions objects
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
    public function findOneBySomeField($value): ?BlogImpressions
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
