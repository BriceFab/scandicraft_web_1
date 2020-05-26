<?php

namespace App\Controller\Forum;

use App\Repository\ForumCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function showCategories(ForumCategoryRepository $repo)
    {
        // $categories = $repo->getMainCategories('forumcategory');

        // return $this->render('forum/show_categories.html.twig', [
        //     'categories' => $categories
        // ]);
        return $this->render('maintenance/page_under_maintenance.html.twig');
    }
}
