<?php

namespace App\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return array(
            EasyAdminEvents::PRE_LIST => array('isAuthorized'),
            EasyAdminEvents::PRE_EDIT => array('isAuthorized'),
            EasyAdminEvents::PRE_DELETE => array('isAuthorized'),
            EasyAdminEvents::PRE_NEW => array('isAuthorized'),
            EasyAdminEvents::PRE_SHOW => array('isAuthorized'),
        );
    }

    public function isAuthorized(GenericEvent $event)
    {
        $entityConfig = $event['entity'];

        $action = $event->getArgument('request')->query->get('action');

        if (!array_key_exists('permissions', $entityConfig) or !array_key_exists($action, $entityConfig['permissions'])) {
            return;
        }

        $authorizedRoles = $entityConfig['permissions'][$action];

        if (!$this->checkIsAutorized($authorizedRoles)) {
            throw new AccessDeniedException();
        };
    }

    private function checkIsAutorized($access_roles)
    {
        foreach ($access_roles as $key => $access_role) {
            $can_access = $this->security->isGranted($access_role);
            if ($can_access) {
                return true;
            }
        }
        return false;
    }
}
