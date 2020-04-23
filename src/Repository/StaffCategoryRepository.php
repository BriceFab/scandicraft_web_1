<?php

namespace App\Repository;

use App\Entity\StaffCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StaffCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffCategory[]    findAll()
 * @method StaffCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffCategory::class);
    }

    // /**
    //  * @return StaffCategory[] Returns an array of StaffCategory objects
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
    public function findOneBySomeField($value): ?StaffCategory
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
