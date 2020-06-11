<?php

namespace App\Controller;

use App\Entity\DevProgression;
use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CommunityController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/spoils", name="spoils")
     */
    public function showSpoils()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/maintenances", name="maintenances")
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
