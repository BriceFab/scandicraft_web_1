<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RemerciementController extends AbstractController
{
    /**
     * @Route("/remerciements", name="remerciement")
     */
    public function index()
    {
        return $this->render('remerciement/index.html.twig', [
            'controller_name' => 'RemerciementController',
        ]);
    }
}
