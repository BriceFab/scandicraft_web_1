<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{
    /**
     * @Route("/faq", name="faq", options={"sitemap"="true"})
     * @param FaqRepository $repo
     * @return Response
     */
    public function showFaq(FaqRepository $repo)
    {
        return $this->render('help/faq.html.twig', [
            'faqs' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/wiki-et-nouveautes", name="wiki", options={"sitemap"="true"})
     * @Route("/wiki", name="simple_wiki", options={"sitemap"="true"})
     */
    public function showWiki()
    {
        return $this->render('help/wiki.html.twig');
    }
}
