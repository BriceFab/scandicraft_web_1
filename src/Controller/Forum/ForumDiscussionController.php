<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumSubCategory;
use App\Form\ForumDiscussionType;
use App\Service\ScandiCraftService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ForumDiscussionController extends ForumController
{
    private $sc_service;

    public function __construct(ScandiCraftService $sc_service)
    {
        $this->sc_service = $sc_service;
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

        return $this->discussionForum($request, $em, $discussion);
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

        if ($forumSubCategory->getAcceptStaffOnly() && $forumDiscussion->getStaffOnly()) {
            $this->denyAccessUnlessGranted('ROLE_STAFF');
        }

        return $this->render('forum/show_discussion.html.twig', [
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory,
            'discussion' => $forumDiscussion,
        ]);
    }

    /**
     * @Route("/delete/discussion/{discussion_id}", name="delete_discussion", methods={"GET"})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteDiscussion(Request $request, ForumDiscussion $discussion, EntityManagerInterface $em)
    {
        try {
            //check active
            if (!$discussion->getSubCategory()->getActive() || !$discussion->getSubCategory()->getCategory()->getActive() || $discussion->getArchive()) {
                return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
            }

            //check user owner discussion
            if ($discussion->getCreatedBy()->getId() !== $this->getUser()->getId()) {
                return $this->retirectToPreviousRoute($request, 'Vous ne pouvez pas supprimer cette discussion.', ForumController::$default_route);
            }

            $em->remove($discussion);
            $em->flush();

            $this->addFlash('notice', 'Votre discussion a été supprimée !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }

        return $this->retirectToPreviousRoute(null, null, ForumController::$default_route);
    }

    /**
     * @Route("/modifier/forum/discussion/{discussion_id}", name="edit_discussion", methods={"GET", "POST"})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function editDiscussion(Request $request, ForumDiscussion $discussion, EntityManagerInterface $em)
    {
        if ($discussion->getArchive() || !$discussion->getSubCategory()->getActive() || !$discussion->getSubCategory()->getCategory()->getActive()) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette catégorie n\'est plus active', ForumController::$default_route);
        }

        if (!$discussion->getSubCategory()->getWritable()) {
            return $this->retirectToPreviousRoute($request, 'Forum: Vous ne pouvez pas écrire dans cette catégorie', ForumController::$default_route);
        }

        if ($discussion->getCreatedBy()->getId() !== $this->getUser()->getId()) {
            return $this->retirectToPreviousRoute($request, 'Vous ne pouvez pas modifier cette discussion.', ForumController::$default_route);
        }

        return $this->discussionForum($request, $em, $discussion, true);
    }

    //Add and Edit
    private function discussionForum($request, $em, ForumDiscussion $discussion, $edit = false)
    {
        /** @var ForumDiscussion $discussion */
        $form = $this->createForm(ForumDiscussionType::class, $discussion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $discussion = $form->getData();

            //validate message length
            $formated_message = $this->sc_service->removeBalises($discussion->getMessage());
            if (strlen($formated_message) <= 25) {
                return $this->retirectToPreviousRoute($request, 'Message trop court. Minimum 25 caractères', ForumController::$default_route);
            } elseif (strlen($formated_message) > 300) {
                return $this->retirectToPreviousRoute($request, 'Message trop long', ForumController::$default_route);
            }

            //enlève script>
            $message = htmlspecialchars_decode($discussion->getMessage(), ENT_HTML5); 
            $message = str_replace('script>', '', $message);
            $discussion->setMessage($message);

            $em->persist($discussion);
            $em->flush();

            $this->addFlash('notice', 'Discussion ' . ($edit ? 'modifiée' : 'créer') . ' avec succès');
            return $this->redirectToRoute('show_discussion', [
                'main_slug' => $discussion->getSubCategory()->getCategory()->getSlug(),
                'sub_slug' => $discussion->getSubCategory()->getSlug(),
                'discussion_slug' => $discussion->getSlug()
            ]);
        }

        return $this->render('forum/forms/discussion_form.html.twig', [
            'form' => $form->createView(),
            'forumCategory' => $discussion->getSubCategory()->getCategory(),
            'forumSubCategory' => $discussion->getSubCategory(),
            'edit' => $edit
        ]);
    }
}
