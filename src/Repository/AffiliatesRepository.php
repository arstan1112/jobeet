<?php

namespace App\Repository;

use App\Entity\Affiliates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

//use Doctrine\ORM\EntityRepository;


/**
 * @method Affiliates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affiliates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affiliates[]    findAll()
 * @method Affiliates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffiliatesRepository extends ServiceEntityRepository
//class AffiliatesRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affiliates::class);
    }

    /**
     * @param string $token
     *
     * @return Affiliates|null
     * @throws NonUniqueResultException
     */
    public function findOneActiveByToken(string $token) : ?Affiliates
    {
        return $this->createQueryBuilder('a')
            ->where('a.active = :active')
            ->andWhere('a.token = :token')
            ->setParameter('active', true)
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Affiliates[] Returns an array of Affiliates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Affiliates
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
