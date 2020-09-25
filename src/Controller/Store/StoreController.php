<?php

namespace App\Controller\Store;

use App\Repository\PaymentTypesRepository;
use App\Repository\StoreArticleRepository;
use App\Repository\StoreCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * @Route("/boutique", name="store")
     * @Route("/store")
     * @param StoreCategoryRepository $categoriesRepository
     * @param StoreArticleRepository $articlesRepository
     * @return Response
     */
    public function boutiquePage(StoreCategoryRepository $categoriesRepository, StoreArticleRepository $articlesRepository, PaymentTypesRepository $paymentTypes)
    {
        return $this->render('store/index.html.twig', [
            'categories' => $categoriesRepository->findBy(['enable' => true]),
            'articles' => $articlesRepository->findBy(['enable' => true]),
            'payment_types_count' => $paymentTypes->count(['enable' => true]),
        ]);
    }
}
