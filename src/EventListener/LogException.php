<?php

namespace App\EventListener;

use App\Entity\ExceptionLog;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Throwable;

class LogException implements EventSubscriberInterface
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
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                // ['logException', 0],
                // ['notifyException', -10],
            ],
        ];
    }

    public function processException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $log = new ExceptionLog();
        $log->setMethod($event->getRequest()->getMethod());
        $log->setUri($event->getRequest()->getUri());
        if ($this->tokenStorage->getToken() !== null && $this->tokenStorage->getToken()->isAuthenticated()) {
            if ($this->tokenStorage->getToken()->getUser() instanceof User) { //n'est pas anonymous
                $log->setUser($this->tokenStorage->getToken()->getUser());
            }
        }
        $log->setExceptionMessage($exception->getMessage());
        $code = null;
        if (property_exists($exception, 'statusCode')) {
            $code = $exception->getStatusCode();
        } else {
            $code = $exception->getCode();
        }
        $log->setExceptionCode($code);
        $log->setIp($event->getRequest()->getClientIp());
        $log->setCreatedAt(new DateTime('now'));

        if ($this->em->isOpen()) {
            $this->em->persist($log);
            $this->em->flush();
        }
    }
}
