<?php

namespace App\EventListener;

use App\Entity\User;
use App\Entity\UserIp;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Get the User entity.
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        /** @var User $user */
        $current_time = new DateTime('now');
        $user->setLastLogin($current_time);

        $ip = $event->getRequest()->getClientIp();
        $user_ips = $user->getUserIps()->map(function ($current_ip) {
            /** @var UserIp $current_ip */
            return $current_ip->getIp();
        })->toArray();

        if (!in_array($ip, $user_ips, true)) {  //ajoute l'IP si pas déjà enregistrée
            //max log IP = 20 (update first date ip)
            if (count($user_ips) >= 20) {
                $sort_user_ips = $user->getUserIps()->toArray();
                usort($sort_user_ips, function ($previous, $next) {
                    /** @var UserIp $previous */
                    /** @var UserIp $next */
                    if ($previous !== null && $next != null) {
                        return strtotime($previous->getLoginAt()->format('d-m-Y')) - strtotime($next->getLoginAt()->format('d-m-Y'));
                    }
                });

                if (count($sort_user_ips) > 0) {
                    /** @var UserIp $earliest */
                    $earliest = $sort_user_ips[0];

                    /** @var UserIp $earliest_user_ip */
                    $earliest_user_ip = $this->em->getRepository(UserIp::class)->find($earliest->getId());
                    $earliest_user_ip->setIp($ip);
                    $earliest_user_ip->setLoginAt($current_time);

                    $this->em->persist($earliest_user_ip);
                }
            } else {
                $user_ip = new UserIp();
                $user_ip->setUser($user);
                $user_ip->setIp($ip);
                $user_ip->setLoginAt($current_time);
                // Persist the data to database.
                $this->em->persist($user_ip);
            }
        }

        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();
    }
}
