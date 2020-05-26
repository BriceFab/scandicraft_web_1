<?php

namespace App\Repository;

use App\Entity\ForumSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumSubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumSubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumSubCategory[]    findAll()
 * @method ForumSubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumSubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumSubCategory::class);
    }

    // /**
    //  * @return ForumSubCategory[] Returns an array of ForumSubCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumSubCategory
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
