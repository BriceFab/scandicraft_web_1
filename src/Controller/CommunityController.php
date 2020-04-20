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
     * @Route("/forum", name="forum")
     */
    public function forum()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/nouveautes", name="nouveautes")
     */
    public function showNews()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/sondages", name="sondages")
     */
    public function showSondages()
    {
        return $this->render('survey/list.html.twig');
    }

    /**
     * @Route("/maintenances", name="maintenances")
     */
    public function showMaintenances()
    {
        return $this->render('maintenance/show_maintenances.html.twig', [
            'maintenances' => $this->em->getRepository(DevProgression::class)->findBy([], [
                'pourcentage' => 'desc'
            ]),
        ]);
    }
}
