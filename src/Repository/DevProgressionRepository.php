<?php

namespace App\Repository;

use App\Entity\DevProgression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DevProgression|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevProgression|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevProgression[]    findAll()
 * @method DevProgression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevProgressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevProgression::class);
    }

    // /**
    //  * @return DevProgression[] Returns an array of DevProgression objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DevProgression
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
