<?php

namespace App\Controller;

use App\Entity\DevProgression;
use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CommunityController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function forum()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/nouveautes", name="nouveautes")
     */
    public function showNews()
    {
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }

    /**
     * @Route("/sondages", name="sondages")
     */
    public function showSondages(Request $request, PaginatorInterface $paginator)
    {
        $data = $this->getDoctrine()->getRepository(Survey::class)->findBy([], ['createdAt' => 'ASC']);
        $data = array_filter($data, function ($val) {
            return $val->isEnable();
        });

        $sondages = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );

        return $this->render('survey/list.html.twig', [
            'sondages' => $sondages
        ]);
    }

    /**
     * @Route("/maintenances", name="maintenances")
     */
    public function showMaintenances()
    {
        return $this->render('maintenance/show_maintenances.html.twig', [
            'maintenances' => $this->em->getRepository(DevProgression::class)->findBy(['under_maintenance' => true], [
                'pourcentage' => 'desc'
            ]),
        ]);
    }
}
