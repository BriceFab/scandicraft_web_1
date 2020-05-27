<?php

namespace App\EventListener;

use App\Entity\ForumCategory;
use App\Entity\ForumSubCategory;
use App\Entity\Survey;
use App\Entity\SurveyAnswerList;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class DoctrineListener
{
    private $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Survey) {
            $entity->setUser($this->security->getUser());
        } elseif ($entity instanceof SurveyAnswerList) {
            $entity->setCreatedBy($this->security->getUser());
        } elseif ($entity instanceof ForumCategory || $entity instanceof ForumSubCategory) {
            $entity->setCreatedBy($this->security->getUser());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Survey) {
            $entity->setSlug($entity->getSlug() . '-' . $entity->getId());
            $this->em->persist($entity);
            $this->em->flush();
        }
    }
}
