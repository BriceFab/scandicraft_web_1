<?php

namespace App\Repository;

use App\Entity\SpoilShare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpoilShare|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpoilShare|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpoilShare[]    findAll()
 * @method SpoilShare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpoilShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpoilShare::class);
    }

    // /**
    //  * @return SpoilShare[] Returns an array of SpoilShare objects
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
    public function findOneBySomeField($value): ?SpoilShare
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
