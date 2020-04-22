<?php

namespace App\Repository;

use App\Entity\Thanks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thanks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thanks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thanks[]    findAll()
 * @method Thanks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThanksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thanks::class);
    }

    // /**
    //  * @return Thanks[] Returns an array of Thanks objects
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
    public function findOneBySomeField($value): ?Thanks
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
