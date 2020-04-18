<?php

namespace App\Controller;

use App\Entity\DevProgression;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'maintenances' => $this->em->getRepository(DevProgression::class)->findBy([], [
                'pourcentage' => 'desc'
            ]),
        ]);
    }

    /**
     * @Route("/jouer", name="jouer")
    */
    public function jouer() {
        return $this->render('jouer/index.html.twig');
    }

    /**
     * @Route("/maintenance", name="maintenance")
    */
    public function maintenance() {
        return $this->render('home/maintenance.html.twig');
    }
}
