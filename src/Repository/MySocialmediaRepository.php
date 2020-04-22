<?php

namespace App\Repository;

use App\Entity\MySocialmedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MySocialmedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method MySocialmedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method MySocialmedia[]    findAll()
 * @method MySocialmedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MySocialmediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MySocialmedia::class);
    }

    public function getSocialmedia()
    {
        return $this->createQueryBuilder('s')
        ->Join('s.socialmedia_type','t')
        ->andWhere('t.enable = :isEnable')
        ->setParameter('isEnable', true)
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return MySocialmedia[] Returns an array of MySocialmedia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MySocialmedia
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
