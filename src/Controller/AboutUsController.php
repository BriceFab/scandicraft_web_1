<?php

namespace App\Controller;

use App\Entity\StaffCategory;
use App\Entity\Thanks;
use App\Entity\ThanksCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/remerciements", name="remerciements")
     */
    public function showRemerciements()
    {
        $remerciements_categories = $this->em->getRepository(ThanksCategory::class)->findBy([], ['priority' => 'ASC']);
        return $this->render('remerciement/index.html.twig', ['categories' => $remerciements_categories]);
    }

    /**
     * @Route("/equipe", name="equipe")
     */
    public function showEquipe()
    {
        $staff_categories = $this->em->getRepository(StaffCategory::class)->findBy([], ['priority' => 'ASC']);
        return $this->render('staff/index.html.twig', ['categories' => $staff_categories]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function showPresentation()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
