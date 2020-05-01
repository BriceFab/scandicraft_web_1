<?php

namespace App\Repository;

use App\Entity\Thanks;
use App\Entity\ThanksCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThanksCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThanksCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThanksCategory[]    findAll()
 * @method ThanksCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThanksCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThanksCategory::class);
    }
}
