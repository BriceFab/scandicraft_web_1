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
    private $unlogUri = [
        '/api/login_check',
        '/connexion',
        '/inscription',
        'ActionLog',
        'ExceptionLog',
        'survey'
    ];

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
        $uri = $event->getRequest()->getRequestUri();
        if (strtoupper($event->getRequest()->getMethod()) !== 'GET' && $this->mustLogsUri($uri)) {
            $log = new ActionLog();
            $log->setMethod($method);
            $log->setUri($uri);
            if ($this->tokenStorage->getToken() !== null) {
                $log->setUser($this->tokenStorage->getToken()->getUser());
            }
            $log->setRequestAt(new DateTime('now'));
            $log->setResponseCode($event->getResponse()->getStatusCode());
            $log->setIp($event->getRequest()->getClientIp());

            $this->em->persist($log);
            $this->em->flush();
        }
    }

    private function mustLogsUri($uri)
    {
        if (in_array($uri, $this->unlogUri)) {
            return false;
        } else {
            foreach ($this->unlogUri as $key => $value) {
                if (strpos($uri, $value) !== false) {
                    return false;
                }
            }
        }

        return true;
    }
}
