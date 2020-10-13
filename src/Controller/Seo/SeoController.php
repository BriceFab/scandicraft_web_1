<?php

namespace App\Controller\Seo;

use App\Service\SitemapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeoController extends AbstractController
{

    /**
     * @Route("/sitemap.{_format}", name="front_sitemap", requirements={"_format" = "xml"})
     * @param SitemapService $sitemapService
     * @return Response
     */
    public function siteMap(SitemapService $sitemapService)
    {
        return $this->render(
            'seo/sitemap.xml.twig', [
                'urls' => $sitemapService->generateUrls(),
            ]
        );
    }

}