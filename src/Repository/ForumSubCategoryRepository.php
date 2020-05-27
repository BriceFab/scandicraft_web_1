<?php

namespace App\Repository;

use App\Entity\ForumSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumSubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumSubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumSubCategory[]    findAll()
 * @method ForumSubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumSubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumSubCategory::class);
    }
}
