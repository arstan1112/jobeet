<?php

namespace App\Repository;

use App\Entity\BlogComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogComment[]    findAll()
 * @method BlogComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogComment::class);
    }

    /**
     * @param int  $id
     * @param null $counter
     *
     * @return BlogComment[] Returns an array of BlogComment objects
     */
    public function findByTopicId(int $id, $counter = null)
    {
        if ($counter) {
            $offset = $counter;
        } else {
            $offset = 0;
        }
        return $this
            ->createQueryBuilder('b')
            ->innerJoin('b.blogTopic', 'topic')
            ->where('topic.id = :id')
            ->setParameter('id', $id)
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     *
     * @return BlogComment[] Returns an array of BlogComment objects
     */
    public function findByTopicIdApi(int $id)
    {
        return $this
            ->createQueryBuilder('c')
            ->innerJoin('c.blogTopic', 'topic')
            ->where('topic.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return BlogComment[] Returns an array of BlogComment objects
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
    public function findOneBySomeField($value): ?BlogComment
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
