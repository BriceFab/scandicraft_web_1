<?php

namespace App\Controller\Support;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    /**
     * @Route("/support", name="support")
     */
    public function supportPage()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
