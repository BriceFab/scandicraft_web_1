<?php

namespace App\Repository;

use App\Entity\StoreCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StoreCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreCategory[]    findAll()
 * @method StoreCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreCategory::class);
    }

    // /**
    //  * @return StoreCategory[] Returns an array of StoreCategory objects
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
    public function findOneBySomeField($value): ?StoreCategory
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
