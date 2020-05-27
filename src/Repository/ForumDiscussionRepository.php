<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumDiscussion[]    findAll()
 * @method ForumDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumDiscussion::class);
    }

    // /**
    //  * @return ForumDiscussion[] Returns an array of ForumDiscussion objects
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
    public function findOneBySomeField($value): ?ForumDiscussion
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
