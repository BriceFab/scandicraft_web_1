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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

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

            $em->persist($discussion);
            $em->flush();

            $this->addFlash('notice', 'Discussion créer avec succès');
            return $this->redirectToRoute('show_discussion', [
                'main_slug' => $forumCategory->getSlug(),
                'sub_slug' => $forumSubCategory->getSlug(),
                'discussion_slug' => $discussion->getSlug()
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
}
