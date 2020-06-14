<?php

namespace App\Controller;

use App\Repository\SpoilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(SpoilRepository $spoilRepository)
    {
        $last = $spoilRepository->findOneBy([], ['createdAt' => 'DESC']);
        $missing_share = $last->getShareGoal() - $last->getCurrentShare();

        return $this->render('home/index.html.twig', [
            'last_spoil_missing_number' => $missing_share > 0 ? $missing_share : 0
        ]);
    }

    /**
     * @Route("/jouer", name="jouer")
     * @Route("/play")
     * @Route("/join")
     */
    public function jouer()
    {
        return $this->render('jouer/index.html.twig');
    }
}
