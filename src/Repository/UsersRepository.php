<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

class UsersRepository extends ServiceEntityRepository
{
    /**
     * UsersRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $id
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findUserById(int $id) :?User
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    /**
//     * @param int $id
//     *
//     * @return Categories|null
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */
//    public function findCategoryById(int $id) : ?Categories
//    {
//        return $this->createQueryBuilder('c')
//            ->select('c')
//            ->where('c.id = :id')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }
}
