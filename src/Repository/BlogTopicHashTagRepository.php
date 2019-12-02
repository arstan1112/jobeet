<?php

namespace App\Repository;

use App\Entity\BlogTopicHashTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogTopicHashTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogTopicHashTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogTopicHashTag[]    findAll()
 * @method BlogTopicHashTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTopicHashTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTopicHashTag::class);
    }

    // /**
    //  * @return BlogTopicHashTag[] Returns an array of BlogTopicHashTag objects
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
    public function findOneBySomeField($value): ?BlogTopicHashTag
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
