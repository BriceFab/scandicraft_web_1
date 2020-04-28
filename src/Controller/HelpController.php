<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function showFaq(FaqRepository $repo)
    {
        return $this->render('help/faq.html.twig', [
            'faqs' => $repo->findAll()
        ]);
    }
}
