<?php

namespace App\Repository;

use App\Entity\SpoilGoalType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpoilGoalType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpoilGoalType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpoilGoalType[]    findAll()
 * @method SpoilGoalType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpoilGoalTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpoilGoalType::class);
    }

    // /**
    //  * @return SpoilGoalType[] Returns an array of SpoilGoalType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpoilGoalType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
