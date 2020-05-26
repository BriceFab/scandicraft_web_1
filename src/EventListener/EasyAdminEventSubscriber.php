<?php

namespace App\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{

    private $decisionManager;
    private $token;

    public function __construct(AccessDecisionManagerInterface $decisionManager, TokenStorageInterface $token)
    {
        $this->decisionManager = $decisionManager;
        $this->token = $token;
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

        if (!$this->decisionManager->decide($this->token->getToken(), $authorizedRoles)) {
            throw new AccessDeniedException();
        };
    }
}
