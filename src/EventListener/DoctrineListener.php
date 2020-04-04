<?php

namespace App\EventListener;

use App\Entity\Survey;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class DoctrineListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Survey) {
            $entity->setUser($this->security->getUser());
        }
    }
}
