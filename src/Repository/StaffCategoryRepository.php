<?php

namespace App\Repository;

use App\Entity\Staff;
use App\Entity\StaffCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StaffCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffCategory[]    findAll()
 * @method StaffCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffCategory::class);
    }
}
