<?php

namespace App\Controller\Spoil;

use App\Entity\Spoil;
use App\Repository\SpoilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SpoilController extends AbstractController
{
    /**
     * @Route("/spoils", name="spoils")
     */
    public function showSpoils(SpoilRepository $repo)
    {
        return $this->render('spoils/list.html.twig', [
            'spoils' => $repo->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/spoil/{id}", name="show_spoil")
     */
    public function showSpoilDetail(Spoil $spoil)
    {
        return $this->render('spoils/show.html.twig', [
            'spoil' => $spoil
        ]);
    }
}
