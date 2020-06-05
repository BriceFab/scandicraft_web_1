<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use App\Entity\ForumSubCategory;
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
    private $status_repo;

    public function __construct(ManagerRegistry $registry, ForumDiscussionStatusRepository $status_repo)
    {
        parent::__construct($registry, ForumDiscussion::class);
        $this->status_repo = $status_repo;
    }

    public function findAllFromCategory(ForumSubCategory $sub_cat)
    {
        return $this->createQueryBuilder('d')
            ->where('d.sub_category = :sub_cat_id')
            ->setParameter('sub_cat_id', $sub_cat->getId())
            ->getQuery()
            ->execute();
    }

    public function createStatusQueryBuilder($discussion_staff_only)
    {
        return $this->status_repo->createStatusQueryBuilder($discussion_staff_only);
    }
}
