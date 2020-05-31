<?php

namespace App\Controller\Forum;

use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionAnswer;
use App\Entity\ForumSubCategory;
use App\Form\ForumDiscussionAnswerType;
use App\Service\ScandiCraftService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForumAnswerController extends ForumController
{
    private $sc_service;
    private $em;
    private $validator;

    public function __construct(ScandiCraftService $sc_service, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->sc_service = $sc_service;
        $this->em = $em;
        $this->validator = $validator;
    }

    private function checkOwnItem(ForumDiscussionAnswer $answer)
    {
        if ($answer->getCreatedBy()->getId() !== $this->getUser()->getId()) {
            $this->createAccessDeniedException("Vous n'etes pas autorisé à effecter cette action");
        }
    }

    private function validateMessage(Request $request, ForumDiscussionAnswer $answer)
    {
        //length and balises
        $formated_message = $this->sc_service->removeBalises($answer->getMessage());
        if (strlen($formated_message) <= 25) {
            return $this->retirectToPreviousRoute($request, 'Message trop court. Minimum 25 caractères !', ForumController::$default_route);
        } elseif (strlen($formated_message) > 25000) {
            return $this->retirectToPreviousRoute($request, 'Message trop long. Maximum 25\'0000 caractères !', ForumController::$default_route);
        }

        $errors = $this->validator->validate($answer);
        if (count($errors) > 0) {
            return $this->retirectToPreviousRoute($request, $errors[0]->getMessage(), ForumController::$default_route);
        }

        // //enlève script>
        // $message = htmlspecialchars_decode($answer->getMessage(), ENT_HTML5);
        // $message = str_replace('script>', '', $message);
        // $answer->setMessage($message);
    }

    /**
     * @Route("/forum/answer/{main_slug}/{sub_slug}/{discussion_slug}/new_answer", name="forum_new_answer", methods={"POST"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_slug": "slug"}})
     * @IsGranted("ROLE_USER")
     */
    public function newAnswer(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;

        $answer = new ForumDiscussionAnswer();
        $answer->setDiscussion($discussion);
        $answer->setMessage($request->get('editor_result'));

        //validate entity
        $validate_result = $this->validateMessage($request, $answer);
        if ($validate_result !== null) return $validate_result;

        $this->em->persist($answer);
        $this->em->flush();

        $this->addFlash('notice', 'Message ajoutée avec succès');

        return $this->redirectToRoute('forum_show_discussion', [
            'main_slug' => $category->getSlug(),
            'sub_slug' => $subCategory->getSlug(),
            'discussion_slug' => $discussion->getSlug()
        ]);
    }

    /**
     * @Route("/forum/answer/{main_slug}/{sub_slug}/{discussion_slug}/modifier/{answer_id}", name="forum_edit_answer", methods={"GET", "POST"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_slug": "slug"}})
     * @ParamConverter("answer", options={"mapping": {"answer_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function editAnswer(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion, ForumDiscussionAnswer $answer)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;
        $this->checkOwnItem($answer);

        /** @var ForumDiscussionAnswer $answer */
        $form = $this->createForm(ForumDiscussionAnswerType::class, $answer);

        $form->handleRequest($request); //TODO edit bypass validator and save in bdd..
        if ($form->isSubmitted() && $form->isValid()) {
            $answer = $form->getData();

            //validate entity
            $validate_result = $this->validateMessage($request, $answer);
            if ($validate_result !== null) return $validate_result;

            $this->em->persist($answer);
            $this->em->flush();

            $this->addFlash('notice', 'Message modifié avec succès');
            return $this->redirectToRoute('forum_show_discussion', [
                'main_slug' => $category->getSlug(),
                'sub_slug' => $subCategory->getSlug(),
                'discussion_slug' => $discussion->getSlug()
            ]);
        }

        return $this->render('forum/forms/edit_answer.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'subCategory' => $subCategory,
            'discussion' => $discussion,
        ]);
    }

    /**
     * @Route("/forum/answer/{main_slug}/{sub_slug}/{discussion_slug}/{answer_id}/supprimer", name="forum_delete_answer", methods={"GET"})
     * @ParamConverter("category", options={"mapping": {"main_slug": "slug"}})
     * @ParamConverter("subCategory", options={"mapping": {"sub_slug": "slug"}})
     * @ParamConverter("discussion", options={"mapping": {"discussion_slug": "slug"}})
     * @ParamConverter("answer", options={"mapping": {"answer_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteAnswer(Request $request, ForumCategory $category, ForumSubCategory $subCategory, ForumDiscussion $discussion, ForumDiscussionAnswer $answer)
    {
        //Check access
        $access_result = $this->checkAutorized($request, $category, $subCategory, $discussion);
        if ($access_result !== null) return $access_result;
        $this->checkOwnItem($answer);

        $this->em->remove($answer);
        $this->em->flush();

        $this->addFlash('notice', 'Votre message a été supprimé !');

        return $this->retirectToPreviousRoute($request, null, ForumController::$default_route);
    }
}
