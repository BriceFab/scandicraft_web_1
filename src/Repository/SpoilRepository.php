<?php

namespace App\Repository;

use App\Entity\Spoil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spoil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spoil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spoil[]    findAll()
 * @method Spoil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpoilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spoil::class);
    }

    // /**
    //  * @return Spoil[] Returns an array of Spoil objects
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
    public function findOneBySomeField($value): ?Spoil
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
