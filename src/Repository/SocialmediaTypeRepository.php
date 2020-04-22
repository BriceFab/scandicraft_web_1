<?php

namespace App\Repository;

use App\Entity\SocialmediaType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialmediaType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialmediaType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialmediaType[]    findAll()
 * @method SocialmediaType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialmediaTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialmediaType::class);
    }

    // /**
    //  * @return SocialmediaType[] Returns an array of SocialmediaType objects
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
    public function findOneBySomeField($value): ?SocialmediaType
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
