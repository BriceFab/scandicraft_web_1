<?php

namespace App\Service;

use App\Entity\MySocialmedia;
use Doctrine\ORM\EntityManagerInterface;

class ScandiCraftService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSocialmedia()
    {
        return $this->em->getRepository(MySocialmedia::class)->getSocialmedia();
    }
}
