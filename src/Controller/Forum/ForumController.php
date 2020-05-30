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
use App\Service\ScandiCraftService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForumController extends BaseController
{
    private static $default_route = 'forum';
    private $sc_service;

    public function __construct(ScandiCraftService $sc_service)
    {
        $this->sc_service = $sc_service;
    }

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

        return $this->render('forum/show_discussion.html.twig', [
            'forumCategory' => $forumCategory,
            'forumSubCategory' => $forumSubCategory,
            'discussion' => $forumDiscussion,
        ]);
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
                $this->addFlash('error', 'Message trop court');
                return $this->retirectToPreviousRoute($request, ForumController::$default_route);
            } elseif (strlen($formated_message) > 300) {
                $this->addFlash('error', 'Message trop long');
                return $this->retirectToPreviousRoute($request, ForumController::$default_route);
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
     * @Route("/forum_data/post/image", name="forum_post_image", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postImage(Request $request, Security $security)
    {
        try {
            $files_result = $this->uploadFiles($request->files, $security->getUser()->getId(), $request);

            return $this->json([
                'result' => $files_result
            ], Response::HTTP_OK);
        } catch (FileException $e) {
            return $this->json([
                'errorMessage' => 'Erreur lors de l\'upload du fichier'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function uploadFiles($files, $user_id, $request)
    {
        $files_result = [];

        foreach ($files as $key => $file) {
            /** @var UploadedFile $file */
            // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $user_id . '-' . uniqid() . '.' . $file->guessExtension();

            $file_result = $file->move(
                $this->getParameter('forum_images_directory'),
                $newFilename
            );

            $files_result[] = [
                'name' => $newFilename,
                'size' => $file_result->getSize(),
                'url' => $request->getSchemeAndHttpHost() . $this->getParameter('forum_images_url') . $newFilename,
            ];
        }

        return $files_result;
    }

    /**
     * @Route("/delete/discussion_answer/{discussion_id}/{answer_id}", name="delete_discussion_answer", methods={"GET"})
     * @ParamConverter("discussion", options={"mapping": {"discussion_id": "id"}})
     * @ParamConverter("answer", options={"mapping": {"answer_id": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function deleteSurveyComment(Request $request, ForumDiscussion $discussion, ForumDiscussionAnswer $answer, EntityManagerInterface $em)
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

        return $this->retirectToPreviousRoute($request, ForumController::$default_route);
    }
}
