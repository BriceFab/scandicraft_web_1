<?php

namespace App\Repository;

use App\Entity\ThanksCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThanksCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThanksCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThanksCategory[]    findAll()
 * @method ThanksCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThanksCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThanksCategory::class);
    }

    // /**
    //  * @return ThanksCategory[] Returns an array of ThanksCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ThanksCategory
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
