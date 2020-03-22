<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function count_register_users()
    {
        return $this->em->getRepository(User::class)->countUsers();
    }

    public function count_online_users() {
        return 0;
    }
}
