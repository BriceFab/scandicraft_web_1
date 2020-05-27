<?php

namespace App\Repository;

use App\Entity\ForumCategory;
use App\Entity\ForumSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCategory[]    findAll()
 * @method ForumCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumCategory::class);
    }

    public function getMainCategories($type)
    {
        return $this->createQueryBuilder('c')
            ->where('TYPE(c) = :type')
            ->andWhere('c.active = true')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }

    public static function getMainCategoriesFromAdmin(EntityRepository $repo)
    {
        return $repo->createQueryBuilder('c')
            ->where('TYPE (c) = :type')
            ->setParameter('type', 'forumcategory')
            ->orderBy('c.name', 'ASC');
    }
}
