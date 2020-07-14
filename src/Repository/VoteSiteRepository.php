<?php

namespace App\Repository;

use App\Entity\VoteSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoteSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteSite[]    findAll()
 * @method VoteSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteSite::class);
    }

    // /**
    //  * @return VoteSite[] Returns an array of VoteSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoteSite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
