<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * @Route("/boutique", name="store")
     */
    public function boutiquePage()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
