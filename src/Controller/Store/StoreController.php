<?php

namespace App\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * @Route("/boutique", name="store")
     * @Route("/store")
     */
    public function boutiquePage()
    {
        return $this->render('store/index.html.twig');
    }
}
