<?php

namespace App\Controller\Forum;

use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionAnswer;
use App\Repository\ForumDiscussionRepository;
use App\Service\ScandiCraftService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForumAnswerController extends ForumController
{
    private $sc_service;

    public function __construct(ScandiCraftService $sc_service)
    {
        $this->sc_service = $sc_service;
    }

    /**
     * @Route("/forum_data/post/answer", name="forum_post_answer", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function forumPostAnswer(Request $request, ForumDiscussionRepository $discussion_repo, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        try {
            $discussion_id = $request->get('dicussion_id');
            $forumDiscussion = $discussion_repo->findOneBy(['id' => $discussion_id]);
            $message = $request->get('editor_result');

            if (!$forumDiscussion->getSubCategory()->getActive() || !$forumDiscussion->getSubCategory()->getCategory()->getActive() || $forumDiscussion->getArchive() || !isset($message)) {
                return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
            }

            //validate message length
            $formated_message = $this->sc_service->removeBalises($message);
            if (strlen($formated_message) <= 25) {
                return $this->retirectToPreviousRoute($request, 'Message trop court. Minimum 25 caractères', ForumController::$default_route);
            } elseif (strlen($formated_message) > 300) {
                return $this->retirectToPreviousRoute($request, 'Message trop long', ForumController::$default_route);
            }

            $answer = new ForumDiscussionAnswer();
            $answer->setDiscussion($forumDiscussion);
            $answer->setMessage($message);

            //validate entity
            $errors = $validator->validate($answer);
            if (count($errors) > 0) {
                $this->addFlash('error', $errors[0]->getMessage());
                return $this->retirectToPreviousRoute($request, ForumController::$default_route);
            }

            $em->persist($answer);
            $em->flush();

            $this->addFlash('notice', 'Réponse ajoutée avec succès');
            return $this->redirectToRoute('show_discussion', [
                'main_slug' => $forumDiscussion->getSubCategory()->getCategory()->getSlug(),
                'sub_slug' => $forumDiscussion->getSubCategory()->getSlug(),
                'discussion_slug' => $forumDiscussion->getSlug()
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }
    }

    /**
     * @Route("/delete/discussion_answer/{discussion_id}/{answer_id}", name="delete_discussion_answer", methods={"GET"})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @ParamConverter("answer", options={"mapping": {"answer_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteDiscussionAnswer(Request $request, ForumDiscussion $discussion, ForumDiscussionAnswer $answer, EntityManagerInterface $em)
    {
        try {
            //check active
            if (!$discussion->getSubCategory()->getActive() || !$discussion->getSubCategory()->getCategory()->getActive() || $discussion->getArchive()) {
                return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
            }

            //check user owner comment
            if ($answer->getCreatedBy()->getId() !== $this->getUser()->getId()) {
                return $this->retirectToPreviousRoute($request, 'Vous ne pouvez pas supprimer ce commentaire.', ForumController::$default_route);
            }

            $em->remove($answer);
            $em->flush();

            $this->addFlash('notice', 'Votre message a été supprimé !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }

        return $this->retirectToPreviousRoute($request, null, ForumController::$default_route);
    }
}
