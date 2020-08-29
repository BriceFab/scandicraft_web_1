<?php

namespace App\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends AbstractController
{
    /**
     * @Route("/crediter", name="credit")
     */
    public function index()
    {
        return $this->render('store/credit/index.html.twig', [
        ]);
    }
}
