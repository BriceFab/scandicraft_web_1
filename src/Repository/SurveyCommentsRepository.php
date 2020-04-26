<?php

namespace App\Repository;

use App\Entity\SurveyComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyComments[]    findAll()
 * @method SurveyComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyComments::class);
    }

    // /**
    //  * @return SurveyComments[] Returns an array of SurveyComments objects
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
    public function findOneBySomeField($value): ?SurveyComments
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
