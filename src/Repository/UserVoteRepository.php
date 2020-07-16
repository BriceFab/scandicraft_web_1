<?php

namespace App\Repository;

use App\Entity\UserVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Liste des x top voter
     * @param int $max nombre max de top
     * @return UserVote[] Returns an array of UserVote objects
     */
    public function getTopVotes($max = 15)
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
            ->addSelect($qb->expr()->count('v.user') . 'AS nbr_votes')
            ->innerJoin('v.user', 'user')
            ->setParameter('act_month', (new \DateTime('now'))->format("m"))
            ->where('substring(v.createdAt, 6, 2) = :act_month')
            ->groupBy('v.user')
            ->orderBy('nbr_votes', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Les votes du mois du joueur
     * @param UserInterface $user joueur
     * @return int|mixed|string
     */
    public function getUserMonthlyVotes(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
            ->innerJoin('v.user', 'user')
            ->setParameter('act_month', (new \DateTime('now'))->format("m"))
            ->setParameter('user', $user)
            ->where('substring(v.createdAt, 6, 2) = :act_month')
            ->andWhere('v.user = :user')
            ->setMaxResults(25)
            ->getQuery()
            ->getResult();
    }
}
