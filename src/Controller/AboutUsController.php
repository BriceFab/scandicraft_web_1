<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    /**
     * @Route("/remerciements", name="remerciements")
     */
    public function showRemerciements()
    {
        return $this->render('remerciement/index.html.twig', [
            'controller_name' => 'RemerciementController',
        ]);
    }

    /**
     * @Route("/equipe", name="equipe")
     */
    public function showEquipe()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function showPresentation()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
