<?php

namespace App\Repository;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method BlogTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogTopic[]    findAll()
 * @method BlogTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTopicRepository extends ServiceEntityRepository
{
    /**
     * BlogTopicRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTopic::class);
    }


    public function findRecentTopics($tag = null)
    {
        $qb = $this
            ->createQueryBuilder('topic')
            ->orderBy('topic.createdAt', 'DESC');

        if ($tag) {
            $qb
                ->innerJoin('topic.blogTopicHashTags', 'tags')
                ->where('tags.name = :name')
                ->setParameter('name', $tag);
        }

        return $qb;
    }

    public function findRecentTopicsByTag($tag = null)
    {
        return $this
            ->createQueryBuilder('topic')
            ->orderBy('topic.createdAt', 'DESC')
            ->innerJoin('topic.blogTopicHashTags', 'tags')
            ->where('tags.name = :name')
            ->setParameter('name', $tag)
            ->getQuery()
    //                ->getResult(Query::HYDRATE_ARRAY);
            ->getResult();

//        if ($tag) {
//            $qb
//                ->innerJoin('topic.blogTopicHashTags', 'tags')
//                ->where('tags.name = :name')
//                ->setParameter('name', $tag)
//                ->getQuery()
//                ->getResult(Query::HYDRATE_ARRAY);
//                ->getResult();
//        }

//        return $qb;
    }

    /**
     * @param BlogTopicHashTag $hashTag
     * @return BlogTopic[]
     */
    public function findRecentTopicsByHashTag(BlogTopicHashTag $hashTag)
    {
        return $this->createQueryBuilder('bt')
            ->leftJoin('bt.blogTopicHashTags', 'h')
            ->where('h.id = :hashTag')
            ->setParameter('hashTag', $hashTag)
            ->orderBy('bt.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
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
