<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumSubCategory;
use App\Repository\ForumCategoryRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ForumCategoryController extends ForumController
{
    /**
     * @Route("/forum", name="forum", options={"sitemap"="true"})
     * @param ForumCategoryRepository $repo
     * @return Response
     */
    public function showCategories(ForumCategoryRepository $repo)
    {
        $categories = $repo->getMainCategories('forumcategory');

        return $this->render('forum/show.html.twig', [
            'categories' => $categories
        ]);
    }

    // /**
    //  * @Route("/forum/{main_slug}", name="forum_show_category_detail")
    //  * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
    //  */
    // public function showCategoryDetail(Request $request, ForumCategory $category, ForumSubCategory $subCategory)
    // {
    //     if (!$category->getActive() || !$subCategory->getActive()) {
    //         return $this->retirectToPreviousRoute($request, 'Forum: cette catégorie n\'est plus active', ForumController::$default_route);
    //     }

    //     return $this->render('forum/show_subcategories.html.twig', [
    //         'forumCategory' => $category,
    //         'forumSubCategory' => $subCategory
    //     ]);
    // }

    /**
     * @Route(
     *     "/forum/{main_slug}/{sub_slug}",
     *     name="forum_show_subcategories",
     *     options={
     *          "sitemap"="true",
     *      }
     * )
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @param Request $request
     * @param ForumCategory $category
     * @param ForumSubCategory $subCategory
     * @return RedirectResponse|Response
     */
    public function showSubCategories(Request $request, ForumCategory $category, ForumSubCategory $subCategory)
    {
        if (!$category->getActive() || !$subCategory->getActive()) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette catégorie n\'est plus active', ForumController::$default_route);
        }

        return $this->render('forum/show_subcategories.html.twig', [
            'forumCategory' => $category,
            'forumSubCategory' => $subCategory
        ]);
    }
}
