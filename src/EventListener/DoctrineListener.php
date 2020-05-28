<?php

namespace App\EventListener;

use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumSubCategory;
use App\Entity\Survey;
use App\Entity\SurveyAnswerList;
use App\Service\ScandiCraftService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class DoctrineListener
{
    private $security;
    private $em;
    private $sc_service;

    public function __construct(Security $security, EntityManagerInterface $em, ScandiCraftService $sc_service)
    {
        $this->security = $security;
        $this->em = $em;
        $this->sc_service = $sc_service;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Survey) {
            $entity->setUser($this->security->getUser());
        }
        //  elseif ($entity instanceof SurveyAnswerList) {
        //     $entity->setCreatedBy($this->security->getUser());
        // } elseif ($entity instanceof ForumCategory || $entity instanceof ForumSubCategory) {
        //     $entity->setCreatedBy($this->security->getUser());
        // }

        //setCreatedAt
        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new DateTime('now'));
        }
        //setCreatedBy
        if (method_exists($entity, 'setCreatedBy')) {
            $entity->setCreatedBy($this->security->getUser());
        }

        if ($entity instanceof ForumDiscussion) {
            $entity->setSlug($this->sc_service->generateSlug($entity->getTitle()));
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ForumDiscussion) {
            $entity->setSlug($entity->getSlug() . '-' . $entity->getId());
            $this->em->persist($entity);
            $this->em->flush();
        }

        if ($entity instanceof Survey) {
            $entity->setSlug($entity->getSlug() . '-' . $entity->getId());
            $this->em->persist($entity);
            $this->em->flush();
        }
    }
}
