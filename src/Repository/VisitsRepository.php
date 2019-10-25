<?php

namespace App\Repository;

use App\Entity\Visits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Visits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visits[]    findAll()
 * @method Visits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visits::class);
    }

    public function save(Visits $visits)
    {
        $this->_em->persist($visits);
        $this->_em->flush($visits);
    }
}
