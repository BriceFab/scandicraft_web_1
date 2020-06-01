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
    private $em;

    public function __construct(ScandiCraftService $sc_service, EntityManagerInterface $em)
    {
        $this->sc_service = $sc_service;
        $this->em = $em;
    }

    private function checkOwnItem(ForumDiscussion $discussion)
    {
        if ($discussion->getCreatedBy()->getId() !== $this->getUser()->getId()) {
            $this->createAccessDeniedException("Vous n'etes pas autorisé à effecter cette action");
        }
    }

    /**
     * @Route("/forum/{main_slug}/{sub_slug}/{discussion_slug}", name="forum_show_discussion")
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_slug": "slug"}})
     */
    public function showDiscussion(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;

        return $this->render('forum/show_discussion.html.twig', [
            'forumCategory' => $category,
            'forumSubCategory' => $subCategory,
            'discussion' => $discussion,
        ]);
    }

    /**
     * @Route("/forum/discussion/{main_slug}/{sub_slug}/ajouter", name="forum_new_discussion", methods={"GET", "POST"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @IsGranted("ROLE_USER")
     */
    public function newDiscussion(Request $request, ForumCategory $category, ForumSubCategory $subCategory)
    {
        //init discussion
        $discussion = new ForumDiscussion();

        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;

        //handle form
        $discussion->setSubCategory($subCategory);

        return $this->discussionForm($request, $discussion);
    }

    /**
     * @Route("/forum/discussion/{main_slug}/{sub_slug}/modifier/{discussion_id}", name="forum_edit_discussion", methods={"GET", "POST"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function editDiscussion(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;
        $this->checkOwnItem($discussion);

        return $this->discussionForm($request, $discussion, true);
    }

    //Add and Edit
    private function discussionForm(Request $request, ForumDiscussion $discussion, $edit = false)
    {
        /** @var ForumDiscussion $discussion */
        $form = $this->createForm(ForumDiscussionType::class, $discussion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $discussion = $form->getData();

            //validate message length
            $formated_message = $this->sc_service->removeBalises($discussion->getMessage());
            if (strlen($formated_message) <= 25) {
                return $this->retirectToPreviousRoute($request, 'Message trop court. Minimum 25 caractères !', ForumController::$default_route);
            } elseif (strlen($formated_message) > 25000) {
                return $this->retirectToPreviousRoute($request, 'Message trop long. Maximum 25\'000 caractères !', ForumController::$default_route);
            }

            //enlève script>
            $message = htmlspecialchars_decode($discussion->getMessage(), ENT_HTML5);
            $message = str_replace('script>', '', $message);
            $discussion->setMessage($message);

            $this->em->persist($discussion);
            $this->em->flush();

            $this->addFlash('notice', 'Discussion ' . ($edit ? 'modifiée' : 'créée') . ' avec succès');
            return $this->redirectToRoute('forum_show_discussion', [
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

    /**
     * @Route("/forum/discussion/{main_slug}/{sub_slug}/supprimer/{discussion_id}", name="forum_delete_discussion", methods={"GET"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteDiscussion(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;
        $this->checkOwnItem($discussion);

        $this->em->remove($discussion);
        $this->em->flush();

        $this->addFlash('notice', 'Votre discussion a été supprimée !');

        return $this->retirectToPreviousRoute(null, null, ForumController::$default_route);
    }
}
