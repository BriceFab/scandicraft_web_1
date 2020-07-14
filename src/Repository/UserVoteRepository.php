<?php

namespace App\Repository;

use App\Entity\UserVote;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVote[]    findAll()
 * @method UserVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVote::class);
    }

    /**
     * @return UserVote[] Returns an array of UserVote objects
     */
    public function getTopVotes()
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
            ->addSelect($qb->expr()->count('v.user') . 'AS nbr_votes')
            ->innerJoin('v.user', 'user')
            ->setParameter('act_month', (new \DateTime('now'))->format("m"))
            ->where('substring(v.createdAt, 6, 2) = :act_month')
            ->groupBy('v.user')
            ->orderBy('nbr_votes', 'DESC')
            ->setMaxResults(25)
            ->getQuery()
            ->getResult();
    }
}
