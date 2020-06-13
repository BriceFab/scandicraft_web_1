<?php

namespace App\Controller\Vote;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    /**
     * @Route("/voter", name="voter")
     */
    public function voterPage()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
