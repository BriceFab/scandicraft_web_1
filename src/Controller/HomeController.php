<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/jouer", name="jouer")
     * @Route("/play")
     * @Route("/join")
    */
    public function jouer() {
        return $this->render('jouer/index.html.twig');
    }

}
