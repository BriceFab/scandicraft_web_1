<?php

namespace App\Controller\Forum;

use App\Controller\BaseController;
use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionAnswer;
use App\Entity\ForumSubCategory;
use App\Form\ForumDiscussionAnswerType;
use App\Form\ForumDiscussionType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumDiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends BaseController
{
    private static $default_route = 'forum';

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
    public function showSubCategoryDiscussion(Request $request, ForumCategory $forumCategory, ForumSubCategory $forumSubCategory, ForumDiscussionRepository $discu_repo)
    {
        if (!$forumCategory->getActive() || !$forumSubCategory->getActive()) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette catégorie n\'est plus active', ForumController::$default_route);
        }

        return $this->render('forum/sub_category_list.html.twig', [
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory
        ]);
    }

    /**
     * @Route("/forum/{main_slug}/{sub_slug}/ajouter", name="add_discussion")
     * @ParamConverter("forumCategory", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("forumSubCategory", options={"mapping": {"sub_slug": "slug"}})
     * @IsGranted("ROLE_USER")
     */
    public function postAddDiscussion(Request $request, ForumCategory $forumCategory, ForumSubCategory $forumSubCategory, EntityManagerInterface $em)
    {
        if (!$forumCategory->getActive() || !$forumSubCategory->getActive()) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette catégorie n\'est plus active', ForumController::$default_route);
        }

        if (!$forumSubCategory->getWritable()) {
            return $this->retirectToPreviousRoute($request, 'Forum: Vous ne pouvez pas écrire dans cette catégorie', ForumController::$default_route);
        }

        $discussion = new ForumDiscussion();
        $discussion->setSubCategory($forumSubCategory);

        $form = $this->createForm(ForumDiscussionType::class, $discussion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $discussion = $form->getData();

            $em->persist($discussion);
            $em->flush();

            $this->addFlash('notice', 'Discussion créer avec succès');
            //Todo show discussion
            return $this->redirectToRoute('forum_sub_category_list', [
                'main_slug' => $forumCategory->getSlug(),
                'sub_slug' => $forumSubCategory->getSlug()
            ]);
        }

        return $this->render('forum/create_discussion.html.twig', [
            'form' => $form->createView(),
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory
        ]);
    }

    /**
     * @Route("/forum/{main_slug}/{sub_slug}/{discussion_slug}", name="show_discussion")
     * @ParamConverter("forumCategory", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("forumSubCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("forumDiscussion", options={"mapping": {"discussion_slug": "slug"}})
     */
    public function showDiscussion(Request $request, ForumCategory $forumCategory, ForumSubCategory $forumSubCategory, ForumDiscussion $forumDiscussion, EntityManagerInterface $em)
    {
        if (!$forumCategory->getActive() || !$forumSubCategory->getActive() || $forumDiscussion->getArchive()) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
        }

        $answer = new ForumDiscussionAnswer();
        $answer->setDiscussion($forumDiscussion);

        $form = $this->createForm(ForumDiscussionAnswerType::class, $answer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer = $form->getData();

            $em->persist($answer);
            $em->flush();

            $this->addFlash('notice', 'Réponse ajoutée avec succès');
            return $this->redirectToRoute('show_discussion', [
                'main_slug' => $forumCategory->getSlug(),
                'sub_slug' => $forumSubCategory->getSlug(),
                'discussion_slug' => $forumDiscussion->getSlug()
            ]);
        }

        return $this->render('forum/show_discussion.html.twig', [
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory,
            'discussion' => $forumDiscussion,
            'form' => $form->createView(),
        ]);
    }
}
