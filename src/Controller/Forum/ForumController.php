<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumSubCategory;
use App\Repository\ForumCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function showCategories(ForumCategoryRepository $repo)
    {
        $categories = $repo->getMainCategories('forumcategory');

        return $this->render('forum/show_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/forum/{main_slug}/{sub_slug}", name="forum_sub_category_list")
     * @ParamConverter("forumCategory", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("forumSubCategory", options={"mapping": {"sub_slug": "slug"}})
     */
    public function showDiscussion(ForumCategory $forumCategory, ForumSubCategory $forumSubCategory)
    {
        return $this->render('forum/sub_category_list.html.twig', [
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory
        ]);
    }
}
