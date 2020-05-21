<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FinishRequestEvent::class => 'onKernelFinishRequest'
        ];
    }

    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        //rien pour le moment
    }
}
