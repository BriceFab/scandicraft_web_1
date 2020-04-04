<?php

namespace App\Repository;

use App\Entity\SurveyAnswers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SurveyAnswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyAnswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyAnswers[]    findAll()
 * @method SurveyAnswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyAnswersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswers::class);
    }

    // /**
    //  * @return SurveyAnswers[] Returns an array of SurveyAnswers objects
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
    public function findOneBySomeField($value): ?SurveyAnswers
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
