<?php

namespace App\EventListener;

use App\Entity\ActionLog;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogAction implements EventSubscriberInterface
{
    private $em;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        if (strtoupper($event->getRequest()->getMethod()) !== 'GET') {
            $log = new ActionLog();
            $log->setMethod($method);
            $log->setUri($event->getRequest()->getRequestUri());
            $log->setUsername($this->tokenStorage->getToken()->getUsername());
            $log->setRequestAt(new DateTime());
            $log->setResponseCode($event->getResponse()->getStatusCode());
            $log->setIp($event->getRequest()->getClientIp());

            $this->em->persist($log);
            $this->em->flush();
        }
    }
}
