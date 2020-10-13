<?php

namespace App\Controller;

use App\Entity\DevProgression;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommunityController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/maintenances", name="maintenances", options={"sitemap"="true"})
     */
    public function showMaintenances()
    {
        return $this->render('maintenance/show_maintenances.html.twig', [
            'maintenances' => $this->em->getRepository(DevProgression::class)->findBy(['under_maintenance' => true], [
                'pourcentage' => 'desc'
            ]),
        ]);
    }
}
