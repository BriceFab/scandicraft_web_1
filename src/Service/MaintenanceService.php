<?php

namespace App\Service;

use App\Entity\DevProgression;
use Doctrine\ORM\EntityManagerInterface;

class MaintenanceService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function count_maintenances()
    {
        return $this->em->getRepository(DevProgression::class)->countMaintenances();
    }
}
