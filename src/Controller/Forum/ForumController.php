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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @Route("/forum_data/post/answer", name="forum_post_answer", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function forumPostAnswer(Request $request, ForumDiscussionRepository $discussion_repo, EntityManagerInterface $em)
    {
        $discussion_id = $request->get('dicussion_id');
        $forumDiscussion = $discussion_repo->findOneBy(['id' => $discussion_id]);
        $message = $request->get('editor_result');

        if (!$forumDiscussion->getSubCategory()->getActive() || !$forumDiscussion->getSubCategory()->getCategory()->getActive() || $forumDiscussion->getArchive() || !isset($message)) {
            return $this->retirectToPreviousRoute($request, 'Forum: cette discussion n\'est plus active', ForumController::$default_route);
        }

        $answer = new ForumDiscussionAnswer();
        $answer->setDiscussion($forumDiscussion);
        $answer->setMessage($message);

        $em->persist($answer);
        $em->flush();

        $this->addFlash('notice', 'Réponse ajoutée avec succès');
        return $this->redirectToRoute('show_discussion', [
            'main_slug' => $forumDiscussion->getSubCategory()->getCategory()->getSlug(),
            'sub_slug' => $forumDiscussion->getSubCategory()->getSlug(),
            'discussion_slug' => $forumDiscussion->getSlug()
        ]);

        // $message = $request->get('editor_result');
        // if (!isset($message)) {
        // }
        // dd($request->get('editor_result'));



        // $defaultRoute = 'sondages';

        // try {
        //     //check survey
        //     $survey_id = $request->get('survey');
        //     $survey = $surveyRepository->findOneBy(['id' => $survey_id]);
        //     if (!$survey || !$survey->isEnable()) {
        //         $this->addFlash('error', 'Le sondage a expiré !');
        //         return $this->retirectToPreviousRoute($request, $defaultRoute);
        //     }

        //     //check if user has answer
        //     $has_answer = false;
        //     if ($this->getUser()) {
        //         $has_answer = $survey->countUserAnswers($this->getUser()->getId()) > 0;
        //     }
        //     if (!$has_answer) {
        //         $this->addFlash('error', 'Vous devez d\'abord répondre au sondage (voter) !');
        //         return $this->retirectToPreviousRoute($request, $defaultRoute);
        //     }

        //     $comment = new SurveyComments();
        //     $comment->setUser($this->getUser());
        //     $comment->setComment($request->get('comment'));
        //     $comment->setSurvey($survey);

        //     //validate entity
        //     $errors = $validator->validate($comment);
        //     if (count($errors) > 0) {
        //         $this->addFlash('error', $errors[0]->getMessage());
        //         return $this->retirectToPreviousRoute($request, $defaultRoute);
        //     }

        //     $em->persist($comment);
        //     $em->flush();

        //     $this->addFlash('notice', 'Merci pour votre commentaire !');
        // } catch (\Exception $e) {
        //     $this->addFlash('error', 'Une erreur est survenue..');
        // }

        // return $this->retirectToPreviousRoute($request, $defaultRoute);
    }

    /**
     * @Route("/forum_data/post/image", name="forum_post_image", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postImage(Request $request, SluggerInterface $slugger, Security $security)
    {
        try {
            $files_result = $this->uploadFiles($request->files, $slugger, $security->getUser()->getId(), $request);

            return $this->json([
                'result' => $files_result
            ], Response::HTTP_OK);
        } catch (FileException $e) {
            return $this->json([
                'errorMessage' => 'Erreure lors de l\upload du fichier'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function uploadFiles($files, $slugger, $user_id, $request)
    {
        $files_result = [];

        foreach ($files as $key => $file) {
            /** @var UploadedFile $file */
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

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
}
